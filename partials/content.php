<?php
/* =====================================================================
   B³ Retreats — bearbeitbare Inhalte (CMS lite).

   Portiert aus dem Motor von matern_website / krakusband, mit zwei
   Unterschieden, die B³ ausmachen:

     1) Die Tabelle `content` heisst hier `k` / `v` (nicht content_key /
        content_value) — so steht sie in db/schema.sql.
     2) B³ hat WIEDERHOLUNGEN: 21 Listen (Experiences, FAQ, Ablauf, Mosaik,
        Preisposten …). Sie liegen als JSON in einer Zeile und kommen ueber
        cl() als Array zurueck. Matern hatte nur flache Schluessel g1..g4 —
        das haette hier 240 Felder auf ueber 500 aufgeblaeht.

   Verwendung auf den oeffentlichen Seiten:

       c('hero.headline')          fertiger, sicherer HTML-Text
       e(c('hero.meta'))           zusaetzlich escaped, fuer Attribute
       cl('exp.items')             Array von Eintraegen (Wiederholung)

   GRUNDSATZ DER UNVERWUESTLICHKEIT: ist die Datenbank weg oder ein
   Schluessel nicht gesetzt, liefert c() den Standardwert aus
   partials/registry/. Die oeffentliche Seite sieht dann genauso aus wie
   vor dem Einschalten des CMS — sie bricht nie ab.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/../db.php';

/** Escapen fuer Text und Attribute auf den oeffentlichen Seiten. */
if (!function_exists('e')) {
    function e($v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

/* ---- Alle Inhaltszeilen aus der Datenbank, einmal je Aufruf ---------- */

function b3_content_all(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = [];
    $pdo = db();
    if ($pdo === null) {
        return $cache;              // keine Datenbank -> nur Standardwerte
    }
    try {
        foreach ($pdo->query('SELECT k, v FROM content') as $row) {
            $cache[$row['k']] = $row['v'];
        }
    } catch (Throwable $e) {
        // Tabelle fehlt (Schema nicht importiert) — kein Grund, die Seite
        // abzuwerfen. Der Betreiber sieht es in admin/setup.php.
        $cache = [];
    }
    return $cache;
}

/* ---- Zusammengefuegtes Register (Standardwerte + Beschriftungen) ----- */

function content_registry(): array
{
    static $reg = null;
    if ($reg !== null) {
        return $reg;
    }
    $reg = [];
    foreach (glob(__DIR__ . '/registry/*.php') ?: [] as $stueck) {
        $arr = require $stueck;
        if (is_array($arr)) {
            $reg += $arr;           // Schluessel sind eindeutig, erster gewinnt
        }
    }
    return $reg;
}

/* ---- Ein Wert: Datenbank -> Register -> '' --------------------------- */

function c(string $key): string
{
    $alle = b3_content_all();
    if (array_key_exists($key, $alle)) {
        $v = (string) $alle[$key];
        // Leer geraeumtes Feld heisst „zurueck zum Standard", nicht „leer".
        // Genau so raeumt die Kundin ein Feld wieder auf.
        if (trim($v) !== '') {
            return $v;
        }
    }
    $reg = content_registry();
    return (string) ($reg[$key]['default'] ?? '');
}

/**
 * Eine Wiederholung als Array.
 *
 * In der Datenbank steht JSON, im Register der Standard als PHP-Array.
 * Ist das JSON unbrauchbar (von Hand verunglueckt), wird der Standard
 * genommen statt eine halbe Liste zu rendern.
 */
function cl(string $key): array
{
    $alle = b3_content_all();
    if (array_key_exists($key, $alle) && trim((string) $alle[$key]) !== '') {
        $dekodiert = json_decode((string) $alle[$key], true);
        if (is_array($dekodiert)) {
            return $dekodiert;
        }
        error_log("B3 cl(): '$key' enthaelt kein gueltiges JSON, Standard genommen.");
    }
    $reg = content_registry();
    $std = $reg[$key]['default'] ?? [];
    return is_array($std) ? $std : [];
}

/** Hat dieses Feld einen eigenen Wert (weicht also vom Standard ab)? */
function content_is_overridden(string $key): bool
{
    $alle = b3_content_all();
    return array_key_exists($key, $alle) && trim((string) $alle[$key]) !== '';
}

/** Feldtyp aus dem Register — das Panel entscheidet damit den Editor. */
function content_type(string $key): string
{
    $reg = content_registry();
    return (string) ($reg[$key]['type'] ?? 'text');
}

/* ---- Basis-URL: echter Host, sonst site_url aus config.php ---------- */

function site_base_url(): string
{
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host !== '' && preg_match('/^[A-Za-z0-9.\-:]+$/', $host)) {
        $schema = (($_SERVER['HTTPS'] ?? '') === 'on'
            || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') ? 'https' : 'http';
        return $schema . '://' . $host;
    }
    $conf = db_config();
    return rtrim((string) ($conf['site_url'] ?? 'https://b3-retreats.de'), '/');
}

/* =====================================================================
   HTML-Sanitizer (Whitelist), 1:1 aus dem Motor uebernommen.

   Er laeuft beim SPEICHERN, nicht beim Ausgeben: was in der Datenbank
   steht, ist bereits sauber. Grund: die Seite wird oft gelesen und selten
   geschrieben — und ein Fehler im Sanitizer faellt beim Speichern auf,
   nicht erst beim Besucher.
   ===================================================================== */

const B3_ALLOWED_TAGS = [
    'p', 'br', 'strong', 'b', 'em', 'i', 'u', 'small',
    'ul', 'ol', 'li', 'a', 'span', 'h2', 'h3', 'h4', 'blockquote',
];

const B3_ALLOWED_ATTRS = [
    'a'          => ['href', 'target', 'rel'],
    'span'       => ['class'],
    'p'          => ['class'],
    'ul'         => ['class'],
    'ol'         => ['class'],
    'li'         => ['class'],
    'h2'         => ['class'],
    'h3'         => ['class'],
    'h4'         => ['class'],
    'blockquote' => ['class'],
];

/* Diese Tags fliegen MIT Inhalt raus — niemals „auspacken". */
const B3_DANGEROUS_TAGS = [
    'script', 'style', 'iframe', 'object', 'embed', 'form', 'input',
    'button', 'textarea', 'select', 'option', 'link', 'meta', 'base',
    'svg', 'math', 'template', 'noscript', 'title', 'head',
];

function b3_url_is_safe(string $url): bool
{
    $url = trim($url);
    // Steuerzeichen und Leerraum vor der Pruefung entfernen — sonst kommt
    // "java\x0bscript:" durch.
    $url = (string) preg_replace('/[\x00-\x20]+/', '', $url);
    if ($url === '') {
        return false;
    }
    if (preg_match('#^(https?:|mailto:|tel:)#i', $url)) {
        return true;
    }
    if (preg_match('~^[/#?]~', $url)) {
        return true;                                  // /pfad, #anker, ?abfrage
    }
    if (str_starts_with($url, '//')) {
        return true;
    }
    if (preg_match('#^[a-z][a-z0-9+.\-]*:#i', $url)) {
        return false;                                 // javascript:, data:, …
    }
    return true;                                      // gewoehnlich relativ
}

function b3_clean_node(DOMNode $node): void
{
    foreach (iterator_to_array($node->childNodes) as $child) {
        if (!($child instanceof DOMElement)) {
            continue;
        }
        $tag = strtolower($child->tagName);

        if (in_array($tag, B3_DANGEROUS_TAGS, true)) {
            $node->removeChild($child);
            continue;
        }

        // Erst den Teilbaum saeubern, damit ein spaeteres Auspacken sicher ist.
        b3_clean_node($child);

        if (!in_array($tag, B3_ALLOWED_TAGS, true)) {
            // Nicht erlaubt, aber harmlos: auspacken, Kinder behalten.
            while ($child->firstChild) {
                $node->insertBefore($child->firstChild, $child);
            }
            $node->removeChild($child);
            continue;
        }

        $erlaubt = B3_ALLOWED_ATTRS[$tag] ?? [];
        foreach (iterator_to_array($child->attributes) as $attr) {
            $an = strtolower($attr->name);
            if (str_starts_with($an, 'on') || !in_array($an, $erlaubt, true)) {
                $child->removeAttribute($attr->name);
                continue;
            }
            if ($an === 'href' && !b3_url_is_safe($attr->value)) {
                $child->removeAttribute($attr->name);
            }
        }

        // Fremdziel ohne rel=noopener ist ein Loch, das man leicht vergisst.
        if ($tag === 'a' && strtolower($child->getAttribute('target')) === '_blank') {
            $child->setAttribute('rel', 'noopener noreferrer');
        }
    }
}

function b3_sanitize_html(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }

    $dom = new DOMDocument('1.0', 'UTF-8');
    $vorher = libxml_use_internal_errors(true);
    $dom->loadHTML(
        '<?xml encoding="UTF-8">' . '<div id="b3-root">' . $html . '</div>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
    );
    libxml_clear_errors();
    libxml_use_internal_errors($vorher);

    $root = $dom->getElementById('b3-root');
    if (!$root) {
        return '';
    }
    b3_clean_node($root);

    $aus = '';
    foreach (iterator_to_array($root->childNodes) as $child) {
        $aus .= $dom->saveHTML($child);
    }
    return trim($aus);
}

/**
 * Einen Wert fuer die Datenbank vorbereiten, je Feldtyp.
 *
 * Der Unterschied ist der Punkt der ganzen Uebung: `text` wird nur von
 * Steuerzeichen befreit und beim Ausgeben escaped, `html` laeuft durch den
 * Sanitizer, `url` wird verworfen, wenn das Schema nicht taugt.
 */
function b3_prepare_value(string $typ, string $wert): string
{
    $wert = str_replace(["\r\n", "\r"], "\n", trim($wert));

    switch ($typ) {
        case 'html':
            return b3_sanitize_html($wert);
        case 'url':
            return b3_url_is_safe($wert) ? $wert : '';
        case 'number':
            return preg_replace('/[^0-9\-]/', '', $wert) ?? '';
        case 'image':
            // Nur Pfade innerhalb der Seite, kein fremder Host.
            return preg_match('#^(assets/img/|img/uploads/)[A-Za-z0-9._/-]+$#', $wert)
                ? $wert : '';
        default:
            // Steuerzeichen raus, Zeilenumbrueche bei textarea behalten.
            return (string) preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $wert);
    }
}
