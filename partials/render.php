<?php
/* =====================================================================
   B³ Retreats — die oeffentliche Seite aus der Datenbank bauen.

   DAS PROBLEM, DAS DIESE DATEI LOEST: bis hierher schrieb das Panel brav
   in die Datenbank, und die Besucherin sah davon nichts. index.html ist
   eine erzeugte, statische Datei — wer im Panel einen Text aenderte,
   aenderte eine Zeile in `content`, sonst nichts. Die Redaktion lief ins
   Leere.

   DER WEG, DER NICHT GEGANGEN WURDE: die 742 Zeilen der Vorlage von Hand
   nach PHP uebersetzen. Dann gaebe es zwei Fassungen derselben Seite —
   tools/templates/index.html fuer den oertlichen Bau und index.php fuer
   den Server — und sie waeren nach der zweiten Aenderung auseinander.

   DER WEG, DER GEGANGEN WURDE: dieselbe Vorlage, ein zweiter Leser. Die
   Vorlage kennt genau drei Konstrukte (siehe tools/build-site.py), und die
   sind hier noch einmal umgesetzt:

       {{ pfad.zum.wert }}                             ein Wert
       {{# pfad.zur.liste }} … {{/ pfad.zur.liste }}   Wiederholung
       {{? pfad.zum.wert }} … {{/? pfad.zum.wert }}    nur wenn gefuellt

   Innerhalb einer Wiederholung greift {{ .feld }} auf den laufenden
   Eintrag zu, 'liste[2].feld' spricht einen Eintrag direkt an.

   DIE DATENLAGE, IN DREI SCHICHTEN:

       1. content/site.json     der vollstaendige Bestand
       2. Zeilen aus `content`  was die Redaktion geaendert hat
       3. das Ergebnis          1, ueberschrieben von 2

   Warum site.json die Grundschicht ist und nicht partials/registry/:
   das Register kennt nur die Felder, die im Panel auftauchen. Die Vorlage
   greift auf mehr zu — die Auszeichnung fuer Google zum Beispiel. Faellt
   ein Pfad aus, steht ein Loch in der Seite. site.json ist per Bau
   vollstaendig: build-site.py loest damit jeden Pfad auf.

   GRUNDSATZ DER UNVERWUESTLICHKEIT: keine Datenbank, kaputtes JSON in einer
   Zeile, ein Pfad, den es nicht gibt — nichts davon bricht die Seite ab.
   Es wird protokolliert und mit der naechsttieferen Schicht gerendert. Im
   schlimmsten Fall sieht die Besucherin die Seite wie vor dem Panel.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/content.php';
require_once __DIR__ . '/termin.php';

/** Ausnahme nur fuer den Pruefbetrieb (--check), nie im Netzbetrieb. */
class B3TemplateError extends RuntimeException
{
}

/* ---------------------------------------------------------------------
   Schicht 1 + 2: Bestand und Ueberschreibungen
   --------------------------------------------------------------------- */

/** content/site.json als verschachteltes Array. */
function b3_site_json(): array
{
    static $daten = null;
    if ($daten !== null) {
        return $daten;
    }
    $daten = [];
    $datei = __DIR__ . '/../content/site.json';
    if (!is_file($datei)) {
        error_log('B3 render: content/site.json fehlt — Seite bleibt leer.');
        return $daten;
    }
    $roh = json_decode((string) file_get_contents($datei), true);
    if (!is_array($roh)) {
        error_log('B3 render: content/site.json ist kein gueltiges JSON.');
        return $daten;
    }
    $daten = $roh;
    return $daten;
}

/**
 * Einen Wert unter 'a.b.c' in ein verschachteltes Array schreiben.
 *
 * Legt fehlende Zwischenebenen an. Steht auf dem Weg etwas, das kein Array
 * ist, wird NICHT ueberschrieben: eine Zeile in `content` soll keine
 * Struktur zertreten, die die Vorlage als Liste erwartet.
 *
 * DER INDEX MUSS MIT. 16 Schluessel im Register sehen so aus:
 *
 *     ort.mosaic[0].src
 *
 * Das Mosaik im Abschnitt „Der Ort" hat acht feste Plaetze mit je eigenem
 * CSS-Klassennamen, ist also keine frei wachsende Liste, sondern acht
 * einzelne Felder. Ohne die Klammer-Behandlung hier legte eine Aenderung
 * einen Schluessel namens „mosaic[0]" NEBEN dem echten Mosaik an: in der
 * Datenbank stand die neue Adresse, die Seite zeigte weiter das alte Bild,
 * und niemand haette gewusst, wo es haengt.
 */
function b3_set_path(array &$ziel, string $pfad, $wert): bool
{
    $teile = explode('.', $pfad);
    $knoten = &$ziel;

    foreach ($teile as $i => $teil) {
        $letzter = ($i === count($teile) - 1);

        // 'mosaic[0]' — erst in den benannten Zweig, dann auf den Platz.
        if (preg_match('/^(.+)\[(\d+)\]$/', $teil, $t)) {
            $name = $t[1];
            $pos  = (int) $t[2];

            if (!isset($knoten[$name]) || !is_array($knoten[$name])) {
                return false;               // die Liste gibt es nicht — nichts erfinden
            }
            $knoten = &$knoten[$name];

            if (!array_key_exists($pos, $knoten)) {
                return false;               // Platz gibt es nicht
            }
            if ($letzter) {
                $knoten[$pos] = $wert;
                return true;
            }
            if (!is_array($knoten[$pos])) {
                return false;
            }
            $knoten = &$knoten[$pos];
            continue;
        }

        if ($letzter) {
            $knoten[$teil] = $wert;
            return true;
        }
        if (!isset($knoten[$teil]) || !is_array($knoten[$teil])) {
            if (isset($knoten[$teil])) {
                return false;               // Skalar im Weg — Finger weg
            }
            $knoten[$teil] = [];
        }
        $knoten = &$knoten[$teil];
    }
    return false;
}

/** Wert unter 'a.b.c' lesen, oder null. */
function b3_get_path(array $quelle, string $pfad)
{
    $knoten = $quelle;
    foreach (explode('.', $pfad) as $teil) {
        if (!is_array($knoten) || !array_key_exists($teil, $knoten)) {
            return null;
        }
        $knoten = $knoten[$teil];
    }
    return $knoten;
}

/**
 * Bestand + Ueberschreibungen der Redaktion.
 *
 * Ein LEERER Wert in `content` heisst „zurueck zum Standard" — genauso wie
 * in c(). Er wird uebersprungen, nicht als leerer Text uebernommen.
 */
/**
 * Ein Wert, der NUR fuer diesen einen Aufruf gilt — nicht aus site.json und
 * nicht aus der Datenbank.
 *
 * Gebraucht fuer das, was von der Anfrage abhaengt und nicht vom Bestand:
 * ob gerade der Dank fuer den Wartelisten-Eintrag stehen soll oder das
 * Formular. Solche Schalter gehoeren NICHT ins Panel — die Kundin kann sie
 * nicht sinnvoll setzen, und ein Feld ohne Wirkung ist schlimmer als keins.
 *
 * MUSS vor dem ersten b3_data() aufgerufen werden; danach steht der Bestand.
 */
function b3_runtime_set(string $pfad, $wert): void
{
    $laufzeit = &b3_runtime();
    $laufzeit[$pfad] = $wert;

    /* Den Bestand verwerfen, damit er die neuen Werte aufnimmt.
       OHNE DIESE ZEILE gilt: wer b3_data() einmal gelesen hat — und sei
       es nur, um daraus etwas zu berechnen —, friert den Bestand ein;
       jeder spaeter gesetzte Wert verpufft. Genau das ist passiert: die
       Plaetze wurden aus b3_data() gelesen, die abgeleiteten Schalter
       danach gesetzt, und die Seite zeigte weiter den Buchungsknopf,
       obwohl null Plaetze eingetragen waren. */
    $stand = &b3_runtime_stand();
    $stand++;
}

/** Zaehler, der sich bei jeder Laufzeitaenderung erhoeht. */
function &b3_runtime_stand(): int
{
    static $stand = 0;
    return $stand;
}

/** Sammelstelle fuer b3_runtime_set(). */
function &b3_runtime(): array
{
    static $werte = [];
    return $werte;
}

function b3_data(): array
{
    static $daten = null;
    static $gebautBei = -1;

    $stand = b3_runtime_stand();
    if ($daten !== null && $gebautBei === $stand) {
        return $daten;
    }
    $gebautBei = $stand;
    $daten = b3_site_json();

    foreach (b3_content_all() as $schluessel => $wert) {
        $wert = (string) $wert;
        if (trim($wert) === '') {
            continue;                       // leer = Standard
        }
        // Steht im Bestand an dieser Stelle eine Liste, muss auch die
        // Ueberschreibung eine sein. Sonst rendert die Wiederholung nichts.
        $bestand = b3_get_path($daten, $schluessel);
        if (is_array($bestand)) {
            $dekodiert = json_decode($wert, true);
            if (!is_array($dekodiert)) {
                error_log("B3 render: '$schluessel' ist kein gueltiges JSON — Standard genommen.");
                continue;
            }
            $wert = $dekodiert;
        }
        b3_set_path($daten, $schluessel, $wert);
    }

    // Zuletzt, damit die Laufzeitwerte gewinnen: sie beschreiben DIESEN
    // Aufruf, der Bestand nur den Normalfall.
    foreach (b3_runtime() as $schluessel => $wert) {
        b3_set_path($daten, $schluessel, $wert);
    }

    return $daten;
}

/* ---------------------------------------------------------------------
   Der Leser der Vorlage
   --------------------------------------------------------------------- */

/**
 * 'a.b.c' oder '.feld' gegen den Stapel offener Gueltigkeitsbereiche.
 *
 * $scopes[0] ist immer der Gesamtbestand, das letzte Element der laufende
 * Eintrag einer Wiederholung. Fehlt der Pfad, wird im Netzbetrieb null
 * geliefert (und protokolliert), im Pruefbetrieb geworfen.
 */
function b3_lookup(string $pfad, array $scopes, string $wo, bool $streng)
{
    $fehlt = function (string $text) use ($wo, $streng) {
        if ($streng) {
            throw new B3TemplateError("$wo: $text");
        }
        error_log("B3 render: $wo: $text");
        return null;
    };

    if (str_starts_with($pfad, '.')) {
        if (count($scopes) < 2) {
            return $fehlt("'$pfad' steht ausserhalb einer Wiederholung");
        }
        $knoten = $scopes[count($scopes) - 1];
        $teile  = explode('.', substr($pfad, 1));
    } else {
        $knoten = $scopes[0];
        $teile  = explode('.', $pfad);
    }

    foreach ($teile as $i => $teil) {
        if ($teil === '') {
            continue;
        }
        $bisher = implode('.', array_slice($teile, 0, $i + 1));

        // 'mosaic[3]' — ein Eintrag darf auch direkt angesprochen werden.
        if (preg_match('/^(.+)\[(\d+)\]$/', $teil, $t)) {
            $name = $t[1];
            $pos  = (int) $t[2];
            if (!is_array($knoten) || !array_key_exists($name, $knoten)) {
                return $fehlt("'$bisher' fehlt in site.json");
            }
            $knoten = $knoten[$name];
            if (!is_array($knoten)) {
                return $fehlt("'$name' ist keine Liste");
            }
            if (!array_key_exists($pos, $knoten)) {
                return $fehlt("'$name' hat keinen Eintrag Nummer " . ($pos + 1));
            }
            $knoten = $knoten[$pos];
            continue;
        }

        if (!is_array($knoten) || !array_key_exists($teil, $knoten)) {
            return $fehlt("'$bisher' fehlt in site.json");
        }
        $knoten = $knoten[$teil];
    }
    return $knoten;
}

/** {{ … }} in einer Zeile ersetzen. Werte gehen WOERTLICH hinein. */
function b3_substitute(string $zeile, array $scopes, string $wo, bool $streng): string
{
    return (string) preg_replace_callback(
        '/\{\{\s*([^#\/?][^}]*?)\s*\}\}/',
        function (array $m) use ($scopes, $wo, $streng): string {
            $wert = b3_lookup($m[1], $scopes, $wo, $streng);
            if (is_array($wert)) {
                if ($streng) {
                    throw new B3TemplateError("$wo: '{$m[1]}' ist kein einfacher Wert");
                }
                error_log("B3 render: $wo: '{$m[1]}' ist kein einfacher Wert");
                return '';
            }
            return $wert === null ? '' : termin_ersetzen((string) $wert, $scopes[0]);
        },
        $zeile
    );
}

/**
 * Die Datums-Marken in einem Wert ersetzen.
 *
 * REINE ZEICHENKETTEN-ERSETZUNG, mit Absicht. Der Wert kommt aus der
 * Datenbank und damit aus dem Panel; ihn als Vorlage auszuwerten hiesse,
 * dass ein dort eingetragenes {{ … }} beliebige Werte auslesen koennte.
 * Ersetzt werden nur die acht bekannten Marken, sonst nichts.
 */
function termin_ersetzen(string $wert, array $daten): string
{
    if (strpos($wert, '{') === false) {
        return $wert;                   // der Normalfall, ohne jeden Aufwand
    }
    static $marken = null, $fuer = null;
    if ($marken === null || $fuer !== ($daten['termin'] ?? null)) {
        $marken = termin_marken($daten);
        $fuer = $daten['termin'] ?? null;
    }
    return strtr($wert, $marken);
}

/** Den passenden schliessenden Marker suchen, Verschachtelung mitgezaehlt. */
function b3_find_close(array $zeilen, int $auf, int $ende, string $art, string $pfad): int
{
    $offen  = $art === 'liste' ? '/^\s*\{\{#\s*(.+?)\s*\}\}\s*$/' : '/^\s*\{\{\?\s*(.+?)\s*\}\}\s*$/';
    $zu     = $art === 'liste' ? '/^\s*\{\{\/\s*([^?].*?)\s*\}\}\s*$/' : '/^\s*\{\{\/\?\s*(.+?)\s*\}\}\s*$/';
    $tiefe  = 0;

    for ($j = $auf + 1; $j < $ende; $j++) {
        $text = $zeilen[$j][1];
        if (preg_match($offen, $text, $m) && $m[1] === $pfad) {
            $tiefe++;
            continue;
        }
        if (preg_match($zu, $text, $m) && $m[1] === $pfad) {
            if ($tiefe === 0) {
                return $j;
            }
            $tiefe--;
        }
    }
    throw new B3TemplateError("Zeile {$zeilen[$auf][0]}: '$pfad' wird nie geschlossen");
}

/** Einen Abschnitt der Vorlage rendern; $zeilen sind (Nummer, Text)-Paare. */
function b3_render_block(array $zeilen, int $start, int $ende, array $scopes, array &$aus, bool $streng): void
{
    $i = $start;
    while ($i < $ende) {
        [$nr, $text] = $zeilen[$i];
        $wo = "Zeile $nr";

        if (preg_match('/^\s*\{\{#\s*(.+?)\s*\}\}\s*$/', $text, $m)) {
            $pfad  = $m[1];
            $close = b3_find_close($zeilen, $i, $ende, 'liste', $pfad);
            $liste = b3_lookup($pfad, $scopes, $wo, $streng);
            if (is_array($liste)) {
                foreach ($liste as $eintrag) {
                    b3_render_block($zeilen, $i + 1, $close, array_merge($scopes, [$eintrag]), $aus, $streng);
                }
            } elseif ($liste !== null) {
                if ($streng) {
                    throw new B3TemplateError("$wo: '$pfad' ist keine Liste");
                }
                error_log("B3 render: $wo: '$pfad' ist keine Liste");
            }
            $i = $close + 1;
            continue;
        }

        if (preg_match('/^\s*\{\{\?\s*(.+?)\s*\}\}\s*$/', $text, $m)) {
            $pfad  = $m[1];
            $close = b3_find_close($zeilen, $i, $ende, 'bedingung', $pfad);
            $wert  = b3_lookup($pfad, $scopes, $wo, false);   // fehlt = einfach nicht zeigen
            if (!empty($wert)) {
                b3_render_block($zeilen, $i + 1, $close, $scopes, $aus, $streng);
            }
            $i = $close + 1;
            continue;
        }

        if (preg_match('/^\s*\{\{\/\??\s*.+?\s*\}\}\s*$/', $text)) {
            throw new B3TemplateError("$wo: schliessender Marker ohne oeffnenden");
        }

        $aus[] = b3_substitute($text, $scopes, $wo, $streng);
        $i++;
    }
}

/* ---------------------------------------------------------------------
   Zwischenspeicher der Browser umgehen
   --------------------------------------------------------------------- */

/**
 * ?v=… an oertliche Dateien haengen.
 *
 * Derselbe Grund wie in build-site.py: assets/img/hero-tall.webp heisst nach
 * jeder Neuberechnung wieder genauso, und der Browser zeigt die Fassung aus
 * seinem Zwischenspeicher weiter. Anders als dort wird hier NICHT der Inhalt
 * gehasht, sondern Aenderungszeit und Groesse genommen: der Inhalt-Hash
 * bedeutet, bei jedem Seitenaufruf ein paar Megabyte zu lesen. Zum Umgehen
 * des Zwischenspeichers reicht eine Kennung, die sich mit der Datei aendert.
 */
function b3_versionieren(string $html): string
{
    return (string) preg_replace_callback(
        '/(?:src|href|srcset)="(assets\/[^"?]+\.(?:webp|css|js|woff2|svg|png|ico))"/',
        function (array $m): string {
            static $merker = [];
            $rel = $m[1];
            if (!array_key_exists($rel, $merker)) {
                $datei = __DIR__ . '/../' . $rel;
                $merker[$rel] = is_file($datei)
                    ? substr(md5((string) filemtime($datei) . '-' . (string) filesize($datei)), 0, 8)
                    : null;
            }
            return $merker[$rel] === null
                ? $m[0]
                : str_replace($rel, $rel . '?v=' . $merker[$rel], $m[0]);
        },
        $html
    );
}

/* ---------------------------------------------------------------------
   Einstieg
   --------------------------------------------------------------------- */

/**
 * Die Vorlage mit den Daten der Redaktion rendern.
 *
 * @param bool $streng Im Pruefbetrieb (tools/check-render.php) wirft ein
 *                     fehlender Pfad. Im Netzbetrieb nie.
 */
function b3_render_template(string $vorlage, bool $streng = false): string
{
    $text = (string) file_get_contents($vorlage);
    $zeilen = [];
    foreach (explode("\n", $text) as $n => $zeile) {
        $zeilen[] = [$n + 1, $zeile];
    }

    $aus = [];
    b3_render_block($zeilen, 0, count($zeilen), [b3_data()], $aus, $streng);
    return b3_versionieren(implode("\n", $aus));
}
