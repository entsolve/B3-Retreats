<?php
/* B³ Retreats — Anmeldung am Panel. */
declare(strict_types=1);

require_once __DIR__ . '/config.php';

security_headers();
start_session();

// Wer schon angemeldet ist, hat hier nichts zu suchen.
if (current_user()) {
    header('Location: index.php');
    exit;
}

$fehler = null;
$ip = client_ip();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    if (login_throttled($ip)) {
        // Absichtlich dieselbe Auskunft fuer jeden Grund: sonst verraet die
        // Meldung, ob ein Benutzername existiert.
        $fehler = 'Zu viele Fehlversuche. Bitte '
            . panel_conf('lockout', 15) . ' Minuten warten.';
    } else {
        $benutzer = post('username');
        $passwort = (string) ($_POST['password'] ?? '');
        if ($benutzer === '' || $passwort === '') {
            $fehler = 'Bitte Benutzername und Passwort eingeben.';
        } elseif (login_user($benutzer, $passwort)) {
            header('Location: index.php');
            exit;
        } else {
            record_login_fail($ip, $benutzer);
            $rest = max(0, panel_conf('max_attempts', 8) - login_recent_fails($ip));
            $fehler = 'Benutzername oder Passwort stimmt nicht.'
                . ($rest > 0 && $rest <= 3 ? " Noch $rest Versuche." : '');
        }
    }
}

$pdo = db();
$hinweis = null;
if ($pdo === null) {
    $hinweis = db_error();
} elseif (!db_has_table($pdo, 'admin_users')) {
    $hinweis = 'Die Datenbank ist noch leer. Einrichtung: admin/setup.php aufrufen.';
} elseif ((int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn() === 0) {
    $hinweis = 'Es gibt noch kein Konto. Einrichtung: admin/setup.php aufrufen.';
}
?>
<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Anmelden — B³ Redaktion</title>
<link rel="stylesheet" href="assets/panel.css">
</head>
<body class="anmeldung">
<main class="karte">
  <p class="marke">B³ <span>Redaktion</span></p>
  <h1>Anmelden</h1>

  <?php if ($hinweis !== null): ?>
    <p class="meldung meldung--warnung"><?= esc($hinweis) ?></p>
  <?php endif; ?>
  <?php if ($fehler !== null): ?>
    <p class="meldung meldung--fehler"><?= esc($fehler) ?></p>
  <?php endif; ?>

  <form method="post" autocomplete="on">
    <?= csrf_field() ?>
    <label for="username">Benutzername</label>
    <input id="username" name="username" required autocapitalize="none"
           spellcheck="false" autocomplete="username"
           value="<?= esc(post('username')) ?>">

    <label for="password">Passwort</label>
    <input id="password" name="password" type="password" required
           autocomplete="current-password">

    <button type="submit">Anmelden</button>
  </form>
</main>
</body>
</html>
