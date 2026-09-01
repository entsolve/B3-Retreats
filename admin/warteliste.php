<?php
/* =====================================================================
   B³ Retreats — die Warteliste im Panel.

   WARUM ES DIESE SEITE BRAUCHT, gleich zwei Gruende:

   1. Die Datenschutzerklaerung sagt zu, den Eintrag auf formlosen
      Widerruf hin zu loeschen. Eine Zusage, fuer die man einen
      Entwickler und phpMyAdmin braucht, ist keine. Der Loeschknopf
      unten ist die Erfuellung dieser Zusage.

   2. Die Meldung ueber einen neuen Eintrag geht per E-Mail. E-Mail
      kann ausfallen — dann steht der Eintrag zwar sicher in der
      Tabelle, aber niemand weiss davon. Hier ist er sichtbar, und die
      Spalte „gemeldet" zeigt genau die Faelle, bei denen die
      Benachrichtigung nicht rausging.

   UNBESTAETIGTE EINTRAEGE STEHEN MIT DABEI, aber deutlich getrennt:
   wer den Link in der Bestaetigungsmail nicht geklickt hat, gehoert
   NICHT angeschrieben — dafuer ist die Bestaetigung ja da. Sie ganz zu
   verstecken waere trotzdem falsch: haeufen sie sich, stimmt etwas mit
   dem Mailversand nicht.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../partials/content.php';

security_headers();
require_login();
$nutzer = current_user();

$pdo = db();
$meldung = null;
$meldungsart = 'ok';

/* --- Loeschen --------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int) post('loeschen');
    if ($id > 0 && $pdo !== null) {
        try {
            $q = $pdo->prepare('SELECT email FROM warteliste WHERE id = ?');
            $q->execute([$id]);
            $wen = (string) $q->fetchColumn();
            $pdo->prepare('DELETE FROM warteliste WHERE id = ?')->execute([$id]);
            // Ins Protokoll, nicht in eine Tabelle: geloescht ist geloescht.
            // Eine „Papierkorb"-Kopie waere genau die Speicherung, der die
            // Person gerade widersprochen hat.
            error_log('B3 warteliste: Eintrag ' . $wen . ' geloescht von '
                . ($nutzer['username'] ?? '?'));
            $meldung = 'Eintrag gelöscht.';
        } catch (Throwable $e) {
            $meldung = 'Löschen fehlgeschlagen: ' . $e->getMessage();
            $meldungsart = 'fehler';
        }
    }
}

/* --- Ausgabe als CSV --------------------------------------------------- */
if (get('csv') === '1' && $pdo !== null) {
    $q = $pdo->query('SELECT vorname, nachname, email, telefon, will_shared, '
        . 'will_friends, created_at, bestaetigt_at FROM warteliste '
        . 'WHERE bestaetigt_at IS NOT NULL ORDER BY bestaetigt_at');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="warteliste-'
        . date('Y-m-d') . '.csv"');
    $aus = fopen('php://output', 'w');
    // Byte Order Mark, sonst zeigt Excel unter Windows „GruÃŸe" statt „Grüße".
    fwrite($aus, "\xEF\xBB\xBF");
    fputcsv($aus, ['Vorname', 'Nachname', 'E-Mail', 'Telefon', 'Shared House',
                   'Friends Special', 'Eingetragen', 'Bestätigt'], ';');
    foreach ($q as $z) {
        fputcsv($aus, [$z['vorname'], $z['nachname'], $z['email'], $z['telefon'],
            $z['will_shared'] ? 'ja' : '', $z['will_friends'] ? 'ja' : '',
            $z['created_at'], $z['bestaetigt_at']], ';');
    }
    fclose($aus);
    exit;
}

/* --- Lesen ------------------------------------------------------------- */
$eintraege = [];
$fehlerText = null;
if ($pdo === null) {
    $fehlerText = db_error() ?? 'Keine Datenbankverbindung.';
} else {
    try {
        // Bestaetigte zuerst, innerhalb dessen die neuesten oben.
        $eintraege = $pdo->query('SELECT * FROM warteliste ORDER BY '
            . '(bestaetigt_at IS NULL), COALESCE(bestaetigt_at, created_at) DESC')
            ->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        $fehlerText = 'Die Tabelle `warteliste` fehlt oder ist nicht lesbar. '
            . 'Sie wird von db/schema.sql angelegt — /admin/setup.php aufrufen.';
    }
}

$bestaetigt = 0;
$offen = 0;
$ungemeldet = 0;
foreach ($eintraege as $e) {
    if ($e['bestaetigt_at'] !== null) {
        $bestaetigt++;
        if (!$e['mail_ok']) { $ungemeldet++; }
    } else {
        $offen++;
    }
}

function wl_datum(?string $s): string
{
    return $s === null || $s === '' ? '—' : date('d.m.Y H:i', strtotime($s));
}

$titel = 'Warteliste';
require __DIR__ . '/_header.php';
?>
  <h1>Warteliste</h1>

  <?php if ($meldung !== null): ?>
    <p class="meldung meldung--<?= esc($meldungsart) ?>"><?= esc($meldung) ?></p>
  <?php endif; ?>

  <?php if ($fehlerText !== null): ?>
    <p class="meldung meldung--fehler"><?= esc($fehlerText) ?></p>
  <?php else: ?>

    <p class="hinweis">
      <strong><?= $bestaetigt ?></strong> bestätigt<?php if ($offen): ?>,
      <strong><?= $offen ?></strong> noch nicht bestätigt<?php endif; ?>.
      <?php if ($ungemeldet): ?>
        <br><strong>Achtung:</strong> bei <?= $ungemeldet ?> bestätigten Einträgen
        ging die Benachrichtigung per E-Mail nicht hinaus. Sie stehen trotzdem
        vollständig hier — bitte die E-Mail-Einstellungen prüfen.
      <?php endif; ?>
      <?php if ($offen): ?>
        <br>Nicht bestätigte Einträge <em>nicht</em> anschreiben: dass die
        Adresse der Person gehört, ist dort noch nicht belegt.
      <?php endif; ?>
    </p>

    <?php if ($bestaetigt): ?>
      <p><a class="knopf-leise" href="?csv=1">Bestätigte als CSV herunterladen</a></p>
    <?php endif; ?>

    <?php if (!$eintraege): ?>
      <p class="hinweis">Noch niemand eingetragen.</p>
    <?php else: ?>
      <div class="eintraege">
        <?php foreach ($eintraege as $e):
          $ist = $e['bestaetigt_at'] !== null; ?>
          <div class="eintrag<?= $ist ? '' : ' eintrag--offen' ?>">
            <p class="eintrag__kopf">
              <strong><?= esc($e['vorname'] . ' ' . $e['nachname']) ?></strong>
              <?php if (!$ist): ?><em class="marker">nicht bestätigt</em><?php endif; ?>
              <?php if ($ist && !$e['mail_ok']): ?>
                <em class="marker">nicht gemeldet</em>
              <?php endif; ?>
            </p>
            <p class="eintrag__zeile">
              <a href="mailto:<?= esc($e['email']) ?>"><?= esc($e['email']) ?></a>
              <?php if (trim((string) $e['telefon']) !== ''): ?>
                · <?= esc($e['telefon']) ?>
              <?php endif; ?>
            </p>
            <p class="eintrag__zeile">
              <?php
                $will = [];
                if ($e['will_shared']) { $will[] = 'Shared House'; }
                if ($e['will_friends']) { $will[] = 'Friends Special'; }
                echo $will ? esc(implode(' · ', $will)) : '<span class="leise">keine Angabe</span>';
              ?>
            </p>
            <p class="eintrag__zeile leise">
              eingetragen <?= esc(wl_datum($e['created_at'])) ?>
              <?php if ($ist): ?> · bestätigt <?= esc(wl_datum($e['bestaetigt_at'])) ?><?php endif; ?>
            </p>
            <form method="post" class="eintrag__aktion"
                  onsubmit="return confirm('Diesen Eintrag endgültig löschen? Das lässt sich nicht rückgängig machen.');">
              <?= csrf_field() ?>
              <button class="knopf-winzig knopf-winzig--warnung" type="submit"
                      name="loeschen" value="<?= (int) $e['id'] ?>">löschen</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
<?php require __DIR__ . '/_footer.php'; ?>
