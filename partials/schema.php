<?php
/* =====================================================================
   B³ Retreats — die Tabellen aus db/schema.sql anlegen.

   Stand frueher in admin/setup.php. Dort blieb es unerreichbar, sobald es
   ein Konto gab: setup.php verweigert dann den Dienst, und das ist auch
   richtig — sie darf kein zweites Konto anlegen koennen.

   Nur haengt daran mehr als das Konto. Kommt spaeter eine Tabelle dazu —
   `warteliste` etwa —, gibt es ohne diese Trennung keinen Weg mehr, sie
   ueber das Panel anzulegen: die Kundin braeuchte phpMyAdmin. Genau das
   ist passiert, und es sah aus wie „das Formular funktioniert nicht".

   Wiederholt aufzurufen ist ungefaehrlich: die Datei besteht aus
   CREATE TABLE IF NOT EXISTS und einem ALTER TABLE, das denselben Zustand
   noch einmal herstellt. Nichts davon loescht etwas.
   ===================================================================== */
declare(strict_types=1);

/**
 * Die Anweisungen aus db/schema.sql, einzeln.
 *
 * Kommentare werden VORHER entfernt, und das ist nicht Kosmetik: in schema.sql
 * steht in einem Kommentar der Satz „Gezaehlt wird je IP; ueberschreitet …".
 * Ein blosses explode(';') zerschneidet die Datei an diesem Semikolon mitten im
 * Kommentar und schickt zwei Bruchstuecke an die Datenbank.
 *
 * Mehr als das braucht es hier nicht: die Datei ist reines DDL, ohne DELIMITER,
 * ohne Trigger, ohne Prozeduren und ohne Semikolon in Anfuehrungszeichen —
 * nachgesehen, nicht angenommen.
 */
function schema_anweisungen(string $sql): array
{
    $sql = preg_replace('#/\*.*?\*/#s', '', $sql);        // Blockkommentare
    $sql = preg_replace('/^\s*--[^\n]*$/m', '', $sql);    // Zeilenkommentare
    $teile = array_map('trim', explode(';', (string) $sql));
    return array_values(array_filter($teile, static fn($t) => $t !== ''));
}

/**
 * Legt die Tabellen an. Gibt [Anzahl, Fehlertext|null] zurueck.
 *
 * Ohne Risiko wiederholbar: jede Anweisung in schema.sql ist ein
 * CREATE TABLE IF NOT EXISTS, ein zweiter Durchlauf aendert also nichts und
 * loescht nichts. Ausgefuehrt wird ausschliesslich die mitgelieferte Datei,
 * niemals etwas aus einem Formular.
 */
function schema_anwenden(PDO $pdo, string $datei): array
{
    if (!is_file($datei)) {
        return [0, 'db/schema.sql liegt nicht auf dem Server. Die Datei gehoert '
            . 'in den Ordner db/ neben index.html.'];
    }
    $sql = (string) file_get_contents($datei);
    $anweisungen = schema_anweisungen($sql);
    if (!$anweisungen) {
        return [0, 'db/schema.sql enthaelt keine ausfuehrbare Anweisung.'];
    }

    $n = 0;
    try {
        foreach ($anweisungen as $anweisung) {
            $pdo->exec($anweisung);
            $n++;
        }
    } catch (Throwable $e) {
        error_log('B3 setup.php Schema: ' . $e->getMessage());
        // Der haeufigste echte Grund, und er ist von aussen nicht zu erraten:
        // der Datenbankbenutzer darf keine Tabellen anlegen.
        return [$n, 'Die Tabellen konnten nicht angelegt werden (Anweisung '
            . ($n + 1) . ' von ' . count($anweisungen) . '). Meist fehlt dem '
            . 'Datenbankbenutzer das Recht CREATE: im cPanel unter '
            . '"MySQL-Datenbanken" dem Benutzer ALL PRIVILEGES auf diese '
            . 'Datenbank geben. Der genaue Fehler steht im Fehlerprotokoll.'];
    }
    return [$n, null];
}
