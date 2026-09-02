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

/* KEIN Zwischenspeichern im Browser.

   Hier stand `public, max-age=300`, und das war ein Fehler mit Ansage: die
   Kundin speichert im Panel, laedt die Seite neu — und sieht bis zu fuenf
   Minuten lang die alte Fassung. Aus ihrer Sicht ist die Aenderung einfach
   nicht angekommen. Genau so wurde es gemeldet.

   Eine Seite, die aus der Datenbank gebaut wird, darf nicht laenger gelten
   als bis zur naechsten Aenderung — und wann die kommt, weiss niemand
   vorher. Die Ersparnis waere ohnehin gering: die Seite wird in
   Sekundenbruchteilen gebaut, und die schweren Teile (Bilder, Schriften,
   Stile) tragen ihr ?v= und werden weiterhin lange zwischengespeichert. */
header('Cache-Control: no-cache, must-revalidate');

/* Warteliste: warteliste.php leitet nach dem Absenden hierher zurueck und
   haengt den Ausgang an die Adresse. Der Vorlagensprache fehlt ein „sonst",
   deshalb drei Schalter statt einem — genau einer davon ist gesetzt.

   Nach dem Dank wird das Formular ausgeblendet: es noch einmal anzubieten
   laedt dazu ein, sich zweimal einzutragen. Nach einem Fehler bleibt es
   stehen, sonst muesste die Person alles neu suchen. */
$stand = (string) ($_GET['warteliste'] ?? '');
$fertig = in_array($stand, ['ok', 'pruefen'], true);   // Formular hat ausgedient
b3_runtime_set('warteliste.ok',           $stand === 'ok'             ? '1' : '');
b3_runtime_set('warteliste.ist_pruefen',  $stand === 'pruefen'        ? '1' : '');
b3_runtime_set('warteliste.nicht_ok',     $stand === 'fehler'         ? '1' : '');
b3_runtime_set('warteliste.ist_panne',    $stand === 'panne'          ? '1' : '');
b3_runtime_set('warteliste.ist_ungueltig', $stand === 'link-ungueltig' ? '1' : '');
b3_runtime_set('warteliste.formular',     $fertig                     ? ''  : '1');

/* Freie Plaetze -> was der Tarif anzeigt. Die Rechnung selbst steht in
   partials/termin.php, damit tools/check-pfade.php sie mitbenutzen kann. */
foreach (plaetze_ableiten(b3_data()) as $pfad => $wert) {
    b3_runtime_set($pfad, $wert);
}

/* Wohin die Buchungsknoepfe zeigen, wenn es nichts zu buchen gibt.
   Leer heisst: alles beim Alten, main.js nimmt den Stripe-Link. */
/* Wohin die Buchungsknoepfe zeigen und wie sie heissen, wenn es nichts zu
   buchen gibt. Die Regel steht in partials/termin.php. */
foreach (buchung_ersatz_ableiten(b3_data()) as $pfad => $wert) {
    b3_runtime_set($pfad, $wert);
}

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
