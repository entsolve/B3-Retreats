<?php
/* B³ Retreats — Content-Register: Danke-Seite (Abschnitt 21).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'danke.meta.title' => [
        'group' => '21 Danke-Seite',
        'label' => 'Seitentitel der Danke-Seite',
        'type' => 'text',
        'hint' => 'Steht im Browser-Tab. Die Seite wird für Suchmaschinen gesperrt.',
        'default' => 'Du bist dabei! | B³ Retreats',
    ],
    'danke.eyebrow' => [
        'group' => '21 Danke-Seite',
        'label' => 'Kleine Zeile über der Überschrift',
        'type' => 'text',
        'hint' => 'Kurz, in Großbuchstaben gesetzt.',
        'default' => 'Buchung bestätigt',
    ],
    'danke.headline' => [
        'group' => '21 Danke-Seite',
        'label' => 'Überschrift',
        'type' => 'html',
        'hint' => 'Die große Begrüßung nach der Buchung.',
        'default' => 'Du bist dabei! ♡',
    ],
    'danke.lead' => [
        'group' => '21 Danke-Seite',
        'label' => 'Satz unter der Überschrift',
        'type' => 'html',
        'hint' => 'Eine Zeile, die die Reservierung bestätigt.',
        'default' => 'Dein Platz beim B³ Retreat ist für dich reserviert.',
    ],
    'danke.intro' => [
        'group' => '21 Danke-Seite',
        'label' => 'Einleitende Absätze',
        'type' => 'list',
        'hint' => 'Fließtext direkt unter der Begrüßung. Jeder Eintrag wird ein eigener Absatz.',
        'itemLabel' => 'text',
        'fields' => [
            [
                'path' => 'text',
                'label' => 'Absatz',
                'type' => 'textarea',
                'hint' => '',
            ],
        ],
        'default' => [
            [
                'text' => 'Wie schön, dass du dich für diese besonderen Tage entschieden hast. Wir freuen uns riesig darauf, dich beim B³ Retreat willkommen zu heißen und gemeinsam eine unvergessliche Zeit zu erleben.',
            ],
            [
                'text' => 'Jetzt darf die Vorfreude beginnen. ♡',
            ],
        ],
    ],
    'danke.image.src' => [
        'group' => '21 Danke-Seite',
        'label' => 'Foto auf der Danke-Seite',
        'type' => 'image',
        'hint' => 'Das Bild von euch dreien.',
        'default' => 'assets/img/hero-tall.webp',
    ],
    'danke.image.alt' => [
        'group' => '21 Danke-Seite',
        'label' => 'Bildbeschreibung',
        'type' => 'text',
        'hint' => 'Alternativtext für Screenreader.',
        'default' => 'Christina, Sophie und Sarah vom B³ Retreat',
    ],
    'danke.next.title' => [
        'group' => '21 Danke-Seite',
        'label' => 'Überschrift „Wie geht es weiter“',
        'type' => 'text',
        'default' => 'Wie geht es jetzt weiter?',
    ],
    'danke.next.body' => [
        'group' => '21 Danke-Seite',
        'label' => 'Absätze zum weiteren Ablauf',
        'type' => 'list',
        'hint' => 'Jeder Eintrag wird ein eigener Absatz.',
        'itemLabel' => 'text',
        'fields' => [
            [
                'path' => 'text',
                'label' => 'Absatz',
                'type' => 'textarea',
                'hint' => '',
            ],
        ],
        'default' => [
            [
                'text' => 'In den nächsten Stunden erhältst du von uns eine persönliche E-Mail mit allen wichtigen Informationen zu deinem Retreat.',
            ],
            [
                'text' => 'Darin findest du alles, was du für die nächsten Schritte wissen musst.',
            ],
        ],
    ],
    'danke.ask.title' => [
        'group' => '21 Danke-Seite',
        'label' => 'Überschrift der Rückfrage',
        'type' => 'text',
        'hint' => 'Die Bitte um die Angabe zur Verpflegung.',
        'default' => 'Eine kleine Sache brauchen wir noch von dir',
    ],
    'danke.ask.text' => [
        'group' => '21 Danke-Seite',
        'label' => 'Text der Rückfrage',
        'type' => 'html',
        'default' => 'Bitte antworte direkt auf unsere E-Mail und teile uns mit, welche Verpflegung du während des Retreats möchtest:',
    ],
    'danke.ask.options' => [
        'group' => '21 Danke-Seite',
        'label' => 'Auswahlmöglichkeiten',
        'type' => 'list',
        'hint' => 'Zum Beispiel „Vegetarisch“ und „Mit Fleisch & Fisch“.',
        'itemLabel' => 'text',
        'fields' => [
            [
                'path' => 'text',
                'label' => 'Möglichkeit',
                'type' => 'text',
                'hint' => '',
            ],
        ],
        'default' => [
            [
                'text' => 'Vegetarisch',
            ],
            [
                'text' => 'Mit Fleisch &amp; Fisch',
            ],
        ],
    ],
    'danke.ask.note' => [
        'group' => '21 Danke-Seite',
        'label' => 'Hinweis unter der Auswahl',
        'type' => 'html',
        'hint' => 'Zum Beispiel der Hinweis auf den Spam-Ordner.',
        'default' => 'So können wir deine Verpflegung entsprechend einplanen. Solltest du die E-Mail nicht direkt finden, wirf bitte auch einen Blick in deinen Spam-Ordner.',
    ],
    'danke.outro.title' => [
        'group' => '21 Danke-Seite',
        'label' => 'Überschrift des Abschlusses',
        'type' => 'text',
        'default' => 'Das war’s für den Moment.',
    ],
    'danke.outro.body' => [
        'group' => '21 Danke-Seite',
        'label' => 'Absätze des Abschlusses',
        'type' => 'list',
        'hint' => 'Jeder Eintrag wird ein eigener Absatz.',
        'itemLabel' => 'text',
        'fields' => [
            [
                'path' => 'text',
                'label' => 'Absatz',
                'type' => 'textarea',
                'hint' => '',
            ],
        ],
        'default' => [
            [
                'text' => 'Alles Weitere bekommst du persönlich von uns per E-Mail. Bis dahin musst du nichts weiter tun – außer dich auf deine Auszeit zu freuen.',
            ],
            [
                'text' => 'Wir freuen uns auf dich!',
            ],
        ],
    ],
    'danke.sign' => [
        'group' => '21 Danke-Seite',
        'label' => 'Grußzeile',
        'type' => 'html',
        'hint' => 'Zum Beispiel „Christina & das B³ Retreat Team“.',
        'default' => 'Christina &amp; das B³ Retreat Team',
    ],
    'danke.cta' => [
        'group' => '21 Danke-Seite',
        'label' => 'Beschriftung des Knopfes zurück zur Seite',
        'type' => 'text',
        'default' => 'Zur Startseite',
    ],
];
