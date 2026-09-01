<?php
/* B³ Retreats — gemeinsamer Kopf aller angemeldeten Panel-Seiten. */
declare(strict_types=1);
if (!function_exists('current_user')) {
    require_once __DIR__ . '/config.php';
}
security_headers();
require_login();
$nutzer = current_user();
$titel = $titel ?? 'Redaktion';
?>
<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= esc($titel) ?> — B³ Redaktion</title>
<link rel="stylesheet" href="assets/panel.css">
</head>
<body>
  <header class="kopf">
    <p class="marke">B³ <span>Redaktion</span></p>
    <nav>
      <a href="index.php"<?= basename($_SERVER['SCRIPT_NAME']) === 'index.php' ? ' class="aktiv"' : '' ?>>Inhalte</a>
      <a href="email.php"<?= basename($_SERVER['SCRIPT_NAME']) === 'email.php' ? ' class="aktiv"' : '' ?>>E-Mail</a>
      <a href="../" target="_blank" rel="noopener">Seite ansehen</a>
    </nav>
    <p class="nutzer"><?= esc($nutzer['username'] ?? '') ?>
      <a href="logout.php">abmelden</a></p>
  </header>
  <main class="rahmen">
