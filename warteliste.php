<?php
/* =====================================================================
   B³ Retreats — Eintrag in die Warteliste.

   Das einzige Formular der Seite. Es nimmt entgegen, was Christina
   vorgegeben hat: Vorname, Name, E-Mail, Telefon (freiwillig) und woran
   Interesse besteht — Shared House, Friends Special oder beides.

   ZWEI SCHRITTE (Double Opt-in). Dieses Skript nimmt die Angaben nur
   entgegen und schickt eine Bestaetigungsmail an die eingetragene
   Adresse. Erst der Klick darin — warteliste-bestaetigen.php — traegt
   endgueltig ein und meldet es der Veranstalterin.

   Der Umweg hat einen handfesten Grund: die spaetere Nachricht „der
   Termin steht fest" ist Werbung im Sinne des § 7 UWG. Ohne bestaetigte
   Adresse laesst sich nicht belegen, dass die Einwilligung von der
   Inhaberin des Postfachs kam und nicht von jemandem, der eine fremde
   Adresse eingetippt hat. Nebenbei schuetzt es genau diese Fremden
   davor, ungefragt Post zu bekommen.

   OHNE DATENBANK GEHT ES NICHT MEHR: der Bestaetigungslink braucht einen
   gespeicherten Token. Faellt die Datenbank aus, meldet das Formular
   ehrlich einen Fehler, statt eine Bestaetigung zu versprechen, die nie
   ankommen kann.

   NACH DEM ABSENDEN WIRD UMGELEITET (Post/Redirect/Get). Ohne das zeigt
   ein Neuladen der Seite die Nachfrage „Formular erneut senden?" und
   traegt beim Bestaetigen ein zweites Mal ein.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/partials/render.php';
require_once __DIR__ . '/partials/mail.php';
require_once __DIR__ . '/partials/mail-vorlagen.php';

/** Zurueck zur Seite, mit einem Vermerk fuer die Anzeige. */
function wl_zurueck(string $stand): void
{
    header('Location: /?warteliste=' . rawurlencode($stand) . '#warteliste', true, 303);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    wl_zurueck('');
}

/* Eigener Zugriff auf config.php statt db_config(): das liefert null, sobald
   die Datenbank-Zugangsdaten fehlen — dann waere auch die E-Mail-Adresse weg,
   und der Eintrag ginge doppelt verloren. `require` und nicht `require_once`,
   damit die Datei ihren Rueckgabewert liefert und nicht bloss `true`. */
function wl_config(): array
{
    static $cfg = null;
    if ($cfg !== null) {
        return $cfg;
    }
    $datei = __DIR__ . '/config.php';
    $roh = is_file($datei) ? require $datei : [];
    $cfg = is_array($roh) ? (array) ($roh['warteliste'] ?? []) : [];
    return $cfg;
}

$cfg = wl_config();

/* --- Bot-Falle -------------------------------------------------------
   Ein Feld, das im Blatt versteckt ist und keine Beschriftung traegt.
   Menschen sehen es nicht und fuellen es nie; einfache Formular-Bots
   fuellen stur alles aus. Wird es ausgefuellt, tun wir so, als waere
   alles gut — eine Fehlermeldung waere fuer den Bot eine Rueckmeldung,
   an der er lernen kann. */
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    wl_zurueck('ok');
}

/* --- Eingaben pruefen ------------------------------------------------ */
function wl_feld(string $name, int $max): string
{
    $wert = (string) ($_POST[$name] ?? '');
    // Zeilenumbrueche raus: in einer E-Mail-Kopfzeile waeren sie eine
    // Einladung, eigene Empfaenger anzuhaengen (Header Injection).
    $wert = str_replace(["\r", "\n", "\0"], ' ', $wert);
    return mb_substr(trim($wert), 0, $max);
}

$vorname  = wl_feld('vorname', 120);
$nachname = wl_feld('nachname', 120);
$email    = mb_strtolower(wl_feld('email', 190));
$telefon  = wl_feld('telefon', 60);
$shared   = isset($_POST['shared'])  ? 1 : 0;
$friends  = isset($_POST['friends']) ? 1 : 0;
$zustimmung = isset($_POST['einwilligung']);

/* WORTLAUT VOM SERVER, nicht aus dem Formular. Er ist der Nachweis nach
   Art. 5 Abs. 2 DSGVO; kaeme er mit der Anfrage herein, koennte jede
   Absenderin hineinschreiben, wozu sie angeblich zugestimmt hat. */
$zustimmungstext = trim(strip_tags(c('warteliste.einwilligung')));

if ($vorname === '' || $nachname === '' || !$zustimmung
    || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    wl_zurueck('fehler');
}

/* --- Bremse gegen massenhaftes Eintragen ----------------------------- */
$pdo = db();
$ip  = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
$hash = $ip === '' ? null
      : hash('sha256', $ip . '|' . (string) ($cfg['pfeffer'] ?? 'ohne-pfeffer'));

if ($pdo && $hash !== null) {
    try {
        $q = $pdo->prepare('SELECT COUNT(*) FROM warteliste '
            . 'WHERE ip_hash = ? AND created_at > (NOW() - INTERVAL 1 HOUR)');
        $q->execute([$hash]);
        if ((int) $q->fetchColumn() >= (int) ($cfg['max_pro_stunde'] ?? 5)) {
            // Wie bei der Bot-Falle: nach aussen unauffaellig.
            wl_zurueck('ok');
        }
    } catch (Throwable $e) {
        error_log('B3 warteliste (Bremse): ' . $e->getMessage());
    }
}

/* --- Speichern, noch unbestaetigt ------------------------------------ */
if (!$pdo) {
    error_log('B3 warteliste: keine Datenbank — Eintrag von ' . $email . ' abgewiesen.');
    wl_zurueck('fehler');
}

/* SCHON BESTAETIGT? Dann hier abbiegen, VOR dem Schreiben. Sonst legt das
   INSERT unten einen frischen Token auf eine Zeile, die ihn nicht mehr
   braucht — ein gueltiger Schluessel, der in keiner E-Mail steht und den
   niemand je einloest. Ausserdem ist eine zweite Bestaetigungsmail an
   jemanden, der laengst auf der Liste steht, schlicht verwirrend. */
try {
    $q = $pdo->prepare('SELECT bestaetigt_at FROM warteliste WHERE email = ?');
    $q->execute([$email]);
    if ($q->fetchColumn()) {
        wl_zurueck('ok');
    }
} catch (Throwable $e) {
    error_log('B3 warteliste (Stand pruefen): ' . $e->getMessage());
}

$token = bin2hex(random_bytes(32));
try {
    $sql = 'INSERT INTO warteliste '
         . '(vorname, nachname, email, telefon, will_shared, will_friends, '
         . ' einwilligung_text, einwilligung_at, ip_hash, token, token_at) '
         . 'VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, NOW()) '
         // Zweiter Anlauf derselben Person: Angaben auffrischen und einen
         // NEUEN Token setzen. Der alte Link wird dadurch ungueltig — wer
         // das Formular zweimal abschickt, soll nicht zwei gueltige Links
         // im Postfach haben. `bestaetigt_at` bleibt unangetastet: wer
         // schon bestaetigt hat, muss das nicht erneut tun.
         . 'ON DUPLICATE KEY UPDATE vorname = VALUES(vorname), '
         . ' nachname = VALUES(nachname), telefon = VALUES(telefon), '
         . ' will_shared = VALUES(will_shared), will_friends = VALUES(will_friends), '
         . ' einwilligung_text = VALUES(einwilligung_text), '
         . ' einwilligung_at = VALUES(einwilligung_at), ip_hash = VALUES(ip_hash), '
         . ' token = VALUES(token), token_at = VALUES(token_at)';
    $pdo->prepare($sql)->execute([$vorname, $nachname, $email, $telefon ?: null,
        $shared, $friends, $zustimmungstext ?: null, $hash, $token]);
} catch (Throwable $e) {
    error_log('B3 warteliste (speichern): ' . $e->getMessage());
    wl_zurueck('fehler');
}

/* --- Bitte bestaetigen ----------------------------------------------- */
$basis = rtrim((string) (site_base_url()), '/');
$link  = $basis . '/warteliste-bestaetigen.php?token=' . $token;

[$html, $text] = wl_mail_bestaetigung($vorname, $link);
$fehler = null;
$verschickt = mail_send($email, 'Bitte bestätige deinen Eintrag', $html, $text, null, $fehler);

if (!$verschickt) {
    // Ohne diese Mail kommt die Person nie auf die Liste. Das zu
    // verschweigen und „hat geklappt" anzuzeigen waere die schlechteste
    // aller Varianten: sie wartet auf eine Nachricht, die es nicht gibt.
    error_log('B3 warteliste (Bestaetigungsmail an ' . $email . '): ' . (string) $fehler);
    wl_zurueck('fehler');
}

wl_zurueck('pruefen');
