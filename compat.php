<?php
/* =====================================================================
   B³ Retreats — Vertraeglichkeit mit aelterem PHP.

   DER GRUND, IN EINEM SATZ: das Hosting laeuft auf PHP 7.4, Teile dieses
   Codes benutzen Funktionen aus PHP 8.0 — und das Ergebnis ist kein
   Hinweis, sondern ein blanker HTTP 500.

   Genau das ist auf b3-retreats.de passiert, und im Schwesterprojekt
   (matern_website) vorher schon einmal: „Call to undefined function
   str_starts_with()". Ein fataler Fehler bricht die Anfrage ab, bevor
   irgendetwas ausgegeben wird — im Browser steht nur die 500, der wirkliche
   Satz steht im Fehlerprotokoll des Servers.

   Diese Datei wird von db.php eingebunden. db.php haengt an JEDEM Eingang
   (oeffentliche Seiten, Panel, Einrichtung), damit die Protesen ueberall
   stehen und man sie nicht an einer Stelle vergessen kann.

   Fallen die Funktionen eines Tages weg, weil das Hosting auf PHP 8 geht,
   passiert nichts: function_exists sorgt dafuer, dass die eingebauten
   Fassungen Vorrang haben.
   ===================================================================== */

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle): bool
    {
        $needle = (string) $needle;
        return $needle === ''
            || strncmp((string) $haystack, $needle, strlen($needle)) === 0;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle): bool
    {
        $needle = (string) $needle;
        if ($needle === '') {
            return true;
        }
        return substr((string) $haystack, -strlen($needle)) === $needle;
    }
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle): bool
    {
        $needle = (string) $needle;
        return $needle === '' || strpos((string) $haystack, $needle) !== false;
    }
}
