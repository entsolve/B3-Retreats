<?php
/* =====================================================================
   B³ Retreats — die oeffentliche Startseite.

   Sie wird bei jedem Aufruf aus drei Teilen gebaut:

       tools/templates/index.html   die Vorlage (Markup)
       content/site.json            der abgenommene Bestand
       Tabelle `content`            was die Redaktion geaendert hat

   Damit wirkt eine Aenderung im Panel sofort auf der Seite — vorher lag
   hier eine statische index.html, und die Redaktion schrieb ins Leere.

   WENN ETWAS SCHIEFGEHT, BLEIBT DIE SEITE STEHEN. Drei Stufen:

       1. Datenbank weg      -> Bestand aus site.json (das ist der Zustand,
                                der abgenommen wurde)
       2. Vorlage kaputt     -> die alte statische index.html
       3. auch die fehlt     -> HTTP 500 mit einem Satz, der Grund steht im
                                Fehlerprotokoll

   index.html bleibt deshalb bewusst liegen: sie ist das Netz unter allem
   und wird von tools/build-site.py weiter mitgepflegt.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/partials/render.php';

/* Die Vorlage liefert vollstaendiges HTML inklusive <!DOCTYPE>. */
header('Content-Type: text/html; charset=utf-8');

/* Die Seite ist oeffentlich und aendert sich nur, wenn die Redaktion
   speichert. Eine knappe Frist entlastet den Server, ohne dass die Kundin
   nach dem Speichern lange auf ihre Aenderung wartet. */
header('Cache-Control: public, max-age=300');

try {
    $html = b3_render_template(__DIR__ . '/tools/templates/index.html');
    if (trim($html) === '') {
        throw new RuntimeException('Die Vorlage hat nichts geliefert.');
    }
    echo $html;
} catch (Throwable $e) {
    error_log('B3 index: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());

    $notfall = __DIR__ . '/index.html';
    if (is_file($notfall)) {
        readfile($notfall);
        exit;
    }

    http_response_code(500);
    echo 'Die Seite konnte nicht gebaut werden. Der Grund steht im Fehlerprotokoll des Servers.';
}
