<?php
/* =====================================================================
   B³ Retreats — Versand von E-Mail.

   ZWEI WEGE, IN DIESER REIHENFOLGE:

     1. SMTP, wenn im Panel eingerichtet (Einstellungen -> E-Mail). Das ist
        der Weg, der ankommt: die Nachricht geht ueber den Mailserver der
        eigenen Domain, ist damit durch SPF/DKIM gedeckt und landet nicht
        im Spam.
     2. mail() als Rueckfall. Funktioniert auf vielen Hostern, aber der
        Absender ist dann der Webserver — bei fremden Postfaechern oft
        genau der Grund, warum nichts ankommt.

   WARUM EIN EIGENER SMTP-CLIENT: das Projekt hat keinen Composer und
   keine Bibliotheken. Fuer das, was hier gebraucht wird — verbinden,
   STARTTLS, anmelden, eine Nachricht abliefern — reichen rund hundert
   Zeilen. Alles darueber (Anhaenge, Warteschlangen, DKIM-Signatur) waere
   Aufwand ohne Zweck; DKIM macht der Mailserver der Domain selbst.

   Jede Nachricht geht als text/plain UND text/html hinaus. Wer HTML
   abgeschaltet hat oder mit einem Vorlesegeraet liest, bekommt eine
   ordentliche Textfassung statt einer leeren Seite.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/../db.php';

/* ---------------------------------------------------------------------
   Einstellungen
   --------------------------------------------------------------------- */

/** Alle E-Mail-Einstellungen aus der Tabelle `settings`. */
function mail_settings(): array
{
    $werte = &mail_settings_speicher();
    if ($werte !== null) {
        return $werte;
    }
    return mail_settings_neu_lesen();
}

/** Gemeinsamer Zwischenspeicher, damit er auch geleert werden kann. */
function &mail_settings_speicher()
{
    static $werte = null;
    return $werte;
}

/**
 * Einstellungen neu aus der Datenbank holen.
 *
 * Gebraucht im Panel: dort wird gespeichert und unmittelbar danach eine
 * Probe verschickt. Mit dem Zwischenspeicher wuerde die Probe noch die
 * ALTEN Zugangsdaten benutzen — und ein Tippfehler bliebe unentdeckt,
 * bis sich jemand in die Warteliste eintraegt.
 */
function mail_settings_neu_lesen(): array
{
    $ziel = &mail_settings_speicher();
    $werte = [];
    $pdo = db();
    if ($pdo) {
        try {
            $q = $pdo->query("SELECT k, v FROM settings WHERE k LIKE 'smtp.%'");
            foreach ($q->fetchAll(PDO::FETCH_KEY_PAIR) ?: [] as $k => $v) {
                $werte[substr($k, 5)] = (string) $v;
            }
        } catch (Throwable $e) {
            error_log('B3 mail: Einstellungen nicht lesbar — ' . $e->getMessage());
        }
    }
    $ziel = $werte;
    return $werte;
}

/**
 * Schluessel zum Ver- und Entschluesseln des SMTP-Passworts.
 *
 * Das Passwort MUSS im Klartext benutzbar bleiben — der Mailserver will es
 * sehen, ein Hash hilft hier nichts. Es einfach so in die Datenbank zu
 * legen waere trotzdem falsch: ein Datenbank-Auszug, wie er beim Umzug
 * oder in einem Backup entsteht, gaebe damit ein funktionierendes
 * Postfach her. Der Schluessel liegt deshalb in config.php, die nicht im
 * Git steht und nicht mitgesichert wird — Datenbank UND Datei muessen
 * zusammenkommen, damit das Passwort lesbar wird.
 */
function mail_key(): string
{
    static $key = null;
    if ($key !== null) {
        return $key;
    }

    $datei = __DIR__ . '/../config.php';
    $roh = is_file($datei) ? require $datei : [];
    if (!is_array($roh)) {
        return $key = '';
    }

    // Erste Wahl: ein ausdruecklich gesetzter Schluessel.
    $eigen = trim((string) ($roh['mail']['schluessel'] ?? ''));
    if ($eigen !== '' && strpos($eigen, 'BITTE-EINMALIG') === false) {
        return $key = $eigen;
    }

    /* SONST AUS DEN DATENBANK-ZUGANGSDATEN ABGELEITET.
       
       Vorher stand hier: „In config.php fehlt der Schluessel" — und damit
       war die SMTP-Einrichtung im Panel eine Sackgasse. Wer sie oeffnet,
       hat gerade KEINEN Dateizugriff; sonst haette er nicht das Panel
       aufgemacht. Eine Einstellungsseite, die zum FTP-Programm schickt,
       ist keine Einstellungsseite.

       Die Ableitung schuetzt genau gegen das, worum es geht: ein
       Datenbank-Auszug — aus einem Backup, bei einem Umzug, nach einem
       Leck — enthaelt die verschluesselten Bytes, aber nicht config.php
       und damit nicht die Zugangsdaten, aus denen der Schluessel faellt.
       Wer ohnehin die Datei lesen kann, kommt auch an alles andere.

       Preis der Bequemlichkeit, offen gesagt: aendert sich das
       Datenbank-Passwort, ist das gespeicherte SMTP-Passwort unlesbar und
       muss im Panel neu eingetragen werden. Genau das meldet die Seite
       dann auch. Wer das vermeiden will, traegt mail.schluessel ein. */
    $db = (array) ($roh['db'] ?? []);
    $stoff = (string) ($db['pass'] ?? '') . '|' . (string) ($db['name'] ?? '')
           . '|' . (string) ($db['user'] ?? '');
    if (trim($stoff, '|') === '') {
        return $key = '';
    }
    return $key = hash('sha256', 'b3-mail|' . $stoff);
}

function mail_encrypt(string $klartext): string
{
    $key = mail_key();
    if ($key === '' || $klartext === '') {
        return '';
    }
    $iv = random_bytes(16);
    $roh = openssl_encrypt($klartext, 'aes-256-cbc', hash('sha256', $key, true),
                           OPENSSL_RAW_DATA, $iv);
    return $roh === false ? '' : base64_encode($iv . $roh);
}

function mail_decrypt(string $gespeichert): string
{
    $key = mail_key();
    if ($key === '' || $gespeichert === '') {
        return '';
    }
    $roh = base64_decode($gespeichert, true);
    if ($roh === false || strlen($roh) <= 16) {
        return '';
    }
    $klar = openssl_decrypt(substr($roh, 16), 'aes-256-cbc', hash('sha256', $key, true),
                            OPENSSL_RAW_DATA, substr($roh, 0, 16));
    return $klar === false ? '' : $klar;
}

/* ---------------------------------------------------------------------
   Versand
   --------------------------------------------------------------------- */

/**
 * Verschickt eine Nachricht. Liefert true, wenn sie abgegeben werden konnte.
 *
 * $fehler nimmt den Grund auf — gebraucht fuer die Probe im Panel, damit
 * dort nicht bloss „hat nicht geklappt" steht.
 */
function mail_send(string $an, string $betreff, string $html, string $text,
                   ?string $antwortAn = null, ?string &$fehler = null): bool
{
    $s = mail_settings();
    $vonAdresse = trim($s['von'] ?? '') ?: 'noreply@b3-retreats.de';
    $vonName    = trim($s['von_name'] ?? '') ?: 'B³ Retreats';

    $grenze = 'b3-' . bin2hex(random_bytes(12));
    $koerper = "--$grenze\r\n"
             . "Content-Type: text/plain; charset=UTF-8\r\n"
             . "Content-Transfer-Encoding: 8bit\r\n\r\n"
             . $text . "\r\n\r\n"
             . "--$grenze\r\n"
             . "Content-Type: text/html; charset=UTF-8\r\n"
             . "Content-Transfer-Encoding: 8bit\r\n\r\n"
             . $html . "\r\n\r\n"
             . "--$grenze--\r\n";

    // Nicht-ASCII im Betreff und im Namen MUSS kodiert werden, sonst
    // steht im Posteingang „WartelisteN Bestaetigung" mit Fragezeichen.
    $kopf = [
        'From: ' . mail_kopfwort($vonName) . " <$vonAdresse>",
        'MIME-Version: 1.0',
        "Content-Type: multipart/alternative; boundary=\"$grenze\"",
        'Date: ' . date('r'),
    ];
    if ($antwortAn !== null && $antwortAn !== '') {
        $kopf[] = 'Reply-To: ' . $antwortAn;
    }
    $betreffKopf = 'Subject: ' . mail_kopfwort($betreff);

    if (trim($s['host'] ?? '') !== '') {
        return smtp_send($s, $vonAdresse, $an,
            array_merge($kopf, [$betreffKopf, 'To: ' . $an]), $koerper, $fehler);
    }

    $ok = @mail($an, $betreff, $koerper, implode("\r\n", $kopf), '-f' . $vonAdresse);
    if (!$ok) {
        $fehler = 'mail() hat abgelehnt. Auf diesem Server ist kein Versand '
                . 'eingerichtet — bitte SMTP hinterlegen.';
    }
    return $ok;
}

/** Kopfzeilen duerfen nur ASCII tragen; alles andere wird kodiert (RFC 2047). */
function mail_kopfwort(string $text): string
{
    $text = str_replace(["\r", "\n"], ' ', $text);
    return preg_match('/[\x80-\xFF]/', $text)
        ? '=?UTF-8?B?' . base64_encode($text) . '?='
        : $text;
}

/* ---------------------------------------------------------------------
   Der SMTP-Client
   --------------------------------------------------------------------- */

/** Eine Zeile sprechen und die Antwort holen; prueft den erwarteten Code. */
function smtp_zeile($fp, ?string $befehl, string $erwartet, ?string &$fehler): bool
{
    if ($befehl !== null) {
        fwrite($fp, $befehl . "\r\n");
    }
    $antwort = '';
    while (($zeile = fgets($fp, 515)) !== false) {
        $antwort .= $zeile;
        // Mehrzeilige Antworten tragen einen Bindestrich nach dem Code.
        if (strlen($zeile) < 4 || $zeile[3] !== '-') {
            break;
        }
    }
    if (strncmp($antwort, $erwartet, strlen($erwartet)) !== 0) {
        $gezeigt = $befehl !== null && stripos($befehl, 'AUTH') === 0 ? 'AUTH …' : ($befehl ?? '(Begruessung)');
        $fehler = 'SMTP bei „' . $gezeigt . '": ' . trim($antwort);
        return false;
    }
    return true;
}

function smtp_send(array $s, string $von, string $an, array $kopf, string $koerper,
                   ?string &$fehler): bool
{
    $host = trim($s['host'] ?? '');
    $port = (int) ($s['port'] ?? 587);
    $sicherheit = trim($s['sicherheit'] ?? 'starttls');
    $ziel = ($sicherheit === 'ssl' ? 'ssl://' : '') . $host . ':' . ($port ?: 587);

    $fp = @stream_socket_client($ziel, $nr, $meldung, 15);
    if (!$fp) {
        $fehler = "Keine Verbindung zu $host:$port — $meldung";
        return false;
    }
    stream_set_timeout($fp, 15);

    $eigen = $s['helo'] ?? '';
    $eigen = $eigen !== '' ? $eigen : (parse_url((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'), PHP_URL_HOST)
                                       ?: (string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));

    $ok = smtp_zeile($fp, null, '220', $fehler)
       && smtp_zeile($fp, 'EHLO ' . $eigen, '250', $fehler);

    if ($ok && $sicherheit === 'starttls') {
        $ok = smtp_zeile($fp, 'STARTTLS', '220', $fehler);
        if ($ok) {
            $ok = @stream_socket_enable_crypto($fp, true,
                    STREAM_CRYPTO_METHOD_TLS_CLIENT);
            if (!$ok) {
                $fehler = 'STARTTLS ist fehlgeschlagen — der Server bietet keine '
                        . 'brauchbare Verschluesselung an.';
            } else {
                // Nach dem Umschalten beginnt die Sitzung von vorn.
                $ok = smtp_zeile($fp, 'EHLO ' . $eigen, '250', $fehler);
            }
        }
    }

    $benutzer = trim($s['benutzer'] ?? '');
    if ($ok && $benutzer !== '') {
        $passwort = mail_decrypt($s['passwort'] ?? '');
        if ($passwort === '') {
            $fehler = 'Das SMTP-Passwort ist nicht lesbar. Fehlt in config.php der '
                    . 'Schluessel unter mail.schluessel, oder wurde er geaendert? '
                    . 'Dann bitte das Passwort im Panel neu eintragen.';
            $ok = false;
        } else {
            $ok = smtp_zeile($fp, 'AUTH LOGIN', '334', $fehler)
               && smtp_zeile($fp, base64_encode($benutzer), '334', $fehler)
               && smtp_zeile($fp, base64_encode($passwort), '235', $fehler);
        }
    }

    if ($ok) {
        // Punkte am Zeilenanfang verdoppeln: ein einzelner Punkt auf einer
        // Zeile beendet sonst die Nachricht mitten im Text (RFC 5321).
        $daten = implode("\r\n", $kopf) . "\r\n\r\n" . $koerper;
        $daten = preg_replace('/^\./m', '..', str_replace("\n", "\r\n",
                     str_replace("\r\n", "\n", $daten)));

        $ok = smtp_zeile($fp, "MAIL FROM:<$von>", '250', $fehler)
           && smtp_zeile($fp, "RCPT TO:<$an>", '25', $fehler)
           && smtp_zeile($fp, 'DATA', '354', $fehler)
           && smtp_zeile($fp, $daten . "\r\n.", '250', $fehler);
    }

    @fwrite($fp, "QUIT\r\n");
    @fclose($fp);
    return $ok;
}
