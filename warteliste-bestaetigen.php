<?php
/* =====================================================================
   B³ Retreats — der zweite Schritt der Warteliste.

   Hier landet, wer den Knopf in der Bestaetigungsmail drueckt. Erst
   dieser Aufruf traegt endgueltig ein und meldet den Eintrag an die
   Veranstalterin.

   ABSICHTLICH GENÜGSAM MIT AUSKÜNFTEN: ob ein Token unbekannt, abgelaufen
   oder schon benutzt ist, sieht die Besucherin nicht im Einzelnen. Sonst
   liesse sich damit ausprobieren, welche Adressen auf der Liste stehen.
   Sie sieht: „hat geklappt" oder „dieser Link gilt nicht mehr".

   TOKEN VERFAELLT NACH SIEBEN TAGEN. Ein Bestaetigungslink, der ewig
   gilt, ist ein Dauerschluessel in einem Postfach, das irgendwann
   weitergegeben oder uebernommen wird.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/render.php';
require_once __DIR__ . '/partials/mail.php';
require_once __DIR__ . '/partials/mail-vorlagen.php';

function wlb_zurueck(string $stand): void
{
    header('Location: /?warteliste=' . rawurlencode($stand) . '#warteliste', true, 303);
    exit;
}

$token = (string) ($_GET['token'] ?? '');
// Form pruefen, bevor die Datenbank gefragt wird: 64 Hex-Zeichen, sonst
// ist es kein Token von uns.
if (!preg_match('/^[0-9a-f]{64}$/', $token)) {
    wlb_zurueck('link-ungueltig');
}

$pdo = db();
if (!$pdo) {
    error_log('B3 warteliste-bestaetigen: keine Datenbank.');
    wlb_zurueck('link-ungueltig');
}

try {
    $q = $pdo->prepare('SELECT id, vorname, nachname, email, telefon, will_shared, '
        . ' will_friends, bestaetigt_at FROM warteliste '
        . 'WHERE token = ? AND token_at > (NOW() - INTERVAL 7 DAY)');
    $q->execute([$token]);
    $eintrag = $q->fetch(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    error_log('B3 warteliste-bestaetigen (lesen): ' . $e->getMessage());
    wlb_zurueck('link-ungueltig');
}

if (!$eintrag) {
    wlb_zurueck('link-ungueltig');
}

/* Kann eigentlich nicht mehr vorkommen — beim Bestaetigen wird der Token
   entwertet, ein zweiter Klick findet die Zeile also gar nicht erst. Die
   Abfrage bleibt trotzdem stehen: sollte je ein Weg entstehen, auf dem
   Token und Bestaetigung nebeneinander existieren, ist „schon dabei" die
   richtige Antwort und nicht „Link ungueltig". */
if ($eintrag['bestaetigt_at'] !== null) {
    wlb_zurueck('ok');
}

try {
    // Token wird beim Bestaetigen entwertet: er hat seinen Zweck erfuellt
    // und soll nicht als zweiter Schluessel liegenbleiben.
    $pdo->prepare('UPDATE warteliste SET bestaetigt_at = NOW(), token = NULL '
        . 'WHERE id = ?')->execute([$eintrag['id']]);
} catch (Throwable $e) {
    error_log('B3 warteliste-bestaetigen (schreiben): ' . $e->getMessage());
    wlb_zurueck('link-ungueltig');
}

$daten = [
    'vorname'  => (string) $eintrag['vorname'],
    'nachname' => (string) $eintrag['nachname'],
    'email'    => (string) $eintrag['email'],
    'telefon'  => (string) ($eintrag['telefon'] ?? ''),
    'shared'   => (bool) $eintrag['will_shared'],
    'friends'  => (bool) $eintrag['will_friends'],
    'zeit'     => date('d.m.Y H:i'),
];

/* --- Der Veranstalterin melden --------------------------------------- */
$cfg = wl_empfaenger();
[$html, $text] = wl_mail_meldung($daten);
$fehler = null;
$gemeldet = mail_send($cfg, 'Warteliste: ' . $daten['vorname'] . ' ' . $daten['nachname'],
                      $html, $text, $daten['email'], $fehler);
if (!$gemeldet) {
    // Kein Grund, die Person zu behelligen: ihr Eintrag steht sicher in
    // der Tabelle. Es fehlt nur die Benachrichtigung — das ist ein
    // Betreiberproblem und gehoert ins Protokoll, nicht auf den Schirm.
    error_log('B3 warteliste: Meldung ueber ' . $daten['email'] . ' ging nicht raus — '
        . (string) $fehler);
} else {
    try {
        $pdo->prepare('UPDATE warteliste SET mail_ok = 1 WHERE id = ?')
            ->execute([$eintrag['id']]);
    } catch (Throwable $e) {
        error_log('B3 warteliste-bestaetigen (mail_ok): ' . $e->getMessage());
    }
}

/* --- Und der Person danken ------------------------------------------- */
$dank = trim(strip_tags(str_replace(['<br>', '<br/>', '</p>'], "\n", c('warteliste.danke'))));
[$html, $text] = wl_mail_willkommen($daten['vorname'], $dank);
mail_send($daten['email'], 'Du stehst auf der Warteliste', $html, $text);

wlb_zurueck('ok');

/** Adresse der Veranstalterin: erst Panel-Einstellung, dann config.php. */
function wl_empfaenger(): string
{
    $s = mail_settings();
    $aus_panel = trim($s['empfaenger'] ?? '');
    if ($aus_panel !== '') {
        return $aus_panel;
    }
    $datei = __DIR__ . '/config.php';
    $roh = is_file($datei) ? require $datei : [];
    $aus_datei = is_array($roh)
        ? (string) ($roh['warteliste']['empfaenger'] ?? '') : '';
    return $aus_datei !== '' ? $aus_datei : 'hello@b3-retreats.de';
}
