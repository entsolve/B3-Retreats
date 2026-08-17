<?php
/* B³ Retreats — Content-Register: Einladung (Abschnitt 04).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'einladung.rail' => [
        'group' => '04 Einladung',
        'label' => 'Kapitelmarke (seitlich)',
        'type' => 'text',
        'hint' => 'Senkrechte Beschriftung am Seitenrand, z. B. \'Kapitel 01\'.',
        'default' => 'Kapitel 01',
    ],
    'einladung.eyebrow' => [
        'group' => '04 Einladung',
        'label' => 'Übertitel',
        'type' => 'text',
        'hint' => 'Kleine Zeile über der Abschnittsüberschrift.',
        'default' => 'Eine Einladung',
    ],
    'einladung.headline' => [
        'group' => '04 Einladung',
        'label' => 'Überschrift Einladung',
        'type' => 'text',
        'hint' => 'Hauptüberschrift (H2) des Abschnitts.',
        'default' => 'Wann hast du dir das letzte Mal wirklich Zeit für dich genommen?',
    ],
    'einladung.body' => [
        'group' => '04 Einladung',
        'label' => 'Absätze der Einladung',
        'type' => 'list',
        'hint' => 'Fließtext des Abschnitts. Jeder Eintrag wird ein eigener Absatz.',
        'itemLabel' => 'text',
        'fields' => [
            [
                'path' => 'text',
                'label' => 'Absatztext',
                'type' => 'textarea',
                'hint' => 'Ein Absatz Fließtext.',
            ],
        ],
        'default' => [
            [
                'text' => 'Nicht, um etwas abzuarbeiten oder das nächste Ziel zu erreichen. Sondern einfach, um wieder genauer hinzuhören.',
            ],
            [
                'text' => 'B³ ist eine Einladung, für vier Tage Abstand vom Alltag zu nehmen: zu deinem Körper, deinen Gedanken, deiner Intuition und den Wünschen, die dabei manchmal leiser werden.',
            ],
            [
                'text' => 'Vom 08.–11. Oktober schaffen wir inmitten der Natur einen Raum für Frauen, die wissen, wo sie stehen – und sich trotzdem bewusst Zeit für sich nehmen möchten.',
            ],
            [
                'text' => 'Dich erwarten Yin Yoga und Breathwork, Astrologie und Frequenzarbeit, Reflexion und Struktur. Dazu gutes Essen, Natur, Gemeinschaft und genügend freie Zeit für das, was dir guttut.',
            ],
        ],
    ],
    'einladung.link.label' => [
        'group' => '04 Einladung',
        'label' => 'Textlink zum Programm',
        'type' => 'text',
        'hint' => 'Beschriftung des Links, der zum Programmabschnitt springt.',
        'default' => 'Das ganze Programm ansehen',
    ],
    'einladung.image.src' => [
        'group' => '04 Einladung',
        'label' => 'Bild im Einladungsabschnitt',
        'type' => 'image',
        'hint' => 'Hochformat, Seitenverhältnis 900 × 1125.',
        'default' => 'assets/img/detail-kerze.webp',
    ],
    'einladung.image.alt' => [
        'group' => '04 Einladung',
        'label' => 'Bildbeschreibung Einladung',
        'type' => 'text',
        'hint' => 'Alternativtext für Screenreader und Google.',
        'default' => 'Kerze und Lavendel auf einem Holzhocker im Retreat-Raum',
    ],
    'einladung.accent.title' => [
        'group' => '04 Einladung',
        'label' => 'Titel des Merkkastens',
        'type' => 'text',
        'hint' => 'Fett hervorgehobene erste Zeile im farbigen Kasten.',
        'default' => 'Body. Mind. Soul.',
    ],
    'einladung.accent.text' => [
        'group' => '04 Einladung',
        'label' => 'Text des Merkkastens',
        'type' => 'textarea',
        'hint' => 'Ein Satz unter dem Titel des Kastens.',
        'default' => 'Drei Perspektiven, die sich zu einer gemeinsamen Erfahrung verbinden.',
    ],
    'einladung.accent.said' => [
        'group' => '04 Einladung',
        'label' => 'Aussprache-Hinweis',
        'type' => 'text',
        'hint' => 'Kleine Schlusszeile im Kasten, z. B. wie B³ gesprochen wird.',
        'default' => 'B³ – gesprochen: Be free.',
    ],
];
