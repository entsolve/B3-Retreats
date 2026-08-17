<?php
/* =====================================================================
   B³ Retreats — Bildverwaltung des Panels.

   Sie beantwortet genau zwei Fragen des Formulars:

       GET  media.php?format=json   Welche Bilder gibt es?
       POST media.php               Nimm dieses neue Bild an.

   Beides als JSON, weil die Auswahl IM Formular passiert: die Kundin
   klickt neben einem Bildfeld auf „Bild wählen", bekommt ein Fenster mit
   allen Bildern und waehlt eines aus. Wuerde sie dafuer die Seite
   verlassen, waeren alle anderen Aenderungen im Abschnitt weg — genau der
   Fehler, den man erst merkt, wenn man eine halbe Stunde Arbeit verloren hat.

   ZWEI QUELLEN, EIN VERZEICHNIS IM NETZ:

       assets/img/     die abgenommenen Bilder der Seite (aus build-assets.py).
                       Werden nie ueberschrieben — sie gehoeren zum Bestand.
       img/uploads/    was die Redaktion selbst hochlaedt, nach Monat sortiert.

   WAS BEIM HOCHLADEN GEPRUEFT WIRD, UND WARUM:

     * Anmeldung und CSRF-Marke. Ohne beides ist ein Upload-Feld eine
       offene Tuer in das Dateisystem.
     * Die Datei muss ein Bild SEIN, nicht nur so heissen: getimagesize()
       liest den Kopf der Datei. Die Endung entscheidet nichts, sie wird
       aus dem erkannten Typ neu gesetzt.
     * Nur WebP, JPEG und PNG. KEIN SVG: eine SVG-Datei ist ein XML-Dokument
       und darf <script> enthalten — hochgeladen und im Browser geoeffnet
       waere das fremder Code auf der eigenen Domain. Wer ein SVG braucht
       (das Seitensymbol zum Beispiel), legt es ueber das Git dazu.
     * Der Name wird neu gebildet, nicht uebernommen. Ein hochgeladener
       Name kann '../', Steuerzeichen oder eine zweite Endung enthalten.
     * img/uploads/.htaccess verbietet zusaetzlich die Ausfuehrung von PHP.
       Das ist der Guertel zum Hosentraeger: selbst wenn oben etwas
       durchrutscht, ist die Datei dort kein Programm, sondern ein Bild.
   ===================================================================== */
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../partials/content.php';

security_headers();
require_login();

const B3_MAX_BYTES = 8 * 1024 * 1024;         // 8 MB
const B3_UPLOAD_DIR = 'img/uploads';

/** Erlaubte Bildtypen: erkannter Typ => Endung, die vergeben wird. */
const B3_TYPEN = [
    IMAGETYPE_WEBP => 'webp',
    IMAGETYPE_JPEG => 'jpg',
    IMAGETYPE_PNG  => 'png',
];

$wurzel = dirname(__DIR__);

/** Antwort als JSON, danach ist Schluss. */
function json_aus(array $daten, int $code = 200): void
{
    if (!headers_sent()) {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode($daten, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Alle Bilder, die zur Auswahl stehen.
 *
 * Das Dateisystem ist die Wahrheit, nicht die Tabelle `media`: assets/img/
 * ist ueber Git gewachsen und war nie in der Datenbank. Die Tabelle steuert
 * nur bei, wer wann etwas hochgeladen hat.
 */
function bilder_sammeln(string $wurzel): array
{
    $treffer = [];

    foreach (['assets/img', B3_UPLOAD_DIR] as $ordner) {
        $basis = $wurzel . '/' . $ordner;
        if (!is_dir($basis)) {
            continue;
        }
        $lauf = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($basis, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($lauf as $datei) {
            /** @var SplFileInfo $datei */
            if (!$datei->isFile()) {
                continue;
            }
            $endung = strtolower($datei->getExtension());
            if (!in_array($endung, ['webp', 'jpg', 'jpeg', 'png', 'svg', 'ico'], true)) {
                continue;
            }
            $rel = str_replace('\\', '/', substr($datei->getPathname(), strlen($wurzel) + 1));
            $treffer[] = [
                'path'     => $rel,
                'bytes'    => $datei->getSize(),
                'geaendert' => $datei->getMTime(),
                'eigen'    => str_starts_with($rel, B3_UPLOAD_DIR . '/'),
            ];
        }
    }

    // Zuletzt Hochgeladenes zuerst — danach sucht die Redaktion.
    usort($treffer, function (array $a, array $b): int {
        if ($a['eigen'] !== $b['eigen']) {
            return $a['eigen'] ? -1 : 1;
        }
        return $b['geaendert'] <=> $a['geaendert'];
    });

    return $treffer;
}

/** Aus einem hochgeladenen Namen einen unbedenklichen Dateinamen bilden. */
function name_bilden(string $original, string $endung): string
{
    $stamm = pathinfo($original, PATHINFO_FILENAME);
    $stamm = mb_strtolower($stamm, 'UTF-8');

    // Umlaute lesbar halten, statt sie wegzuwerfen.
    $stamm = strtr($stamm, ['ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss']);
    $stamm = (string) preg_replace('/[^a-z0-9]+/', '-', $stamm);
    $stamm = trim($stamm, '-');
    if ($stamm === '') {
        $stamm = 'bild';
    }
    $stamm = substr($stamm, 0, 50);

    // Kurzer Zufallsanhang: zwei gleichnamige Dateien sollen sich nicht
    // gegenseitig ueberschreiben, und der Name soll nicht erratbar sein.
    return $stamm . '-' . bin2hex(random_bytes(3)) . '.' . $endung;
}

/* =====================================================================
   POST — ein neues Bild annehmen
   ===================================================================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    if (!isset($_FILES['bild'])) {
        json_aus(['ok' => false, 'fehler' => 'Es wurde keine Datei geschickt.'], 400);
    }

    $datei = $_FILES['bild'];

    if (($datei['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        // Die haeufigste Ursache ist nicht „kaputt", sondern „zu gross" —
        // und das steht in php.ini, nicht in unserer Grenze.
        $texte = [
            UPLOAD_ERR_INI_SIZE   => 'Die Datei ist groesser, als der Server erlaubt (upload_max_filesize).',
            UPLOAD_ERR_FORM_SIZE  => 'Die Datei ist zu gross.',
            UPLOAD_ERR_PARTIAL    => 'Die Datei kam nur halb an. Bitte noch einmal versuchen.',
            UPLOAD_ERR_NO_FILE    => 'Es wurde keine Datei ausgewaehlt.',
            UPLOAD_ERR_NO_TMP_DIR => 'Dem Server fehlt ein Zwischenspeicher fuer Uploads.',
            UPLOAD_ERR_CANT_WRITE => 'Der Server konnte die Datei nicht schreiben.',
        ];
        json_aus(['ok' => false, 'fehler' => $texte[$datei['error']] ?? 'Der Upload ist fehlgeschlagen.'], 400);
    }

    if (!is_uploaded_file($datei['tmp_name'])) {
        json_aus(['ok' => false, 'fehler' => 'Ungueltiger Upload.'], 400);
    }

    if ($datei['size'] > B3_MAX_BYTES) {
        json_aus(['ok' => false, 'fehler' => 'Das Bild ist groesser als 8 MB. Bitte kleiner speichern.'], 400);
    }

    // Der Kopf der Datei entscheidet, nicht ihr Name.
    $info = @getimagesize($datei['tmp_name']);
    if ($info === false || !isset(B3_TYPEN[$info[2]])) {
        json_aus([
            'ok' => false,
            'fehler' => 'Das ist kein zulaessiges Bild. Erlaubt sind WebP, JPEG und PNG.',
        ], 400);
    }

    [$breite, $hoehe] = $info;
    $endung = B3_TYPEN[$info[2]];

    $monat  = date('Y-m');
    $ordner = $wurzel . '/' . B3_UPLOAD_DIR . '/' . $monat;
    if (!is_dir($ordner) && !@mkdir($ordner, 0755, true) && !is_dir($ordner)) {
        error_log('B3 media: Ordner nicht anlegbar: ' . $ordner);
        json_aus(['ok' => false, 'fehler' => 'Der Ordner fuer Bilder liess sich nicht anlegen.'], 500);
    }

    $name = name_bilden((string) ($datei['name'] ?? 'bild'), $endung);
    $ziel = $ordner . '/' . $name;

    if (!@move_uploaded_file($datei['tmp_name'], $ziel)) {
        error_log('B3 media: move_uploaded_file nach ' . $ziel . ' fehlgeschlagen');
        json_aus(['ok' => false, 'fehler' => 'Die Datei liess sich nicht ablegen.'], 500);
    }
    @chmod($ziel, 0644);

    $rel = B3_UPLOAD_DIR . '/' . $monat . '/' . $name;

    // In der Tabelle steht, wer wann was hochgeladen hat. Scheitert das,
    // ist das Bild trotzdem da — der Eintrag ist Buchhaltung, kein Inhalt.
    try {
        $pdo = db();
        if ($pdo !== null) {
            $pdo->prepare('INSERT INTO media (path, width, height, bytes, created_by) '
                . 'VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE '
                . 'width = VALUES(width), height = VALUES(height), bytes = VALUES(bytes)')
                ->execute([$rel, $breite, $hoehe, filesize($ziel) ?: null,
                    current_user()['username'] ?? null]);
        }
    } catch (Throwable $e) {
        error_log('B3 media: Eintrag in `media` fehlgeschlagen: ' . $e->getMessage());
    }

    json_aus([
        'ok'     => true,
        'path'   => $rel,
        'width'  => $breite,
        'height' => $hoehe,
        'bytes'  => filesize($ziel) ?: 0,
    ]);
}

/* =====================================================================
   GET — die Liste
   ===================================================================== */

json_aus(['ok' => true, 'bilder' => bilder_sammeln($wurzel)]);
