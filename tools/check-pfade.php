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
$vorlage  = $wurzel . '/tools/templates/index.html';
$text     = (string) file_get_contents($vorlage);

$zeilen = [];
foreach (explode("\n", $text) as $n => $z) {
    $zeilen[] = [$n + 1, $z];
}

/** Die Seite mit genau einer Ueberschreibung bauen. */
function bauen_mit(array $zeilen, string $schluessel, $wert): string
{
    $daten = b3_site_json();
    if (!b3_set_path($daten, $schluessel, $wert)) {
        return '<<PFAD NICHT SETZBAR>>';
    }
    $aus = [];
    b3_render_block($zeilen, 0, count($zeilen), [$daten], $aus, false);
    return implode("\n", $aus);
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

    $marke = 'ZZPROBE' . $geprueft . 'ZZ';
    $html = bauen_mit($zeilen, $schluessel, $marke);
    $geprueft++;

    if (strpos($html, $marke) === false) {
        $fehler[] = $schluessel . ($html === '<<PFAD NICHT SETZBAR>>'
            ? '  (Pfad liess sich nicht setzen)'
            : '  (gesetzt, aber nicht in der Seite)');
    }
}

printf("%d Schluessel geprueft, %d Listen uebersprungen.\n", $geprueft, count($uebersprungen));

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
