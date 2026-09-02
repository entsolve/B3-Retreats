<?php
/* =====================================================================
   B³ Retreats — VORLAGE fuer die Secrets-Konfiguration.

   SO WIRD SIE VERWENDET:
   1) Auf dem Server diese Datei als  public_html/config.php  kopieren.
   2) Echte Werte eintragen (Datenbank, Domain).
   3) config.php NICHT ins Git committen — sie steht in .gitignore und wird
      beim Deploy (.cpanel.yml) ausgeschlossen, existiert also nur auf dem
      Server. Der HTTP-Zugriff ist zusaetzlich in .htaccess gesperrt.

   Gelesen von db.php. Fehlt die Datei, bricht die Seite NICHT ab: sie
   rendert dann die Default-Werte aus partials/registry/ und das Panel
   meldet sauber "keine Datenbankverbindung".
   ===================================================================== */
return [
    // Oeffentliche Basis-URL (canonical, Open Graph, Sitemap). OHNE Slash am Ende.
    'site_url' => 'https://b3-retreats.de',

    // Datenbank (cPanel -> MySQL-Datenbanken)
    'db' => [
        'host' => 'localhost',        // bei den meisten Hostern localhost
        'name' => 'DB_NAME',
        'user' => 'DB_USER',
        'pass' => 'DB_PASSWORT',
    ],

    // Panel-Sicherheit
    'admin' => [
        // Fehlversuche je IP, bevor der Login voruebergehend sperrt.
        'max_attempts'   => 8,
        // Zeitfenster dafuer, in Minuten.
        'attempt_window' => 15,
        // Sperrdauer nach Ueberschreiten, in Minuten.
        'lockout'        => 15,
        // Sitzung laeuft nach so vielen Minuten Untaetigkeit ab.
        'session_idle'   => 120,
    ],

    // Bild-Upload im Panel
    'uploads' => [
        'dir'        => 'img/uploads',                    // relativ zum docroot
        'max_bytes'  => 6 * 1024 * 1024,                  // 6 MB je Datei
        'max_pixels' => 40000000,                         // Schutz vor Dekompressionsbomben
        'types'      => ['image/webp', 'image/jpeg', 'image/png'],
    ],

    // --- Warteliste ------------------------------------------------------
    'warteliste' => [
        // Wohin die Eintraege gemeldet werden.
        'empfaenger' => 'hello@b3-retreats.de',
        // Absender MUSS eine Adresse der eigenen Domain sein, sonst wirft der
        // Empfaenger die Nachricht wegen SPF/DMARC weg. Die Adresse der
        // eintragenden Person steht im Reply-To, nicht im From.
        'absender'   => 'noreply@b3-retreats.de',
        // Pfeffer fuer den IP-Hash. EINMALIG durch etwas Zufaelliges ersetzen,
        // z. B. `openssl rand -hex 32`. Ohne eigenen Wert ist der Hash
        // durchprobierbar und damit wertlos.
        'pfeffer'    => 'BITTE-EINMALIG-ERSETZEN',
        // Hoechstens so viele Eintraege je IP und Stunde.
        'max_pro_stunde' => 5,
    ],

    // --- E-Mail ----------------------------------------------------------
    'mail' => [
        // OPTIONAL. Schluessel, mit dem das SMTP-Passwort in der Datenbank
        // verschluesselt wird.
        //
        // Bleibt der Platzhalter stehen, wird der Schluessel aus den
        // Datenbank-Zugangsdaten oben abgeleitet — die SMTP-Einrichtung im
        // Panel funktioniert dann ohne jeden Eingriff in diese Datei. Das ist
        // Absicht: wer die Einstellungsseite oeffnet, hat in dem Moment keinen
        // Dateizugriff, sonst braeuchte er die Seite nicht.
        //
        // Einen eigenen Wert einzutragen lohnt aus einem Grund: aendert sich
        // das Datenbank-Passwort, ist das gespeicherte SMTP-Passwort sonst
        // unlesbar und muss im Panel neu hinterlegt werden. Wert erzeugen mit
        // `openssl rand -hex 32`.
        'schluessel' => 'BITTE-EINMALIG-ERSETZEN',
    ],
];
