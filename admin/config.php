<?php
/* =====================================================================
   B³ Retreats — Panel-Konfiguration und gemeinsame Helfer
   (Session, CSRF, Anmeldung, Sperre nach Fehlversuchen, Escaping).

   Portiert aus matern_website/admin/config.php. Angepasst an B³:
     * Session heisst B3_ADMIN (sonst teilen sich zwei Projekte auf demselben
       Host dieselbe Sitzung, wenn beide unter derselben Domain liegen),
     * Grenzwerte kommen aus config.php im Wurzelverzeichnis, damit sie ohne
       Code-Aenderung angepasst werden koennen,
     * Tabellen heissen wie in db/schema.sql (login_attempts mit `ip`,
       `username`, `attempted_at`).
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/../db.php';

/* --- Keine Fehlerdetails nach draussen ---------------------------------
   Eine PHP-Meldung nennt Pfade, Abfragen und manchmal Daten. Auf einer
   oeffentlichen Adresse ist das eine Landkarte fuer Angreifer. Zum Suchen
   eines Fehlers auf dem Server display_errors kurz auf '1' setzen. */
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
error_reporting(E_ALL);
set_exception_handler(function (Throwable $e): void {
    error_log('B3 admin: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    if (!headers_sent()) {
        http_response_code(500);
    }
    echo 'Es ist ein Fehler aufgetreten. Der Grund steht im Fehlerprotokoll des Servers.';
    exit;
});

/* --- Fatale Fehler sichtbar machen, ohne Details zu verraten -------------
   Der Handler oben faengt Ausnahmen. Einen FATALEN Fehler faengt er nicht:
   „Call to undefined function", ein fehlendes require, ein Syntaxfehler in
   einer eingebundenen Datei — die brechen ab, bevor irgendetwas ausgegeben
   wird, und der Browser zeigt eine leere 500. Genau daran ist bei der
   Einrichtung eine ganze Runde verloren gegangen: 500 ohne einen Satz dazu.

   Diese Funktion laeuft beim Beenden der Anfrage. Findet sie einen fatalen
   Fehler, schreibt sie die volle Zeile ins Protokoll und gibt nach draussen
   nur die ART und die Zeile — genug, um zu wissen, wo man sucht, ohne Pfade,
   Abfragen oder Daten preiszugeben. */
register_shutdown_function(function (): void {
    $f = error_get_last();
    if ($f === null || !in_array($f['type'], [E_ERROR, E_PARSE, E_CORE_ERROR,
            E_COMPILE_ERROR, E_USER_ERROR], true)) {
        return;
    }
    error_log('B3 admin FATAL: ' . $f['message'] . ' @ ' . $f['file'] . ':' . $f['line']);
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
    }
    // Nur Dateiname, nicht der ganze Pfad, und die erste Zeile der Meldung.
    $datei = basename((string) $f['file']);
    $satz = strtok((string) $f['message'], "\n");
    echo "Das Panel konnte nicht starten.\n\n"
        . $satz . "\n"
        . "in " . $datei . ", Zeile " . $f['line'] . "\n\n"
        . "Die vollstaendige Meldung steht in admin/error_log.\n";
});

/** Panel-Einstellung aus config.php, mit Rueckfallwert. */
function panel_conf(string $key, int $standard): int
{
    $conf = db_config();
    $wert = $conf['admin'][$key] ?? null;
    return is_numeric($wert) ? (int) $wert : $standard;
}

function client_ip(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '');
}

/**
 * HTTP-Schutzkopfzeilen. Auf jeder Panel-Seite aufgerufen.
 *
 * Die CSP ist streng und laesst kein Inline-JavaScript zu. Das ist kein
 * Selbstzweck: das Panel schreibt Inhalte in die Seite, und genau dort waere
 * ein durchgerutschtes <script> am gefaehrlichsten. Bestaetigungen laufen
 * deshalb ueber assets/panel.js und data-Attribute, nicht ueber onclick.
 */
function security_headers(): void
{
    if (headers_sent()) {
        return;
    }
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()');
    header('X-Robots-Tag: noindex, nofollow');
    header("Content-Security-Policy: default-src 'self'; img-src 'self' data: blob:; "
        . "style-src 'self'; script-src 'self'; form-action 'self'; "
        . "frame-ancestors 'none'; base-uri 'self'; object-src 'none'");
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

/* ---------------- Sperre nach zu vielen Fehlversuchen ----------------- */

function login_window_seconds(): int
{
    return panel_conf('attempt_window', 15) * 60;
}

function login_recent_fails(string $ip): int
{
    try {
        $st = db()->prepare('SELECT COUNT(*) FROM login_attempts '
            . 'WHERE ip = ? AND attempted_at >= ?');
        $st->execute([$ip, date('Y-m-d H:i:s', time() - login_window_seconds())]);
        return (int) $st->fetchColumn();
    } catch (Throwable $e) {
        return 0;   // ohne Tabelle keine Sperre — die Anmeldung selbst prueft weiter
    }
}

function login_throttled(string $ip): bool
{
    return login_recent_fails($ip) >= panel_conf('max_attempts', 8);
}

function record_login_fail(string $ip, string $username): void
{
    try {
        db()->prepare('INSERT INTO login_attempts (ip, username, attempted_at) '
            . 'VALUES (?, ?, ?)')
            ->execute([$ip, mb_substr($username, 0, 60), date('Y-m-d H:i:s')]);
        // Hygiene und DSGVO: aeltere Versuche als das Zeitfenster werden nicht
        // gebraucht, und die Tabelle soll nicht endlos wachsen.
        db()->prepare('DELETE FROM login_attempts WHERE attempted_at < ?')
            ->execute([date('Y-m-d H:i:s', time() - login_window_seconds())]);
    } catch (Throwable $e) {
        /* nicht kritisch */
    }
}

function clear_login_fails(string $ip): void
{
    try {
        db()->prepare('DELETE FROM login_attempts WHERE ip = ?')->execute([$ip]);
    } catch (Throwable $e) {
        /* nicht kritisch */
    }
}

/* ------------------------------ Session ------------------------------- */

function start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'secure'   => $secure,
        'samesite' => 'Lax',
    ]);
    session_name('B3_ADMIN');
    session_start();

    // Abmeldung nach Untaetigkeit: ein offenes Panel auf einem fremden Rechner
    // ist das wahrscheinlichere Risiko als ein geknacktes Passwort.
    $jetzt = time();
    $leerlauf = panel_conf('session_idle', 120) * 60;
    if (isset($_SESSION['admin'], $_SESSION['last_activity'])
        && ($jetzt - (int) $_SESSION['last_activity'] > $leerlauf)) {
        $_SESSION = [];
        session_destroy();
        session_start();
    }
    $_SESSION['last_activity'] = $jetzt;
}

/* -------------------------------- CSRF -------------------------------- */

function csrf_token(): string
{
    start_session();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . esc(csrf_token()) . '">';
}

function csrf_check(): void
{
    start_session();
    $ok = isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], (string) $_POST['csrf']);
    if (!$ok) {
        http_response_code(400);
        exit('Ungueltiges Formular-Token. Bitte die Seite neu laden.');
    }
}

/* ----------------------------- Anmeldung ------------------------------ */

function current_user(): ?array
{
    start_session();
    return $_SESSION['admin'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

function login_user(string $username, string $password): bool
{
    $st = db()->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
    $st->execute([$username]);
    $u = $st->fetch();
    // password_verify auch ohne Treffer aufrufen waere sauberer gegen
    // Zeitmessung; hier bremst schon die Fehlversuchssperre, und ein
    // Benutzername ist kein Geheimnis.
    if (!$u || !password_verify($password, (string) $u['password_hash'])) {
        return false;
    }
    start_session();
    session_regenerate_id(true);   // gegen Session-Fixation
    $_SESSION['admin'] = ['id' => (int) $u['id'], 'username' => (string) $u['username']];
    clear_login_fails(client_ip());
    try {
        db()->prepare('UPDATE admin_users SET last_login = CURRENT_TIMESTAMP WHERE id = ?')
            ->execute([$u['id']]);
    } catch (Throwable $e) {
        /* nicht kritisch */
    }
    return true;
}

function logout_user(): void
{
    start_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'],
            $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/* ------------------------------ Escaping ------------------------------ */

function esc($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function post(string $k, string $standard = ''): string
{
    return isset($_POST[$k]) ? trim((string) $_POST[$k]) : $standard;
}

function get(string $k, string $standard = ''): string
{
    return isset($_GET[$k]) ? trim((string) $_GET[$k]) : $standard;
}
