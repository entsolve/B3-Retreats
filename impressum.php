<?php
/* =====================================================================
   B³ Retreats — Impressum.

   Gebaut wie die Startseite: Vorlage + Panel-Inhalt, bei jedem Aufruf.
   Der Rumpf steht unter recht.impressum.body im Abschnitt
   „22 Rechtstexte" und ist damit fuer die Kundin erreichbar — vorher lag
   er als statisches HTML im Repo und war nur ueber einen Build zu aendern.

   Faellt irgendetwas aus, bleibt die Seite stehen: impressum.html ist
   der zuletzt gebaute Stand und wird ausgeliefert. Bei Rechtstexten ist
   das keine Kuer — eine 500 auf dem Impressum ist ein Abmahngrund.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/partials/render.php';

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

try {
    $html = b3_render_template(__DIR__ . '/tools/templates/impressum.html');
    if (trim($html) === '') {
        throw new RuntimeException('Die Vorlage hat nichts geliefert.');
    }
    echo $html;
} catch (Throwable $e) {
    error_log('B3 impressum: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());

    $notfall = __DIR__ . '/impressum.html';
    if (is_file($notfall)) {
        readfile($notfall);
        exit;
    }

    http_response_code(500);
    echo 'Die Seite konnte nicht gebaut werden. Der Grund steht im Fehlerprotokoll des Servers.';
}
