-- =====================================================================
-- B³ Retreats — Datenbank-Schema (MySQL / MariaDB, cPanel)
-- Aufbau nach Vorbild "matern_website" / "krakusband": CMS-lite + Panel.
--
-- Import, der einfache Weg: admin/setup.php im Browser aufrufen und dort
-- "Tabellen jetzt anlegen" druecken. Die Seite liest genau diese Datei und
-- fuehrt sie aus — keine Handarbeit in phpMyAdmin noetig.
-- Import von Hand, falls gewuenscht: phpMyAdmin -> LINKS DIE DATENBANK WAEHLEN
-- (sonst "No database selected") -> Reiter "Importieren" -> diese Datei.
--
-- Beides ist ohne Risiko wiederholbar: jede Anweisung unten ist ein
-- CREATE TABLE IF NOT EXISTS, ein zweiter Durchlauf aendert und loescht nichts.
--
-- HINWEIS: frueher stand hier "danach db/seed.sql importieren". Diese Datei
-- gibt es noch nicht — der Generator, der die Inhalte aus site.json in die
-- content-Tabelle schreibt, ist offen (siehe RESUME.md). Fuer setup.php ist das
-- ohne Belang: gebraucht wird dort nur admin_users.
-- Zeichensatz: utf8mb4 — deutsche Umlaute ae oe ue ss und „ " brauchen ihn.
--
-- Ein Formular gibt es: die Warteliste (Tabelle `warteliste`, unten). Die
-- Buchung selbst laeuft weiterhin vollstaendig ueber Stripe, dort werden bei
-- uns keine Zahlungsdaten erhoben.
-- =====================================================================
SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- --------------------------------------------------------------------
-- 1) Admin-Konten (Panel /admin)
--    KEIN Default-Passwort im Schema. Konto EINMALIG per Browser anlegen:
--    /admin/setup.php aufrufen, Login + starkes Passwort setzen, danach
--    admin/setup.php vom Server LOESCHEN.
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(60)  NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,          -- password_hash(), PASSWORD_DEFAULT
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login`    DATETIME     NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admin_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 2) Inhalte — eine Zeile je Feld der Redaktionsoberflaeche.
--
--    `k`    ist derselbe Pfad wie bisher in site.json und in
--           admin/schema.json, z.B. "hero.headline" oder "exp.items".
--    `type` steuert Ausgabe UND Editor:
--             text     — einzeilig, wird escaped ausgegeben
--             textarea — mehrzeilig, escaped
--             html     — durch den Sanitizer (Whitelist) gefiltert
--             image    — Pfad unter assets/img/ oder img/uploads/
--             number   — Ganzzahl (z.B. Bildhoehe)
--             url      — externer Link, Schema wird geprueft
--             json     — Wiederholungen (exp.items, faq, timeline, mosaic)
--
--    Fehlt eine Zeile, greift der Default aus partials/registry/ —
--    die Seite rendert also auch mit leerer Tabelle vollstaendig.
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `content` (
  `k`          VARCHAR(190) NOT NULL,
  `v`          LONGTEXT     NULL,
  `type`       ENUM('text','textarea','html','image','number','url','json',
                     'datum','schalter')
                            NOT NULL DEFAULT 'text',
  `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
                            ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` VARCHAR(60)  NULL DEFAULT NULL,
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 3) Versionen — jede Speicherung legt den VORHERIGEN Wert ab.
--    Der Grund ist praktisch, nicht theoretisch: die Kundin pflegt die
--    Seite selbst, und ein versehentlich geleertes Feld muss ohne
--    Datenbank-Backup zurueckholbar sein.
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `content_history` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `k`          VARCHAR(190) NOT NULL,
  `v`          LONGTEXT     NULL,
  `changed_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `changed_by` VARCHAR(60)  NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_history_key_time` (`k`, `changed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 4) Brute-Force-Schutz beim Login.
--    Gezaehlt wird je IP; ueberschreitet die Zahl der Fehlversuche im
--    Zeitfenster das Limit, sperrt login.php die IP voruebergehend.
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip`           VARCHAR(45)  NOT NULL,
  `username`     VARCHAR(60)  NULL DEFAULT NULL,
  `attempted_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_attempts_ip_time` (`ip`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 5) Hochgeladene Bilder aus dem Panel.
--    Die Datei liegt unter img/uploads/, hier stehen nur die Metadaten —
--    damit die Bildauswahl im Editor eine Liste hat und nicht das
--    Dateisystem durchsuchen muss.
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `media` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `path`       VARCHAR(255) NOT NULL,             -- z.B. img/uploads/2026-08/foo.webp
  `alt`        VARCHAR(255) NOT NULL DEFAULT '',
  `width`      SMALLINT UNSIGNED NULL DEFAULT NULL,
  `height`     SMALLINT UNSIGNED NULL DEFAULT NULL,
  `bytes`      INT UNSIGNED NULL DEFAULT NULL,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` VARCHAR(60)  NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_media_path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 6) Einstellungen des Panels (Kleinkram, der keine eigene Tabelle lohnt)
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `k`          VARCHAR(120) NOT NULL,
  `v`          TEXT         NULL,
  `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
                            ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 4) Warteliste — Eintraege aus dem Formular auf der Startseite.
--
--    ZWEI SCHRITTE: nach dem Absenden geht eine Bestaetigungsmail an die
--    eingetragene Adresse; erst der Klick darin traegt endgueltig ein und
--    meldet den Eintrag an die Veranstalterin. Bis dahin steht die Zeile
--    mit `bestaetigt_at IS NULL` in der Tabelle und zaehlt nicht.
--
--    Die bestaetigten Eintraege gehen ausserdem per E-Mail an die Veranstalterin. Die
--    Tabelle ist der Zweitweg und der eigentliche Bestand: E-Mail kann im
--    Spam landen, geloescht werden oder gar nicht erst rausgehen. Wer sich
--    eingetragen hat, waere dann weg — und genau das darf nicht passieren,
--    denn diese Menschen warten auf eine Nachricht.
--
--    EINWILLIGUNG WIRD MITGESCHRIEBEN, nicht bloss abgehakt: Art. 5 Abs. 2
--    DSGVO verlangt, dass die Einwilligung nachweisbar ist. Deshalb stehen
--    Zeitpunkt UND der Wortlaut daneben, der der Person tatsaechlich
--    angezeigt wurde. Aendert sich der Text spaeter, bleibt fuer jeden
--    Altfall belegt, wozu genau zugestimmt wurde.
--
--    IP-ADRESSE NUR ALS HASH. Gebraucht wird sie einzig, um massenhaftes
--    Eintragen zu bremsen; dafuer genuegt ein Vergleich. Der Klartext
--    waere ein personenbezogenes Datum ohne Zweck. Gepfeffert mit einem
--    Geheimnis aus config.php, sonst waere der Hash eines IPv4-Raums in
--    Minuten durchprobiert.
-- --------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `warteliste` (
  `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `vorname`           VARCHAR(120) NOT NULL,
  `nachname`          VARCHAR(120) NOT NULL,
  `email`             VARCHAR(190) NOT NULL,
  `telefon`           VARCHAR(60)  NULL DEFAULT NULL,
  `will_shared`       TINYINT(1)   NOT NULL DEFAULT 0,
  `will_friends`      TINYINT(1)   NOT NULL DEFAULT 0,
  `einwilligung_text` TEXT         NULL DEFAULT NULL,
  `einwilligung_at`   DATETIME     NULL DEFAULT NULL,
  `ip_hash`           CHAR(64)     NULL DEFAULT NULL,
  -- Bestaetigung per E-Mail (Double Opt-in). Erst wenn `bestaetigt_at`
  -- steht, gilt der Eintrag. Vorher ist er nur eine Behauptung: jede und
  -- jeder kann eine fremde Adresse eintippen, und die spaetere Nachricht
  -- „der Termin steht fest" ist Werbung im Sinne des § 7 UWG. Ohne
  -- bestaetigte Adresse laesst sich nicht belegen, dass die Einwilligung
  -- von der Inhaberin des Postfachs kam.
  `token`             CHAR(64)     NULL DEFAULT NULL,
  `token_at`          DATETIME     NULL DEFAULT NULL,
  `bestaetigt_at`     DATETIME     NULL DEFAULT NULL,
  `mail_ok`           TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at`        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  -- Zweimal dieselbe Adresse ist kein Fehler der Person, sondern ein zweiter
  -- Klick. Der Eintrag wird dann aufgefrischt statt verdoppelt.
  UNIQUE KEY `uq_warteliste_email` (`email`),
  KEY `idx_warteliste_created` (`created_at`),
  KEY `idx_warteliste_ip` (`ip_hash`, `created_at`),
  KEY `idx_warteliste_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- Nachtrag: neue Feldtypen fuer die content-Tabelle.
--
--    CREATE TABLE IF NOT EXISTS ruehrt eine vorhandene Tabelle nicht an —
--    bei einer bestehenden Installation bliebe der alte ENUM stehen, und
--    das Speichern eines Datums- oder Schalterfeldes im Panel liefe ins
--    Leere: MySQL nimmt den unbekannten Wert nicht an. Sichtbar waere das
--    als „ich aendere den Termin, es passiert nichts".
--
--    MODIFY COLUMN ist wiederholbar: steht der ENUM schon richtig, ist die
--    Anweisung wirkungslos.
-- --------------------------------------------------------------------
ALTER TABLE `content`
  MODIFY COLUMN `type` ENUM('text','textarea','html','image','number','url','json',
                            'datum','schalter') NOT NULL DEFAULT 'text';
