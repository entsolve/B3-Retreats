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
-- KEINE leads-Tabelle: B³ hat kein Kontaktformular, die Buchung laeuft
-- vollstaendig ueber Stripe. Kommt spaeter eines dazu, wird sie ergaenzt.
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
  `type`       ENUM('text','textarea','html','image','number','url','json')
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
