<?php
/* =====================================================================
   B³ Retreats — EINMALIGE Einrichtung des Panel-Kontos.

   Aufruf:  https://b3-retreats.de/admin/setup.php
   Danach:  DIESE DATEI VOM SERVER LOESCHEN.

   Was sie tut, in dieser Reihenfolge:
     1) prueft, ob config.php ausgefuellt ist und die Datenbank antwortet,
     2) prueft, ob db/schema.sql importiert wurde,
     3) legt GENAU EIN Konto an — existiert schon eines, verweigert sie.

   Punkt 3 ist der wichtige: die Seite liegt oeffentlich im Netz. Ohne diese
   Sperre koennte jeder, der die Adresse kennt, sich ein zweites Konto
   anlegen und im Panel sitzen. Sie schliesst sich also selbst ab, sobald
   das erste Konto steht — auch dann, wenn das Loeschen vergessen wird.
   Das Loeschen bleibt trotzdem Pflicht: die Datei verraet sonst weiter,
   welche Software hier laeuft.
   ===================================================================== */

declare(strict_types=1);

require_once __DIR__ . '/../db.php';

const MIN_LEN = 12;

session_start();
if (empty($_SESSION['setup_token'])) {
    $_SESSION['setup_token'] = bin2hex(random_bytes(32));
}

/** Text fuer die Ausgabe entschaerfen. */
function h(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

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

// --- Lage pruefen, bevor irgendein Formular gezeigt wird -------------------

$pdo = db();
$blocker = null;      // harter Grund, warum es gar nicht weitergeht
$fertig = false;      // Konto wurde in diesem Aufruf angelegt
$fehler = [];         // Eingabefehler, Formular bleibt stehen
$schema_fehlt = false;   // Tabellen sind noch nicht da
$schema_meldung = null;  // Ergebnis eines Anlege-Versuchs
$schema_datei = __DIR__ . '/../db/schema.sql';

// Tabellen auf Wunsch selbst anlegen, statt den Weg ueber phpMyAdmin zu
// verlangen. Das ist derselbe Inhalt aus derselben Datei — nur ohne Handarbeit.
if ($pdo !== null
    && ($_SERVER['REQUEST_METHOD'] === 'POST')
    && (($_POST['aktion'] ?? '') === 'schema')
    && hash_equals($_SESSION['setup_token'], (string) ($_POST['token'] ?? ''))
    && !db_has_table($pdo, 'admin_users')) {
    [$n, $fehlertext] = schema_anwenden($pdo, $schema_datei);
    $schema_meldung = $fehlertext ?? "$n Tabellen angelegt.";
}

if ($pdo === null) {
    $blocker = db_error();
} elseif (!db_has_table($pdo, 'admin_users')) {
    // Kein Blocker mehr, sondern ein Schritt: die Seite kann das selbst.
    $schema_fehlt = true;
} else {
    $vorhanden = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
    if ($vorhanden > 0) {
        $blocker = 'Es gibt bereits ein Konto. Diese Seite legt aus Sicherheits'
            . 'gruenden kein zweites an und ist damit erledigt: LOESCHEN SIE '
            . 'admin/setup.php JETZT VOM SERVER. Passwort vergessen? Dann in '
            . 'phpMyAdmin die Zeile in "admin_users" loeschen und diese Seite '
            . 'erneut aufrufen.';
    }
}

// --- Formular verarbeiten -------------------------------------------------

if ($blocker === null && !$schema_fehlt && $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['aktion'] ?? '') !== 'schema') {
    if (!hash_equals($_SESSION['setup_token'], (string) ($_POST['token'] ?? ''))) {
        $fehler[] = 'Das Formular ist abgelaufen. Bitte die Seite neu laden.';
    }

    $benutzer = trim((string) ($_POST['username'] ?? ''));
    $pass1 = (string) ($_POST['password'] ?? '');
    $pass2 = (string) ($_POST['password2'] ?? '');

    if (!preg_match('/^[A-Za-z0-9._-]{3,60}$/', $benutzer)) {
        $fehler[] = 'Benutzername: 3 bis 60 Zeichen, erlaubt sind Buchstaben, '
            . 'Ziffern, Punkt, Unterstrich und Bindestrich.';
    }
    // Laenge schlaegt Sonderzeichen: eine lange Passphrase ist sicherer und
    // leichter zu behalten als "P4ss!". Deshalb wird Laenge verlangt und keine
    // Zeichenklassen-Akrobatik.
    if (mb_strlen($pass1) < MIN_LEN) {
        $fehler[] = 'Passwort: mindestens ' . MIN_LEN . ' Zeichen. Am besten '
            . 'drei bis vier zufaellige Woerter hintereinander.';
    }
    if ($pass1 !== $pass2) {
        $fehler[] = 'Die beiden Passwoerter stimmen nicht ueberein.';
    }
    if (strcasecmp($pass1, $benutzer) === 0) {
        $fehler[] = 'Passwort und Benutzername duerfen nicht gleich sein.';
    }

    if (!$fehler) {
        try {
            // Nochmals unter Sperre pruefen: zwei gleichzeitige Aufrufe duerfen
            // nicht beide ein Konto anlegen.
            $pdo->beginTransaction();
            $anzahl = (int) $pdo->query('SELECT COUNT(*) FROM admin_users FOR UPDATE')
                ->fetchColumn();
            if ($anzahl > 0) {
                $pdo->rollBack();
                $blocker = 'In der Zwischenzeit wurde bereits ein Konto angelegt. '
                    . 'Diese Datei jetzt vom Server loeschen.';
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO admin_users (username, password_hash) VALUES (?, ?)'
                );
                $stmt->execute([$benutzer, password_hash($pass1, PASSWORD_DEFAULT)]);
                $pdo->commit();
                $fertig = true;
                // Der Token wird verbraucht: ein zweites Abschicken derselben
                // Seite laeuft ins Leere.
                unset($_SESSION['setup_token']);
            }
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('B3 setup.php: ' . $e->getMessage());
            $fehler[] = 'Das Konto konnte nicht gespeichert werden. Der Grund steht '
                . 'im Fehlerprotokoll des Servers.';
        }
    }
}

// Kein Zwischenspeichern und keine Suchmaschine auf dieser Seite.
header('Cache-Control: no-store');
header('X-Robots-Tag: noindex, nofollow');
?>
<!doctype html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>B³ Retreats — Einrichtung</title>
<style>
  :root { color-scheme: light; }
  body { margin: 0; padding: 6vh 5vw; background: #F4F0E9; color: #2A2722;
         font: 16px/1.6 system-ui, -apple-system, "Segoe UI", sans-serif; }
  main { max-width: 34rem; margin-inline: auto; }
  h1 { font-size: 1.5rem; font-weight: 600; margin: 0 0 .25rem; }
  .sub { color: #6E675C; margin: 0 0 2rem; }
  form { background: #FFFDFA; border: 1px solid #DED6C9; border-radius: 4px;
         padding: 1.75rem; }
  label { display: block; font-weight: 600; margin: 1.1rem 0 .35rem; }
  label:first-of-type { margin-top: 0; }
  input { width: 100%; box-sizing: border-box; padding: .7rem .8rem;
          border: 1px solid #C9BFAE; border-radius: 3px; background: #fff;
          font: inherit; }
  input:focus { outline: 2px solid #7A8C6B; outline-offset: 1px; }
  .hint { color: #6E675C; font-size: .875rem; margin-top: .3rem; }
  button { margin-top: 1.75rem; width: 100%; padding: .85rem;
           background: #454B40; color: #F7F3EC; border: 0; border-radius: 3px;
           font: inherit; font-weight: 600; letter-spacing: .04em; cursor: pointer; }
  button:hover { background: #363B31; }
  .box { border-radius: 4px; padding: 1.1rem 1.25rem; margin-bottom: 1.5rem; }
  .stop { background: #FBEEE9; border: 1px solid #D8A08A; }
  .ok   { background: #EDF2E8; border: 1px solid #9DB08C; }
  .warn { background: #FCF5E2; border: 1px solid #D9C384; }
  ul { margin: .5rem 0 0; padding-left: 1.2rem; }
  code { background: #EFEADF; padding: .1em .35em; border-radius: 2px;
         font-size: .9em; }
  strong.rot { color: #A8442A; }
</style>
</head>
<body>
<main>
  <h1>B³ Retreats — Einrichtung des Panel-Kontos</h1>
  <p class="sub">Diese Seite wird genau einmal gebraucht.</p>

<?php if ($fertig): ?>
  <div class="box ok">
    <p><strong>Das Konto steht</strong> — und damit ist auch bewiesen, dass
      config.php stimmt und die Datenbank erreichbar ist.</p>
    <p>Anmelden laesst es sich noch nicht: die Panel-Oberflaeche auf dem Server
      ist noch nicht gebaut (es fehlen <code>login.php</code> und das Panel
      selbst). Das Konto liegt bereit, sobald sie da ist. Bis dahin werden die
      Inhalte weiter mit der lokalen Oberflaeche gepflegt
      (<code>python3 tools/admin-server.py</code>).</p>
    <p style="margin-bottom:0">Jetzt der wichtigste Schritt:
      <strong class="rot">loeschen Sie <code>admin/setup.php</code> vom Server.</strong>
      Die Seite legt kein zweites Konto an, solange dieses besteht — aber sie
      verraet weiterhin, welche Software hier laeuft. Loeschen ist eine Sekunde
      Arbeit und nimmt das Thema vom Tisch.</p>
  </div>

<?php elseif ($blocker !== null): ?>
  <div class="box stop">
    <p style="margin:0"><?= h($blocker) ?></p>
  </div>

<?php elseif ($schema_fehlt): ?>
  <?php if ($schema_meldung !== null): ?>
    <div class="box stop"><p style="margin:0"><?= h($schema_meldung) ?></p></div>
  <?php endif; ?>
  <div class="box warn">
    <p><strong>Die Datenbank ist erreichbar, aber noch leer.</strong> Es fehlen die
      sechs Tabellen des Panels.</p>
    <p style="margin-bottom:0">Diese Seite kann sie selbst anlegen — es ist derselbe
      Inhalt aus <code>db/schema.sql</code>, den man sonst in phpMyAdmin von Hand
      importiert. Nichts wird dabei geloescht: jede Anweisung ist ein
      <code>CREATE TABLE IF NOT EXISTS</code>.</p>
  </div>
  <form method="post">
    <input type="hidden" name="token" value="<?= h($_SESSION['setup_token'] ?? '') ?>">
    <input type="hidden" name="aktion" value="schema">
    <button type="submit">Tabellen jetzt anlegen</button>
  </form>

<?php else: ?>
  <?php if ($schema_meldung !== null): ?>
    <div class="box ok"><p style="margin:0"><?= h($schema_meldung) ?>
      Jetzt noch das Konto anlegen.</p></div>
  <?php endif; ?>
  <?php if ($fehler): ?>
    <div class="box warn">
      <strong>Bitte noch korrigieren:</strong>
      <ul><?php foreach ($fehler as $f): ?><li><?= h($f) ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <form method="post" autocomplete="off">
    <input type="hidden" name="token" value="<?= h($_SESSION['setup_token'] ?? '') ?>">

    <label for="username">Benutzername</label>
    <input id="username" name="username" required maxlength="60"
           autocapitalize="none" spellcheck="false"
           value="<?= h((string) ($_POST['username'] ?? '')) ?>">
    <p class="hint">Buchstaben, Ziffern, Punkt, Unterstrich, Bindestrich.</p>

    <label for="password">Passwort</label>
    <input id="password" name="password" type="password" required
           minlength="<?= MIN_LEN ?>" autocomplete="new-password">
    <p class="hint">Mindestens <?= MIN_LEN ?> Zeichen. Drei bis vier zufaellige
      Woerter hintereinander sind sicherer und leichter zu behalten als ein
      kurzes Passwort mit Sonderzeichen.</p>

    <label for="password2">Passwort wiederholen</label>
    <input id="password2" name="password2" type="password" required
           minlength="<?= MIN_LEN ?>" autocomplete="new-password">

    <button type="submit">Konto anlegen</button>
  </form>
<?php endif; ?>
</main>
</body>
</html>
