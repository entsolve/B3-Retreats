<?php
/* =====================================================================
   B³ Retreats — Datenbankverbindung.

   Liest config.php und liefert eine PDO-Verbindung. Fehlt die Datei oder
   antwortet die Datenbank nicht, wird NICHT abgebrochen: die Seite selbst
   ist statisch und muss weiterlaufen. Wer die Verbindung braucht, prueft
   den Rueckgabewert.

       require_once __DIR__ . '/db.php';
       $pdo = db();                  // PDO oder null
       if (!$pdo) { … }              // Grund steht in db_error()

   Warum Ausnahmen NICHT nach draussen dringen: eine PDO-Meldung enthaelt
   Hostname, Datenbankname und Benutzer. Auf einer oeffentlichen Seite ist
   das eine Einladung. Deshalb bleibt der Text intern und der Aufrufer
   bekommt nur die Auskunft, DASS es nicht ging.
   ===================================================================== */

declare(strict_types=1);

/** Zuletzt aufgetretener Verbindungsfehler, in Klartext fuer den Betreiber. */
function db_error(): ?string
{
    return db_state()['error'];
}

/** Gemeinsamer Zustand: Konfiguration, Verbindung, Fehlertext. */
function &db_state(): array
{
    static $state = ['config' => null, 'pdo' => null, 'error' => null, 'tried' => false];
    return $state;
}

/** Konfiguration aus config.php, oder null wenn sie fehlt/unbrauchbar ist. */
function db_config(): ?array
{
    $state = &db_state();
    if ($state['config'] !== null) {
        return $state['config'];
    }

    $file = __DIR__ . '/config.php';
    if (!is_file($file)) {
        $state['error'] = 'config.php fehlt. Vorlage ist config.example.php; '
            . 'die Datei gehoert neben index.html und nicht ins Git.';
        return null;
    }

    /** @var mixed $config */
    $config = require $file;
    if (!is_array($config) || !isset($config['db']) || !is_array($config['db'])) {
        $state['error'] = 'config.php liefert kein Array mit einem Schluessel "db".';
        return null;
    }

    foreach (['host', 'name', 'user', 'pass'] as $key) {
        if (!isset($config['db'][$key]) || $config['db'][$key] === '') {
            $state['error'] = "config.php: 'db.$key' ist leer.";
            return null;
        }
        // Die Vorlage traegt Platzhalter in spitzen Klammern. Wer sie stehen
        // laesst, bekommt sonst einen Verbindungsfehler und sucht am falschen Ende.
        if (is_string($config['db'][$key]) && str_contains($config['db'][$key], '<<')) {
            $state['error'] = "config.php: 'db.$key' enthaelt noch den Platzhalter "
                . 'aus der Vorlage. Echte Werte stehen im cPanel unter '
                . '"MySQL-Datenbanken".';
            return null;
        }
    }

    $state['config'] = $config;
    return $config;
}

/** PDO-Verbindung, oder null. Wird nur einmal je Aufruf aufgebaut. */
function db(): ?PDO
{
    $state = &db_state();
    if ($state['tried']) {
        return $state['pdo'];
    }
    $state['tried'] = true;

    $config = db_config();
    if ($config === null) {
        return null;
    }

    $db = $config['db'];
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $db['host'], $db['name']);

    try {
        $state['pdo'] = new PDO($dsn, $db['user'], $db['pass'], [
            // Fehler als Ausnahme: ein stilles false bei einer Abfrage ist die
            // Art von Fehler, die man erst Wochen spaeter bemerkt.
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Echte Prepared Statements, keine vom Treiber nachgebauten.
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        // Klartext bleibt intern, siehe Kopf der Datei.
        error_log('B3 db(): ' . $e->getMessage());
        $state['error'] = 'Keine Verbindung zur Datenbank. Zugangsdaten in '
            . 'config.php pruefen (Name, Benutzer, Passwort) und ob der Benutzer '
            . 'Rechte auf dieser Datenbank hat.';
        return null;
    }

    return $state['pdo'];
}

/** Existiert diese Tabelle? Fuer klare Meldungen, statt eines SQL-Fehlers. */
function db_has_table(PDO $pdo, string $table): bool
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM information_schema.tables '
        . 'WHERE table_schema = DATABASE() AND table_name = ?');
    $stmt->execute([$table]);
    return (int) $stmt->fetchColumn() > 0;
}
