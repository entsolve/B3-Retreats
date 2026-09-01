<?php
/* =====================================================================
   B³ Retreats — Aussehen der E-Mails.

   Drei Nachrichten, zwei Empfaengerkreise:

     wl_mail_bestaetigung()  an die Person -> „bitte bestaetigen"
     wl_mail_willkommen()    an die Person -> nach dem Klick, der Dank
     wl_mail_meldung()       an die Veranstalterin -> ein neuer Eintrag

   GEBAUT WIE 2005, UND DAS MIT ABSICHT. E-Mail-Programme koennen kein
   modernes CSS: Outlook rendert mit der Word-Engine, Gmail wirft <style>
   im Kopf teilweise weg. Was ueberall haelt, sind Tabellen und Stile
   direkt am Element. Ein schoenes Flex-Layout saehe im Entwurf gut aus
   und im Posteingang zerfallen.

   Farben und Schriften sind die der Seite — nur als feste Werte, denn
   die CSS-Variablen aus tokens.css gibt es hier nicht.
   ===================================================================== */
declare(strict_types=1);

const WL_SAND  = '#EFE9E1';
const WL_IVORY = '#F7F4EF';
const WL_OLIVE = '#454B40';
const WL_TAUPE = '#9A846C';
const WL_TEXT  = '#2B2B28';

/** Gemeinsamer Rahmen: Kopf mit Marke, Inhalt, leiser Fuss. */
function wl_rahmen(string $inhalt, string $fusszeile = ''): string
{
    $serif = "'Cormorant Garamond', Georgia, 'Times New Roman', serif";
    $sans  = "Manrope, -apple-system, 'Segoe UI', Arial, sans-serif";
    $fuss = $fusszeile !== '' ? $fusszeile
        : 'B³ Retreats · Christina Brumm · An den Nahewiesen 20, 55450 Langenlonsheim';

    return '<!DOCTYPE html><html lang="de"><head><meta charset="utf-8">'
      . '<meta name="viewport" content="width=device-width,initial-scale=1">'
      . '</head><body style="margin:0;padding:0;background:' . WL_SAND . ';">'
      . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"'
      . ' style="background:' . WL_SAND . ';padding:32px 16px;"><tr><td align="center">'

      . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"'
      . ' style="max-width:560px;background:' . WL_IVORY . ';">'

      // Kopf: Wortmarke als Text. Ein Bild waere hier falsch — die meisten
      // Programme laden Bilder erst auf Nachfrage, und dann stuende oben
      // ein leerer Kasten.
      . '<tr><td align="center" style="padding:36px 32px 8px;">'
      . '<div style="font-family:' . $serif . ';font-size:34px;font-weight:300;'
      . 'color:' . WL_OLIVE . ';line-height:1;">B<span style="font-size:19px;'
      . 'vertical-align:super;">3</span></div>'
      . '<div style="font-family:' . $sans . ';font-size:9px;letter-spacing:3.4px;'
      . 'color:' . WL_TAUPE . ';margin-top:6px;">RETREATS</div>'
      . '</td></tr>'

      . '<tr><td style="padding:20px 32px 36px;font-family:' . $sans . ';'
      . 'font-size:15px;line-height:1.65;color:' . WL_TEXT . ';">' . $inhalt . '</td></tr>'
      . '</table>'

      . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"'
      . ' style="max-width:560px;"><tr><td align="center"'
      . ' style="padding:18px 24px;font-family:' . $sans . ';font-size:11px;'
      . 'line-height:1.6;color:' . WL_TAUPE . ';">' . $fuss . '</td></tr></table>'

      . '</td></tr></table></body></html>';
}

/** Ein Knopf, der auch dort ein Knopf bleibt, wo es kein CSS gibt. */
function wl_knopf(string $adresse, string $text): string
{
    return '<table role="presentation" cellpadding="0" cellspacing="0" border="0"'
      . ' style="margin:26px 0;"><tr><td style="background:' . WL_OLIVE . ';">'
      . '<a href="' . htmlspecialchars($adresse, ENT_QUOTES) . '"'
      . ' style="display:inline-block;padding:14px 30px;color:' . WL_IVORY . ';'
      . 'text-decoration:none;font-family:Manrope,Arial,sans-serif;font-size:12px;'
      . 'letter-spacing:2px;text-transform:uppercase;">'
      . htmlspecialchars($text) . '</a></td></tr></table>';
}

function wl_h1(string $text): string
{
    return '<h1 style="margin:0 0 18px;font-family:\'Cormorant Garamond\',Georgia,serif;'
      . 'font-size:29px;font-weight:300;line-height:1.15;color:' . WL_OLIVE . ';">'
      . htmlspecialchars($text) . '</h1>';
}

function wl_p(string $text): string
{
    return '<p style="margin:0 0 14px;">' . nl2br(htmlspecialchars($text)) . '</p>';
}

/* ------------------------------------------------------------------ */

/** Schritt eins: bitte den Eintrag bestaetigen. */
function wl_mail_bestaetigung(string $vorname, string $link): array
{
    $html = wl_rahmen(
        wl_h1('Nur noch ein Klick')
      . wl_p('Hallo ' . $vorname . ',')
      . wl_p('du möchtest auf unsere Warteliste — schön, dass du dabei sein willst. '
           . 'Bitte bestätige noch kurz, dass diese E-Mail-Adresse wirklich dir gehört:')
      . wl_knopf($link, 'Eintrag bestätigen')
      . wl_p('Erst danach stehst du auf der Liste. Falls der Knopf nicht funktioniert, '
           . 'kopiere diese Adresse in deinen Browser:')
      . '<p style="margin:0 0 14px;word-break:break-all;font-size:13px;color:'
      . WL_TAUPE . ';">' . htmlspecialchars($link) . '</p>'
      . wl_p('Hast du dich gar nicht eingetragen? Dann ignoriere diese Nachricht '
           . 'einfach — ohne deinen Klick passiert nichts, und wir löschen die '
           . 'Angaben wieder.'));

    $text = "Hallo $vorname,\n\n"
      . "du moechtest auf unsere Warteliste. Bitte bestaetige noch kurz, dass diese\n"
      . "E-Mail-Adresse wirklich dir gehoert:\n\n$link\n\n"
      . "Erst danach stehst du auf der Liste.\n\n"
      . "Hast du dich gar nicht eingetragen? Dann ignoriere diese Nachricht einfach —\n"
      . "ohne deinen Klick passiert nichts, und wir loeschen die Angaben wieder.\n\n"
      . "B3 Retreats";
    return [$html, $text];
}

/** Schritt zwei: der Dank, mit Christinas Worten. */
function wl_mail_willkommen(string $vorname, string $dank): array
{
    $html = wl_rahmen(wl_h1('Du bist dabei') . wl_p('Hallo ' . $vorname . ',') . wl_p($dank));
    $text = "Hallo $vorname,\n\n" . $dank . "\n\nB3 Retreats";
    return [$html, $text];
}

/** An die Veranstalterin: ein bestaetigter Eintrag. */
function wl_mail_meldung(array $e): array
{
    $zeile = function (string $name, string $wert): string {
        return '<tr><td style="padding:7px 16px 7px 0;color:' . WL_TAUPE . ';'
          . 'font-size:11px;letter-spacing:1.6px;text-transform:uppercase;'
          . 'white-space:nowrap;vertical-align:top;">' . htmlspecialchars($name) . '</td>'
          . '<td style="padding:7px 0;border-bottom:1px solid rgba(154,132,108,.25);">'
          . htmlspecialchars($wert) . '</td></tr>';
    };

    $interesse = trim(($e['shared'] ? 'Shared House' : '')
        . ($e['shared'] && $e['friends'] ? ', ' : '') . ($e['friends'] ? 'Friends Special' : ''));
    $interesse = $interesse !== '' ? $interesse : '— keine Angabe —';
    $telefon   = $e['telefon'] !== '' ? $e['telefon'] : '— nicht angegeben —';

    $html = wl_rahmen(
        wl_h1('Neu auf der Warteliste')
      . wl_p('Der Eintrag ist per E-Mail bestätigt.')
      . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"'
      . ' style="margin:20px 0;font-size:14px;">'
      . $zeile('Name', $e['vorname'] . ' ' . $e['nachname'])
      . $zeile('E-Mail', $e['email'])
      . $zeile('Telefon', $telefon)
      . $zeile('Interesse', $interesse)
      . $zeile('Bestätigt', $e['zeit'])
      . '</table>'
      . '<p style="margin:0;font-size:13px;color:' . WL_TAUPE . ';">'
      . 'Auf diese Nachricht zu antworten schreibt direkt an '
      . htmlspecialchars($e['email']) . '.</p>',
        'Diese Nachricht kommt von der Warteliste auf b3-retreats.de');

    $text = "Neu auf der Warteliste (per E-Mail bestaetigt)\n\n"
      . 'Name:      ' . $e['vorname'] . ' ' . $e['nachname'] . "\n"
      . 'E-Mail:    ' . $e['email'] . "\n"
      . 'Telefon:   ' . $telefon . "\n"
      . 'Interesse: ' . $interesse . "\n"
      . 'Bestaetigt: ' . $e['zeit'] . "\n";
    return [$html, $text];
}
