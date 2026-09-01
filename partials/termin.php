<?php
/* =====================================================================
   B³ Retreats — der Termin an EINER Stelle.

   Vorher stand das Datum an neun Stellen im Panel, jede von Hand
   getippt und jede in einem anderen Format: „08.–11. Oktober 2026" im
   Aufmacher, „B³ Retreat Oktober 2026" auf dem Ticket, Anreise und
   Abreise als Fliesstext, und zweimal ein ISO-Zeitpunkt fuer die
   strukturierten Daten, die Google ausliest.

   Einen neuen Termin einzutragen hiess damit: neunmal richtig tippen.
   Wer dabei die strukturierten Daten uebersieht — und die sieht man
   nicht, sie stehen nur im Quelltext — bekommt in der Google-Suche
   monatelang den alten Termin angezeigt, waehrend die Seite den neuen
   nennt.

   JETZT: zwei Datumsfelder, alles andere rechnet sich daraus. In den
   Texten steht die Marke {datum}, und die Kundin behaelt ihre eigene
   Formulierung drumherum:

       „{datum} · Spabrücken · Ratenzahlung möglich"

   Die Marke wird als reine Zeichenkette ersetzt, nicht als Vorlage
   ausgewertet. Aus der Datenbank kann damit kein {{ … }} nachgeschoben
   werden, das dann Werte ausliest, die dort nichts zu suchen haben.
   ===================================================================== */
declare(strict_types=1);

const TERMIN_MONATE = [1 => 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
    'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

/** Datum aus dem Panel (JJJJ-MM-TT) — oder null, wenn unbrauchbar. */
function termin_datum(string $roh): ?array
{
    $roh = trim($roh);
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $roh, $m)) {
        return null;
    }
    [, $j, $mo, $t] = array_map('intval', $m);
    return checkdate($mo, $t, $j) ? ['j' => $j, 'm' => $mo, 't' => $t] : null;
}

/**
 * Der Zeitraum in Worten.
 *
 * Innerhalb eines Monats wird der Monat nur einmal genannt — „08.–11.
 * Oktober 2026" und nicht „08. Oktober – 11. Oktober 2026". Ueber eine
 * Monats- oder Jahresgrenze hinweg wird ausgeschrieben, sonst waere das
 * Ergebnis schlicht falsch.
 */
function termin_spanne(?array $von, ?array $bis): string
{
    if ($von === null) {
        return '';
    }
    $tag = static fn(array $d): string => sprintf('%02d.', $d['t']);
    $voll = static fn(array $d): string => sprintf('%02d. %s %d', $d['t'], TERMIN_MONATE[$d['m']], $d['j']);

    if ($bis === null || $von === $bis) {
        return $voll($von);
    }
    if ($von['j'] !== $bis['j']) {
        return $voll($von) . ' – ' . $voll($bis);
    }
    if ($von['m'] !== $bis['m']) {
        return sprintf('%02d. %s – %s', $von['t'], TERMIN_MONATE[$von['m']], $voll($bis));
    }
    return $tag($von) . '–' . $voll($bis);
}

/** „Oktober 2026" — fuer Titel, in denen kein Tag stehen soll. */
function termin_monat(?array $von): string
{
    return $von === null ? '' : TERMIN_MONATE[$von['m']] . ' ' . $von['j'];
}

/** ISO-Zeitpunkt fuer die strukturierten Daten. */
function termin_iso(?array $d, string $uhrzeit): string
{
    return $d === null ? ''
        : sprintf('%04d-%02d-%02dT%s', $d['j'], $d['m'], $d['t'], $uhrzeit);
}

/**
 * Alle Marken, die in Texten des Panels ersetzt werden.
 *
 * Bewusst kurz gehalten: jede weitere Marke ist eine, die die Kundin
 * kennen muss. {datum} deckt den Normalfall ab, {monat} den Ticket-Titel.
 */
function termin_marken(array $daten): array
{
    $von = termin_datum((string) b3_get_path($daten, 'termin.start'));
    $bis = termin_datum((string) b3_get_path($daten, 'termin.ende'));

    $kurz = static fn(?array $d): string => $d === null ? ''
        : sprintf('%02d.%02d.%d', $d['t'], $d['m'], $d['j']);
    $iso  = static fn(?array $d): string => $d === null ? ''
        : sprintf('%04d-%02d-%02d', $d['j'], $d['m'], $d['t']);
    $tag  = static function (?array $d): string {
        if ($d === null) {
            return '';
        }
        $wochentage = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch',
                       'Donnerstag', 'Freitag', 'Samstag'];
        return $wochentage[(int) date('w', mktime(12, 0, 0, $d['m'], $d['t'], $d['j']))];
    };

    return [
        '{datum}'     => termin_spanne($von, $bis),   // 08.–11. Oktober 2026
        '{monat}'     => termin_monat($von),          // Oktober 2026
        '{start}'     => $kurz($von),                 // 08.10.2026
        '{ende}'      => $kurz($bis),                 // 11.10.2026
        '{start_tag}' => $tag($von),                  // Donnerstag
        '{ende_tag}'  => $tag($bis),                  // Sonntag
        '{start_iso}' => $iso($von),                  // 2026-10-08
        '{ende_iso}'  => $iso($bis),                  // 2026-10-11
    ];
}

/* ---------------------------------------------------------------------
   Freie Plaetze
   --------------------------------------------------------------------- */

/**
 * Was die Tarifkarten anzeigen sollen — abgeleitet aus den freien Plaetzen.
 *
 * Liefert fertige Werte, keine Bedingungen: die Vorlagensprache kann nicht
 * vergleichen, und das soll sie auch nicht lernen muessen.
 *
 * STEHT HIER UND NICHT IN index.php, weil tools/check-pfade.php dieselbe
 * Rechnung braucht. Lag sie inline im Seitenaufbau, meldete die Probe vier
 * gepflegte Felder als wirkungslos — sie sieht die Rechnung ja nicht. Eine
 * Probe, die man wegen falscher Treffer ignoriert, ist keine Probe mehr.
 */
function plaetze_ableiten(array $daten): array
{
    $aus = [];
    foreach (['shared', 'friends'] as $tarif) {
        $roh = trim((string) b3_get_path($daten, "haus.$tarif.plaetze"));

        // Leeres Feld heisst NICHT „ausgebucht", sondern „nicht gepflegt".
        // Sonst legt ein versehentlich geleertes Feld den Verkauf still.
        $frei = $roh === '' ? null : max(0, (int) $roh);
        $voll = $frei !== null && $frei === 0;

        $aus["haus.$tarif.frei"] = $voll ? '' : '1';
        $aus["haus.$tarif.voll"] = $voll ? '1' : '';

        $text = '';
        if ($frei !== null && $frei > 0) {
            $text = $frei === 1
                ? (string) b3_get_path($daten, 'haus.plaetze_einer')
                : str_replace('{n}', (string) $frei,
                    (string) b3_get_path($daten, 'haus.plaetze_muster'));
        }
        $aus["haus.$tarif.plaetze_text"] = $text;
    }
    return $aus;
}

/**
 * Uebernimmt die Warteliste die Buchungsknoepfe?
 *
 * JA in zwei Faellen:
 *
 *   1. Es ist gar kein Buchungslink hinterlegt. Ein Knopf, der „Meinen
 *      Platz sichern" verspricht und dann nur zum naechsten Absatz
 *      springt, ist ein kaputter Knopf — und der steht ausgerechnet auf
 *      der Stelle, an der jemand gerade zusagen wollte.
 *
 *   2. Alle Varianten sind ausgebucht. Weiter zum Bezahlen einzuladen
 *      hiesse, Geld fuer einen Platz zu nehmen, den es nicht gibt.
 *
 * In beiden Faellen ist die Warteliste das ehrliche Ziel: sie ist das,
 * was die Seite in diesem Moment tatsaechlich anbieten kann.
 */
function warteliste_uebernimmt(array $daten): bool
{
    /* Der ausdrueckliche Schalter aus dem Panel schlaegt alles.

       Er ist dazugekommen, weil die Regel darunter zwar richtig, aber nicht
       bedienbar war: um auf Warteliste umzustellen, musste man den
       Buchungslink loeschen oder die Plaetze auf null setzen — beides
       Umwege, auf die niemand von selbst kommt. Stattdessen wurden die
       Knopf-BESCHRIFTUNGEN auf „In Warteliste eintragen" geaendert, und die
       Knoepfe fuehrten weiter zu Stripe. Die Seite tat, was in den Daten
       stand; die Daten widersprachen sich. Ein Schalter, der genau das
       sagt, was er tut, verhindert das. */
    if (trim((string) b3_get_path($daten, 'warteliste.statt_buchung')) !== '') {
        return true;
    }

    $abgeleitet = plaetze_ableiten($daten);
    $alleVoll = ($abgeleitet['haus.shared.voll'] ?? '') === '1'
             && ($abgeleitet['haus.friends.voll'] ?? '') === '1';

    $links = [(string) b3_get_path($daten, 'buchung.url'),
              (string) b3_get_path($daten, 'haus.shared.url'),
              (string) b3_get_path($daten, 'haus.friends.url')];
    foreach ((array) b3_get_path($daten, 'buchung.preise') as $eintrag) {
        $links[] = (string) ($eintrag['url'] ?? '');
    }
    $keinLink = true;
    foreach ($links as $l) {
        if (trim($l) !== '') {
            $keinLink = false;
            break;
        }
    }

    return $keinLink || $alleVoll;
}

/**
 * Was aus der Warteliste-Uebernahme folgt: Ziel und Beschriftung der Knoepfe.
 *
 * Wie plaetze_ableiten() steht auch das hier und nicht in index.php, damit
 * tools/check-pfade.php dieselbe Rechnung anstellen kann. Sonst meldet die
 * Probe das Beschriftungsfeld als wirkungslos — es steht ja nirgends
 * woertlich in der Vorlage, sondern geht in einen abgeleiteten Wert ein.
 */
function buchung_ersatz_ableiten(array $daten): array
{
    $uebernimmt = warteliste_uebernimmt($daten);
    return [
        'warteliste.ziel' => $uebernimmt ? '#warteliste' : '',
        'warteliste.cta_knoepfe_aktiv' => $uebernimmt
            ? (string) b3_get_path($daten, 'warteliste.cta_knoepfe') : '',
    ];
}
