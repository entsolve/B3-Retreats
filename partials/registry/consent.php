<?php
/* B³ Retreats — Content-Register: Cookie-Hinweis (Abschnitt 20).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'consent.eyebrow' => [
        'group' => '20 Cookie-Hinweis',
        'label' => 'Cookie-Hinweis: Kleine Zeile',
        'type' => 'text',
        'hint' => 'Vorspann ueber dem Cookie-Text.',
        'default' => 'Cookies',
    ],
    'consent.text' => [
        'group' => '20 Cookie-Hinweis',
        'label' => 'Cookie-Hinweis: Text',
        'type' => 'html',
        'hint' => 'Erklaerung im Einwilligungsbanner, enthaelt den Link zur Datenschutzerklaerung als HTML. Rechtlich relevant, Aenderungen nur mit Bedacht.',
        'default' => 'Wir setzen nur technisch notwendige Cookies. Optionale Cookies für Statistik oder Marketing
         erst mit deiner Zustimmung — mehr dazu in der <a class="link" href="/datenschutz">Datenschutzerklärung</a>.',
    ],
    'consent.btn_necessary' => [
        'group' => '20 Cookie-Hinweis',
        'label' => 'Cookie-Hinweis: Knopf \'Nur notwendige\'',
        'type' => 'text',
        'hint' => 'Beschriftung des Knopfes, der nur technisch notwendige Cookies erlaubt.',
        'default' => 'Nur notwendige',
    ],
    'consent.btn_all' => [
        'group' => '20 Cookie-Hinweis',
        'label' => 'Cookie-Hinweis: Knopf \'Alle akzeptieren\'',
        'type' => 'text',
        'hint' => 'Beschriftung des Knopfes, der alle Cookies erlaubt.',
        'default' => 'Alle akzeptieren',
    ],
];
