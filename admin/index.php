<?php
/* =====================================================================
   B³ Retreats — Inhalts-Editor des Panels.

   Aufbau wie in matern_website/admin/content.php, nur auf B³ zugeschnitten:
   die Felder kommen aus partials/registry/ (erzeugt aus admin/schema.json und
   content/site.json), gruppiert nach den 21 Abschnitten der Seite.

   ZWEI DINGE, DIE DAS VERHALTEN BESTIMMEN:

   1) Speichern legt einen ÜBERSCHREIBWERT an, es aendert nie den Standard.
      Ein leer geraeumtes Feld heisst deshalb „zurueck zum Standard" — genau so
      nimmt die Kundin eine Aenderung zurueck, ohne den alten Text zu kennen.

   2) Vor jedem Ueberschreiben wandert der VORHERIGE Wert nach
      content_history. Ein versehentlich geleertes Feld ist damit ohne
      Datenbank-Backup wiederherstellbar.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../partials/content.php';

security_headers();
require_login();

$pdo = db();
$meldung = null;
$meldungsart = 'ok';

/* --- Felder nach Abschnitt gruppieren -------------------------------- */

$register = content_registry();
$abschnitte = [];                       // '01 Suchmaschine…' => [key => def, …]
foreach ($register as $key => $def) {
    $abschnitte[$def['group'] ?? 'Sonstiges'][$key] = $def;
}
ksort($abschnitte);                     // die Gruppen tragen ihre Nummer im Namen

$gruppen = array_keys($abschnitte);
$aktuell = get('a');
if (!in_array($aktuell, $gruppen, true)) {
    $aktuell = $gruppen[0] ?? '';
}

/* --- Speichern -------------------------------------------------------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $gruppe = post('gruppe');
    if (!isset($abschnitte[$gruppe])) {
        $meldung = 'Unbekannter Abschnitt.';
        $meldungsart = 'fehler';
    } elseif ($pdo === null) {
        $meldung = db_error() ?? 'Keine Datenbankverbindung.';
        $meldungsart = 'fehler';
    } else {
        $aktuell = $gruppe;
        $eingaben = $_POST['feld'] ?? [];
        $geaendert = 0;
        $abgelehnt = [];

        try {
            $pdo->beginTransaction();
            $vorher = $pdo->prepare('SELECT v FROM content WHERE k = ?');
            $verlauf = $pdo->prepare('INSERT INTO content_history (k, v, changed_by) '
                . 'VALUES (?, ?, ?)');
            $schreiben = $pdo->prepare(
                'INSERT INTO content (k, v, type, updated_by) VALUES (?, ?, ?, ?) '
                . 'ON DUPLICATE KEY UPDATE v = VALUES(v), type = VALUES(type), '
                . 'updated_by = VALUES(updated_by)'
            );

            foreach ($abschnitte[$gruppe] as $key => $def) {
                if (!array_key_exists($key, $eingaben)) {
                    continue;
                }
                $roh = (string) $eingaben[$key];
                $typ = (string) ($def['type'] ?? 'text');

                if ($typ === 'list') {
                    // Wiederholungen liegen als JSON. Unbrauchbares JSON wird
                    // NICHT gespeichert: eine halbe Liste zerlegt die Seite.
                    $t = trim($roh);
                    if ($t === '') {
                        $neu = '';
                    } else {
                        $probe = json_decode($t, true);
                        if (!is_array($probe)) {
                            $abgelehnt[] = $def['label'] ?? $key;
                            continue;
                        }
                        $neu = json_encode($probe, JSON_UNESCAPED_UNICODE
                            | JSON_UNESCAPED_SLASHES);
                    }
                } else {
                    $neu = b3_prepare_value($typ, $roh);
                    // Der Sanitizer hat den Wert verworfen, obwohl etwas drin
                    // stand — das soll der Redaktion auffallen, nicht stillschweigen.
                    if ($neu === '' && trim($roh) !== '') {
                        $abgelehnt[] = $def['label'] ?? $key;
                        continue;
                    }
                }

                $vorher->execute([$key]);
                $alt = $vorher->fetchColumn();
                $alt = $alt === false ? null : (string) $alt;
                if ((string) $alt === $neu) {
                    continue;                        // nichts zu tun
                }
                if ($alt !== null) {
                    $verlauf->execute([$key, $alt, $nutzer['username'] ?? null]);
                }
                $schreiben->execute([$key, $neu, $typ === 'list' ? 'json' : $typ,
                    $nutzer['username'] ?? null]);
                $geaendert++;
            }
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('B3 admin speichern: ' . $e->getMessage());
            $meldung = 'Speichern fehlgeschlagen. Der Grund steht im Fehlerprotokoll.';
            $meldungsart = 'fehler';
        }

        if ($meldung === null) {
            $meldung = $geaendert === 0
                ? 'Keine Aenderung — alles war schon so gespeichert.'
                : ($geaendert === 1 ? '1 Feld gespeichert.' : "$geaendert Felder gespeichert.");
            if ($abgelehnt) {
                $meldung .= ' Nicht uebernommen: ' . implode(', ', $abgelehnt)
                    . ' (Inhalt war fuer diesen Feldtyp nicht zulaessig).';
                $meldungsart = 'warnung';
            }
        }
    }
}

/* --- Aktuelle Werte fuer die Anzeige ---------------------------------- */

$werte = [];
if ($pdo !== null) {
    try {
        foreach ($pdo->query('SELECT k, v FROM content') as $r) {
            $werte[$r['k']] = (string) $r['v'];
        }
    } catch (Throwable $e) {
        $meldung = $meldung ?? 'Die Tabelle "content" fehlt — admin/setup.php aufrufen.';
        $meldungsart = $meldung === null ? $meldungsart : 'warnung';
    }
}

$titel = 'Inhalte';
require __DIR__ . '/_header.php';
?>
  <?php if ($pdo === null): ?>
    <p class="meldung meldung--fehler"><?= esc(db_error() ?? 'Keine Datenbankverbindung.') ?></p>
  <?php endif; ?>
  <?php if ($meldung !== null): ?>
    <p class="meldung meldung--<?= esc($meldungsart) ?>"><?= esc($meldung) ?></p>
  <?php endif; ?>

  <div class="spalten">
    <nav class="abschnitte">
      <?php foreach ($gruppen as $g): ?>
        <a href="?a=<?= urlencode($g) ?>"<?= $g === $aktuell ? ' class="aktiv"' : '' ?>>
          <?= esc($g) ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <form method="post" class="felder">
      <?= csrf_field() ?>
      <input type="hidden" name="gruppe" value="<?= esc($aktuell) ?>">
      <h1><?= esc($aktuell) ?></h1>

      <?php foreach ($abschnitte[$aktuell] ?? [] as $key => $def):
        $typ = (string) ($def['type'] ?? 'text');
        $eigen = array_key_exists($key, $werte) && trim($werte[$key]) !== '';
        if ($typ === 'list') {
            $wert = $eigen ? $werte[$key]
                : json_encode($def['default'] ?? [], JSON_PRETTY_PRINT
                    | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            $wert = $eigen ? $werte[$key] : (string) ($def['default'] ?? '');
        }
        $id = 'f_' . md5($key);
      ?>
        <div class="feld">
          <label for="<?= $id ?>">
            <?= esc($def['label'] ?? $key) ?>
            <?php if ($eigen): ?><em class="marker">geaendert</em><?php endif; ?>
          </label>
          <?php if ($typ === 'textarea' || $typ === 'html' || $typ === 'list'): ?>
            <textarea id="<?= $id ?>" name="feld[<?= esc($key) ?>]"
                      rows="<?= $typ === 'list' ? 12 : 4 ?>"
                      spellcheck="<?= $typ === 'list' ? 'false' : 'true' ?>"><?= esc($wert) ?></textarea>
          <?php else: ?>
            <input id="<?= $id ?>" name="feld[<?= esc($key) ?>]"
                   type="<?= $typ === 'number' ? 'text' : 'text' ?>"
                   value="<?= esc($wert) ?>">
          <?php endif; ?>
          <?php if (!empty($def['hint'])): ?>
            <p class="hinweis"><?= esc($def['hint']) ?></p>
          <?php endif; ?>
          <p class="schluessel"><?= esc($key) ?> · <?= esc($typ) ?></p>
        </div>
      <?php endforeach; ?>

      <div class="leiste">
        <button type="submit">Speichern</button>
        <span class="hinweis">Ein Feld ganz leeren stellt den urspruenglichen
          Text wieder her.</span>
      </div>
    </form>
  </div>
<?php require __DIR__ . '/_footer.php'; ?>
