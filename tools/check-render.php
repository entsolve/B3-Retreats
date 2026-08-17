<?php
/* =====================================================================
   B³ Retreats — beweist, dass beide Leser der Vorlage dasselbe liefern.

   Es gibt zwei: tools/build-site.py baut oertlich die statische index.html,
   partials/render.php baut auf dem Server dieselbe Seite aus der Datenbank.
   Solange die Redaktion nichts geaendert hat, MUESSEN beide zeichengleich
   sein — sonst hat der Server eine andere Seite als die abgenommene.

   Verglichen wird gegen die bestehende index.html. Die Kennung hinter ?v=
   wird auf beiden Seiten entfernt: Python hasht den Inhalt der Datei, PHP
   Aenderungszeit und Groesse. Verschiedene Kennungen, gleicher Zweck —
   siehe b3_versionieren().

   Aufruf:
       php tools/check-render.php
   ===================================================================== */
declare(strict_types=1);

$wurzel = dirname(__DIR__);
require_once $wurzel . '/partials/render.php';

$vorlage = $wurzel . '/tools/templates/index.html';
$bestand = $wurzel . '/index.html';

foreach ([$vorlage, $bestand] as $datei) {
    if (!is_file($datei)) {
        fwrite(STDERR, "fehlt: $datei\n");
        exit(2);
    }
}

try {
    $erzeugt = b3_render_template($vorlage, true);
} catch (B3TemplateError $e) {
    fwrite(STDERR, 'Vorlage: ' . $e->getMessage() . "\n");
    exit(1);
}

$ohne_kennung = fn(string $s): string => (string) preg_replace('/\?v=[0-9a-f]{8}/', '', $s);

$a = explode("\n", $ohne_kennung($erzeugt));
$b = explode("\n", $ohne_kennung((string) file_get_contents($bestand)));

$max = max(count($a), count($b));
$abweichungen = 0;
for ($i = 0; $i < $max; $i++) {
    $links  = $a[$i] ?? '<Datei zu Ende>';
    $rechts = $b[$i] ?? '<Datei zu Ende>';
    if ($links !== $rechts) {
        if ($abweichungen === 0) {
            fwrite(STDERR, "Abweichung zwischen PHP-Leser und index.html:\n");
        }
        if ($abweichungen < 5) {
            fwrite(STDERR, sprintf(
                "  Zeile %d\n    PHP:      %s\n    Bestand:  %s\n",
                $i + 1,
                var_export($links, true),
                var_export($rechts, true)
            ));
        }
        $abweichungen++;
    }
}

if ($abweichungen > 0) {
    fwrite(STDERR, "\n$abweichungen abweichende Zeilen.\n");
    exit(1);
}

printf(
    "PHP-Leser und index.html sind zeichengleich (%d Zeilen). Der Server zeigt dieselbe Seite.\n",
    count($a)
);
exit(0);
