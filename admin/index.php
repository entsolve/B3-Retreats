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

// MUSS vor dem Speichern stehen und nicht erst in _header.php: dort wird
// $nutzer sonst zu spaet gesetzt, und in content.updated_by landet nichts —
// dann sieht man in der Datenbank nicht mehr, wer einen Text geaendert hat.
$nutzer = current_user();

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

        /* „geaendert" heisst: WEICHT VOM URSPRUNG AB — nicht bloss „hat eine
           Zeile in der Datenbank". Seit der Bestand eingespielt ist, hat jedes
           Feld eine Zeile; die bisherige Pruefung haette alle 217 Felder als
           geaendert markiert und die Marke damit wertlos gemacht. */
        $hatZeile = array_key_exists($key, $werte) && trim($werte[$key]) !== '';

        if ($typ === 'list') {
            // Listen werden verglichen, NACHDEM beide Seiten dieselbe Form
            // haben: in der Datenbank steht kompaktes JSON, im Register ein
            // Array. Ohne das Dekodieren waere jede Liste „geaendert".
            $roh = $hatZeile ? json_decode((string) $werte[$key], true) : null;
            if (!is_array($roh)) {
                $roh = $def['default'] ?? [];
                $hatZeile = false;
            }
            $eigen = $hatZeile && $roh != ($def['default'] ?? []);
            $wert = json_encode($roh, JSON_PRETTY_PRINT
                | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            $standard = (string) ($def['default'] ?? '');
            $wert  = $hatZeile ? (string) $werte[$key] : $standard;
            $eigen = $hatZeile && $wert !== $standard;
        }
        $id = 'f_' . md5($key);
      ?>
        <div class="feld">
          <label for="<?= $id ?>">
            <?= esc($def['label'] ?? $key) ?>
            <?php if ($eigen): ?><em class="marker">geaendert</em><?php endif; ?>
          </label>
          <?php if ($typ === 'image'): ?>
            <?php /* Bildfeld: Vorschau, Pfad und zwei Knoepfe. Der Pfad bleibt
                     ein normales Eingabefeld — wer ihn kennt, tippt ihn weiter
                     von Hand, und ohne JavaScript funktioniert das Feld genauso
                     wie vorher. assets/panel.js haengt die Auswahl nur davor. */ ?>
            <div class="bildfeld" data-bildfeld>
              <img class="bildfeld__vorschau" alt=""
                   src="<?= esc('../' . $wert) ?>"<?= trim($wert) === '' ? ' hidden' : '' ?>>
              <div class="bildfeld__steuer">
                <input id="<?= $id ?>" name="feld[<?= esc($key) ?>]" type="text"
                       class="bildfeld__pfad" value="<?= esc($wert) ?>"
                       spellcheck="false" autocapitalize="none">
                <div class="bildfeld__knoepfe">
                  <button type="button" class="knopf-leise" data-bild-waehlen>Bild wählen</button>
                  <button type="button" class="knopf-leise" data-bild-hochladen>Neues Bild hochladen</button>
                </div>
              </div>
            </div>
          <?php elseif ($typ === 'html'): ?>
            <?php /* Sichtbarer Editor. Das textarea bleibt bestehen und bleibt
                     das, was abgeschickt wird — assets/panel.js blendet es aus
                     und schreibt bei jeder Aenderung zurueck. Ohne JavaScript
                     steht hier weiterhin das rohe HTML, und Speichern geht. */ ?>
            <textarea id="<?= $id ?>" name="feld[<?= esc($key) ?>]" rows="4"
                      spellcheck="true" data-editor="html"><?= esc($wert) ?></textarea>
          <?php elseif ($typ === 'list'): ?>
            <?php /* Wiederholung. Im textarea steht JSON — das bleibt auch so,
                     denn genau das wird abgeschickt und beim Speichern geprueft.
                     Daneben liegt die Beschreibung der Unterfelder aus dem
                     Register; assets/panel.js baut daraus je Eintrag eine Karte
                     mit beschrifteten Feldern und blendet das JSON weg.
                     Ohne JavaScript bleibt der Kasten mit JSON — unschoen, aber
                     vollstaendig bedienbar. */ ?>
            <textarea id="<?= $id ?>" name="feld[<?= esc($key) ?>]" rows="12"
                      spellcheck="false" data-editor="liste"
                      data-felder="<?= esc(json_encode($def['fields'] ?? [],
                          JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>"
                      data-eintrag-label="<?= esc($def['itemLabel'] ?? '') ?>"><?= esc($wert) ?></textarea>
          <?php elseif ($typ === 'schalter'): ?>
            <?php /* Ein Haken statt eines Textfeldes. Das versteckte Feld davor
                     sorgt dafuer, dass beim Abhaken auch wirklich etwas
                     abgeschickt wird: ein nicht angehaktes <input type=checkbox>
                     taucht im POST gar nicht auf, und das Feld bliebe auf dem
                     alten Wert stehen. */ ?>
            <input type="hidden" name="feld[<?= esc($key) ?>]" value="">
            <label class="schalter">
              <input id="<?= $id ?>" name="feld[<?= esc($key) ?>]" type="checkbox"
                     value="1"<?= trim($wert) !== '' ? ' checked' : '' ?>>
              <span><?= esc($def['label'] ?? $key) ?></span>
            </label>
          <?php elseif ($typ === 'datum'): ?>
            <?php /* Das Eingabefeld bleibt ein normales Textfeld im Format
                     JJJJ-MM-TT — ohne JavaScript ist es damit weiterhin voll
                     bedienbar. assets/panel.js haengt den Kalender davor.

                     BEWUSST KEIN <input type="date">: der native Kalender
                     schliesst sich nach dem ersten Klick. Hier werden aber
                     zwei zusammengehoerende Tage gewaehlt, und dabei will man
                     sehen, was man tut — der Kalender bleibt offen und
                     zeichnet die Spanne ein. */ ?>
            <input id="<?= $id ?>" name="feld[<?= esc($key) ?>]" type="text"
                   value="<?= esc($wert) ?>" spellcheck="false" autocapitalize="none"
                   data-datum="<?= esc($key) ?>" placeholder="JJJJ-MM-TT"
                   pattern="\d{4}-\d{2}-\d{2}">
          <?php elseif ($typ === 'textarea'): ?>
            <textarea id="<?= $id ?>" name="feld[<?= esc($key) ?>]" rows="4"
                      spellcheck="true"><?= esc($wert) ?></textarea>
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

<?php /* --- Bildauswahl -------------------------------------------------
     Steht EINMAL auf der Seite und wird von jedem Bildfeld benutzt; das
     Feld, aus dem heraus geoeffnet wurde, merkt sich assets/panel.js.

     Die Marke unten ist dieselbe CSRF-Marke wie im Formular: media.php
     nimmt einen Upload nur mit ihr an. Sie steht in einem data-Attribut,
     weil die CSP des Panels kein Inline-Skript zulaesst (siehe config.php)
     — das Skript kann sie also nicht als Variable mitbekommen. */ ?>
<dialog class="bildwahl" data-bildwahl data-csrf="<?= esc(csrf_token()) ?>">
  <div class="bildwahl__kopf">
    <h2>Bild wählen</h2>
    <button type="button" class="knopf-leise" data-bildwahl-schliessen>Schließen</button>
  </div>
  <p class="bildwahl__meldung" data-bildwahl-meldung hidden></p>
  <div class="bildwahl__raster" data-bildwahl-raster>
    <p class="hinweis">Bilder werden geladen …</p>
  </div>
</dialog>

<?php /* Ein einziges Dateifeld fuer die ganze Seite, unsichtbar. Der Knopf
     „Neues Bild hochladen" loest es aus. */ ?>
<input type="file" accept="image/webp,image/jpeg,image/png" hidden data-bild-datei>

<?php require __DIR__ . '/_footer.php'; ?>
