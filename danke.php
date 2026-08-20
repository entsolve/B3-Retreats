<?php
/* =====================================================================
   B³ Retreats — die Danke-Seite nach der Buchung.

   Erreichbar unter /danke (die Umschreibung dorthin steht in .htaccess).
   Gebaut wie die Startseite: dieselbe Vorlagensprache, derselbe Leser,
   dieselben Inhalte aus dem Panel — nur eine andere Vorlage.

   Sie steht bewusst NICHT in der Navigation und traegt noindex: sie ist
   das, was nach der Zahlung kommt, und hat in der Suche nichts verloren.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/partials/render.php';

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

/* Auch wenn die Seite nicht verlinkt ist: doppelt haelt besser als eine
   Zeile im <head>, die eine Suchmaschine ueberliest. */
header('X-Robots-Tag: noindex, nofollow');

try {
    $html = b3_render_template(__DIR__ . '/tools/templates/danke.html');
    if (trim($html) === '') {
        throw new RuntimeException('Die Vorlage hat nichts geliefert.');
    }
    echo $html;
} catch (Throwable $e) {
    error_log('B3 danke: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    echo 'Die Seite konnte nicht gebaut werden. Der Grund steht im Fehlerprotokoll des Servers.';
}
