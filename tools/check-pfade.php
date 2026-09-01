<?php
/* =====================================================================
   B³ Retreats — prueft, dass JEDER Schluessel des Registers auch wirklich
   auf der Seite ankommt.

   Der Anlass war ein Fund, kein Verdacht: 16 Schluessel tragen einen Index
   (`ort.mosaic[0].src` — die acht Bilder im Mosaik „Der Ort"). b3_set_path()
   kannte die eckige Klammer anfangs nicht und legte einen Schluessel namens
   „mosaic[0]" NEBEN dem Mosaik an. In der Datenbank stand dann die neue
   Adresse, auf der Seite blieb das alte Bild — ein Fehler, den man erst
   bemerkt, wenn die Kundin sagt „ich hab das doch geaendert".

   Die Probe geht darum fuer jeden Schluessel den ganzen Weg:

       Wert setzen  ->  Seite bauen  ->  steht der Wert drin?

   Ohne Datenbank: die Ueberschreibung wird direkt in die Datenstruktur
   geschrieben, genau dort, wo b3_data() sie sonst hinschreibt.

   Aufruf:
       php tools/check-pfade.php
   ===================================================================== */
declare(strict_types=1);

$wurzel = dirname(__DIR__);
require_once $wurzel . '/partials/render.php';

$register = content_registry();

/* ALLE Vorlagen, nicht nur die Startseite. Seit es /danke gibt, wohnt ein
   Teil der Schluessel dort — und wer hier nur index.html prueft, meldet
   dreizehn Felder als wirkungslos, die in Wahrheit sauber ankommen. */
$vorlagen = [];
foreach (glob($wurzel . '/tools/templates/*.html') ?: [] as $datei) {
    $zeilen = [];
    foreach (explode("\n", (string) file_get_contents($datei)) as $n => $z) {
        $zeilen[] = [$n + 1, $z];
    }
    $vorlagen[basename($datei)] = $zeilen;
}

/** Eine Vorlage mit genau einer Ueberschreibung bauen. */
/* Schalter, die erst zur Laufzeit gesetzt werden (b3_runtime_set in
   index.php) und im Bestand leer stehen. Hier werden ALLE eingeschaltet,
   auch die einander ausschliessenden.

   Der Grund ist genau der Fehlalarm, den diese Probe sonst ausloest:
   warteliste.danke steht innerhalb von {{? warteliste.ok }}, und ohne
   gesetzte Flagge wird der Block nie gerendert. Das Feld waere als
   „kommt nicht an" gemeldet, obwohl es nach dem Absenden sauber
   erscheint. Eine Probe, die falschen Alarm schlaegt, wird nach dem
   dritten Mal ignoriert — und deckt dann auch die echten Faelle nicht
   mehr auf. */
const LAUFZEIT_SCHALTER = [
    'warteliste.ok'       => '1',
    'warteliste.nicht_ok' => '1',
    'warteliste.formular' => '1',
    'warteliste.ist_pruefen' => '1',
    'warteliste.ist_ungueltig' => '1',
    'haus.shared.frei' => '1',
    'haus.shared.voll' => '1',
    'haus.friends.frei' => '1',
    'haus.friends.voll' => '1',
];

function bauen_mit(array $zeilen, string $schluessel, $wert): string
{
    $daten = b3_site_json();
    if (!b3_set_path($daten, $schluessel, $wert)) {
        return '<<PFAD NICHT SETZBAR>>';
    }
    /* Ein Tarif auf genau EINEN freien Platz, der andere auf zwei.

       „haus.plaetze_einer" steht nur da, wenn noch genau ein Platz frei
       ist — im Bestand sind es acht und zwei, der Zweig kaeme also nie
       vor. Mit 1 und 2 laufen beide Faelle in einem einzigen Durchlauf:
       der Sondersatz fuer den letzten Platz und der mit der Zahl. */
    foreach (['shared' => '1', 'friends' => '2'] as $tarif => $anzahl) {
        if ($schluessel !== "haus.$tarif.plaetze") {
            b3_set_path($daten, "haus.$tarif.plaetze", $anzahl);
        }
    }

    /* Dieselbe Rechnung wie im Seitenaufbau. Ohne sie erschienen die
       Felder rund um die freien Plaetze als wirkungslos: sie stehen nicht
       woertlich in der Vorlage, sondern gehen in abgeleitete Werte ein. */
    foreach (plaetze_ableiten($daten) as $pfad => $abgeleitet) {
        if ($pfad !== $schluessel) {
            b3_set_path($daten, $pfad, $abgeleitet);
        }
    }
    /* DANACH alle Schalter einschalten, auch die einander ausschliessenden.
       Vorher waere es wirkungslos: plaetze_ableiten() ueberschreibt „voll"
       anhand der acht freien Plaetze aus dem Bestand, und der Zweig fuer
       „ausgebucht" wuerde nie gerendert. Hier geht es nicht um einen
       stimmigen Seitenzustand, sondern nur darum, JEDEN Zweig einmal zu
       durchlaufen. */
    foreach (LAUFZEIT_SCHALTER as $flagge => $an) {
        if ($flagge !== $schluessel) {
            b3_set_path($daten, $flagge, $an);
        }
    }
    $aus = [];
    b3_render_block($zeilen, 0, count($zeilen), [$daten], $aus, false);
    return implode("\n", $aus);
}

/** Kommt der Wert in IRGENDEINER Vorlage an? */
function irgendwo(array $vorlagen, string $schluessel, string $marke): bool
{
    foreach ($vorlagen as $zeilen) {
        if (strpos(bauen_mit($zeilen, $schluessel, $marke), $marke) !== false) {
            return true;
        }
    }
    return false;
}

$geprueft = 0;
$fehler = [];
$uebersprungen = [];

foreach ($register as $schluessel => $def) {
    $typ = (string) ($def['type'] ?? 'text');

    // Listen tragen ihren Inhalt ueber cl(); eine Marke laesst sich dort
    // nicht sinnvoll einsetzen, ohne die Struktur zu erfinden. Sie werden
    // ueber ihre Unterfelder ohnehin mitgeprueft.
    if ($typ === 'list') {
        $uebersprungen[] = $schluessel;
        continue;
    }

    $geprueft++;

    /* DATUMSFELDER BRAUCHEN EINE DATUMSFOERMIGE PROBE.

       termin.start und termin.ende stehen nirgends woertlich auf der
       Seite: aus ihnen rechnet partials/termin.php die Marken {datum},
       {monat}, {start_tag} und so weiter, die dann in den Texten
       stecken. Eine Probe wie „ZZPROBE7ZZ" ist kein gueltiges Datum,
       wird verworfen — und das Feld erschiene als wirkungslos, obwohl es
       in Wahrheit jede Datumsangabe der Seite bestimmt.

       Statt das per Ausnahmeliste wegzudruecken, wird mit einem echten,
       unverwechselbaren Datum geprueft. Der 07. eines Monats im Jahr
       2099 taucht sonst nirgends auf; erscheint er im Ergebnis, ist der
       Weg vom Feld bis zur Seite tatsaechlich belegt — und nicht bloss
       behauptet. */
    if ($typ === 'datum') {
        $probe = $schluessel === 'termin.ende' ? '2099-03-19' : '2099-03-07';
        $erwartet = $schluessel === 'termin.ende' ? '19.' : '07.';
        $treffer = false;
        foreach ($vorlagen as $zeilen) {
            $aus = bauen_mit($zeilen, $schluessel, $probe);
            if (strpos($aus, $erwartet) !== false && strpos($aus, '2099') !== false) {
                $treffer = true;
                break;
            }
        }
        if (!$treffer) {
            $fehler[] = $schluessel . '  (Datum kommt auf keiner Seite an)';
        }
        continue;
    }

    /* Zahlenfelder brauchen eine Zahl. „ZZPROBE12ZZ" wird zu 0 gecastet —
       aus „noch X frei" wuerde „ausgebucht", und das Feld erschiene als
       wirkungslos, obwohl es genau diese Anzeige steuert. */
    $marke = $typ === 'number' ? '90210777' : 'ZZPROBE' . $geprueft . 'ZZ';

    if (!irgendwo($vorlagen, $schluessel, $marke)) {
        $fehler[] = $schluessel . '  (in keiner Vorlage angekommen)';
    }
}

printf("%d Schluessel gegen %d Vorlagen geprueft, %d Listen uebersprungen.\n",
    $geprueft, count($vorlagen), count($uebersprungen));

if ($fehler) {
    echo "\nDiese Felder kommen auf der Seite NICHT an:\n";
    foreach ($fehler as $f) {
        echo "  $f\n";
    }
    echo "\nEine Aenderung daran waere im Panel sichtbar, auf der Seite aber wirkungslos.\n";
    exit(1);
}

echo "Jeder Schluessel des Registers wirkt sich auf der Seite aus.\n";
exit(0);
