<?php
/* =====================================================================
   B³ Retreats — E-Mail-Einstellungen des Panels.

   Ohne SMTP verschickt die Seite ueber mail(). Das laeuft auf vielen
   Hostern, kommt aber bei fremden Postfaechern oft nicht an: der
   Absender ist dann der Webserver, und Empfaenger wie Gmail oder GMX
   pruefen inzwischen streng, ob der Absender fuer die Domain sprechen
   darf. Mit den Zugangsdaten des eigenen Postfachs geht die Nachricht
   ueber den Mailserver der Domain — gedeckt durch SPF und DKIM.

   DAS PASSWORT WIRD VERSCHLUESSELT ABGELEGT. Es muss im Klartext
   benutzbar bleiben, ein Hash hilft hier nicht; der Schluessel dafuer
   steht in config.php, die nicht im Git liegt. Ein Datenbank-Auszug
   allein gibt das Passwort damit nicht her.

   ES WIRD NIE ZURUECKGESCHICKT. Im Formular steht ein leeres Feld mit
   dem Hinweis, dass ein Passwort hinterlegt ist. Wer nichts eintraegt,
   laesst das gespeicherte unangetastet.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../partials/content.php';
require_once __DIR__ . '/../partials/mail.php';
require_once __DIR__ . '/../partials/mail-vorlagen.php';

security_headers();
require_login();
$nutzer = current_user();

$pdo = db();
$meldung = null;
$meldungsart = 'ok';

const EMAIL_FELDER = [
    'host'       => ['Server (SMTP-Host)', 'z. B. smtp.ionos.de oder mail.b3-retreats.de'],
    'port'       => ['Port', '587 für STARTTLS, 465 für SSL'],
    'sicherheit' => ['Verschlüsselung', ''],
    'benutzer'   => ['Benutzername', 'Meist die vollständige E-Mail-Adresse'],
    'von'        => ['Absender-Adresse', 'Muss zur Domain gehören — sonst landet die Post im Spam'],
    'von_name'   => ['Absender-Name', 'Was im Posteingang als Absender steht'],
    'empfaenger' => ['Eintragungen melden an', 'Hierhin gehen die Wartelisten-Eintragungen'],
];

function email_setzen(PDO $pdo, string $k, string $v): void
{
    $pdo->prepare('INSERT INTO settings (k, v) VALUES (?, ?) '
        . 'ON DUPLICATE KEY UPDATE v = VALUES(v)')->execute(['smtp.' . $k, $v]);
}

/* --- Speichern und Probe --------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if ($pdo === null) {
        $meldung = db_error() ?? 'Keine Datenbankverbindung.';
        $meldungsart = 'fehler';
    } else {
        try {
            foreach (array_keys(EMAIL_FELDER) as $k) {
                email_setzen($pdo, $k, trim(post('f_' . $k)));
            }
            // Leeres Passwortfeld heisst „nicht anfassen".
            $neuesPasswort = post('f_passwort');
            if ($neuesPasswort !== '') {
                $verschluesselt = mail_encrypt($neuesPasswort);
                if ($verschluesselt === '') {
                    throw new RuntimeException(
                        'In config.php fehlt der Schlüssel unter mail.schluessel. '
                        . 'Ohne ihn kann das Passwort nicht sicher abgelegt werden.');
                }
                email_setzen($pdo, 'passwort', $verschluesselt);
            }
            if (post('loeschen') === '1') {
                email_setzen($pdo, 'passwort', '');
            }
            $meldung = 'Gespeichert.';

            if (post('probe') === '1') {
                // Einstellungen sind frisch geschrieben — den Zwischenspeicher
                // umgehen, sonst wird mit den alten Werten geprueft.
                mail_settings_neu_lesen();
                $an = trim(post('f_empfaenger')) ?: 'hello@b3-retreats.de';
                [$html, $text] = wl_mail_meldung([
                    'vorname' => 'Probe', 'nachname' => 'Eintrag',
                    'email' => $an, 'telefon' => '', 'shared' => true,
                    'friends' => false, 'zeit' => date('d.m.Y H:i'),
                ]);
                $fehler = null;
                if (mail_send($an, 'Probe: E-Mail-Einstellungen', $html, $text, null, $fehler)) {
                    $meldung = 'Gespeichert. Die Probe-Nachricht ist an ' . $an
                             . ' hinausgegangen.';
                } else {
                    $meldung = 'Gespeichert, aber die Probe schlug fehl: ' . $fehler;
                    $meldungsart = 'fehler';
                }
            }
        } catch (Throwable $e) {
            $meldung = $e->getMessage();
            $meldungsart = 'fehler';
        }
    }
}

$werte = mail_settings_neu_lesen();
$hatPasswort = trim($werte['passwort'] ?? '') !== '';

$titel = 'E-Mail';
require __DIR__ . '/_header.php';
?>
  <?php if ($meldung !== null): ?>
    <p class="meldung meldung--<?= esc($meldungsart) ?>"><?= esc($meldung) ?></p>
  <?php endif; ?>

  <form method="post" class="felder">
    <?= csrf_field() ?>
    <h1>E-Mail</h1>
    <p class="hinweis">
      Ohne diese Angaben verschickt die Seite über den Webserver. Das kommt oft
      nicht an. Mit den Zugangsdaten eines Postfachs der eigenen Domain geht die
      Post den regulären Weg. Die Daten stehen bei deinem Hoster unter
      „E-Mail-Konten“ → „Einstellungen“.
    </p>

    <?php if (trim($werte['empfaenger'] ?? '') === ''): ?>
      <p class="meldung meldung--fehler">
        „Eintragungen melden an“ ist leer. Solange dort nichts steht, gehen die
        Meldungen über neue Wartelisten-Einträge an die fest eingebaute Adresse
        hello@b3-retreats.de. Wer sie woanders haben will, trägt sie hier ein.
      </p>
    <?php endif; ?>

    <?php foreach (EMAIL_FELDER as $k => [$label, $hilfe]):
      $id = 'f_' . $k; $wert = (string) ($werte[$k] ?? ''); ?>
      <div class="feld">
        <label for="<?= $id ?>"><?= esc($label) ?></label>
        <?php if ($k === 'sicherheit'): ?>
          <select id="<?= $id ?>" name="<?= $id ?>">
            <?php foreach (['starttls' => 'STARTTLS (Port 587, üblich)',
                            'ssl'      => 'SSL/TLS (Port 465)',
                            'keine'    => 'keine (nicht empfohlen)'] as $v => $t): ?>
              <option value="<?= $v ?>"<?= ($wert ?: 'starttls') === $v ? ' selected' : '' ?>>
                <?= esc($t) ?></option>
            <?php endforeach; ?>
          </select>
        <?php else: ?>
          <input id="<?= $id ?>" name="<?= $id ?>"
                 type="<?= $k === 'port' ? 'number' : ($k === 'von' || $k === 'empfaenger' ? 'email' : 'text') ?>"
                 value="<?= esc($wert) ?>" spellcheck="false" autocapitalize="none">
        <?php endif; ?>
        <?php if ($hilfe !== ''): ?><p class="hilfe"><?= esc($hilfe) ?></p><?php endif; ?>
        <p class="schluessel">smtp.<?= esc($k) ?></p>
      </div>
    <?php endforeach; ?>

    <div class="feld">
      <label for="f_passwort">Passwort</label>
      <input id="f_passwort" name="f_passwort" type="password" autocomplete="new-password"
             placeholder="<?= $hatPasswort ? 'gespeichert — leer lassen, um es zu behalten' : 'noch keins hinterlegt' ?>">
      <p class="hilfe">
        Wird verschlüsselt abgelegt und nie wieder angezeigt. Leer lassen ändert nichts.
        <?php if ($hatPasswort): ?>
          <label style="display:block;margin-top:.5rem">
            <input type="checkbox" name="loeschen" value="1"> gespeichertes Passwort entfernen
          </label>
        <?php endif; ?>
      </p>
      <p class="schluessel">smtp.passwort · verschlüsselt</p>
    </div>

    <div class="aktionen">
      <button type="submit" class="knopf">Speichern</button>
      <button type="submit" class="knopf-leise" name="probe" value="1">
        Speichern und Probe-Nachricht schicken
      </button>
    </div>
  </form>
<?php require __DIR__ . '/_footer.php'; ?>
