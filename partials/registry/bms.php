<?php
/* B³ Retreats — Content-Register: Body. Mind. Soul. (Abschnitt 05).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'bms.rail' => [
        'group' => '05 Body. Mind. Soul.',
        'label' => 'Kapitelmarke (seitlich)',
        'type' => 'text',
        'hint' => 'Senkrechte Beschriftung am Seitenrand, z. B. \'Kapitel 02\'.',
        'default' => 'Kapitel 02',
    ],
    'bms.eyebrow' => [
        'group' => '05 Body. Mind. Soul.',
        'label' => 'Übertitel',
        'type' => 'text',
        'hint' => 'Kleine Zeile über der Abschnittsüberschrift.',
        'default' => 'Body. Mind. Soul.',
    ],
    'bms.headline' => [
        'group' => '05 Body. Mind. Soul.',
        'label' => 'Überschrift Body. Mind. Soul.',
        'type' => 'text',
        'hint' => 'Hauptüberschrift (H2) des Abschnitts.',
        'default' => 'Das ist B³.',
    ],
    'bms.ruler' => [
        'group' => '05 Body. Mind. Soul.',
        'label' => 'Linienbeschriftung',
        'type' => 'text',
        'hint' => 'Kurzes Wort in der Zierlinie unter der Überschrift.',
        'default' => 'Drei Zugänge',
    ],
    'bms.intro' => [
        'group' => '05 Body. Mind. Soul.',
        'label' => 'Einleitung',
        'type' => 'textarea',
        'hint' => 'Absatz, der die drei Gastgeberinnen einführt.',
        'default' => 'Wir sind Sophie, Sarah und Christina und bringen drei ganz unterschiedliche Perspektiven mit, die sich bei B³ miteinander verbinden.',
    ],
    'bms.cols' => [
        'group' => '05 Body. Mind. Soul.',
        'label' => 'Die drei Zugänge',
        'type' => 'list',
        'hint' => 'Je Eintrag eine Spalte: Ebene, Person und Beschreibung.',
        'itemLabel' => 'title',
        'fields' => [
            [
                'path' => 'title',
                'label' => 'Ebene',
                'type' => 'text',
                'hint' => 'Body, Mind oder Soul.',
            ],
            [
                'path' => 'sup',
                'label' => 'Hochgestellte Ziffer',
                'type' => 'text',
                'hint' => 'Kleine Zahl neben der Ebene (1, 2, 3).',
            ],
            [
                'path' => 'who',
                'label' => 'Name',
                'type' => 'text',
                'hint' => 'Wer diese Ebene begleitet.',
            ],
            [
                'path' => 'text',
                'label' => 'Beschreibung',
                'type' => 'textarea',
                'hint' => 'Was diese Person einbringt.',
            ],
        ],
        'default' => [
            [
                'title' => 'Body',
                'sup' => '1',
                'who' => 'Sarah',
                'text' => 'Sarah arbeitet mit dem Körper, dem Atem und unserer Wahrnehmung.',
            ],
            [
                'title' => 'Soul',
                'sup' => '2',
                'who' => 'Sophie',
                'text' => 'Sophie verbindet Astrologie, Energiearbeit und spirituelle Impulse.',
            ],
            [
                'title' => 'Mind',
                'sup' => '3',
                'who' => 'Christina',
                'text' => 'Christina bringt Klarheit, Reflexion und Struktur hinein und beschäftigt sich mit der Frage, wie wir unser Leben so gestalten können, dass das, was uns wirklich wichtig ist, darin auch seinen Platz findet.',
            ],
        ],
    ],
    'bms.outro' => [
        'group' => '05 Body. Mind. Soul.',
        'label' => 'Schlussabsätze',
        'type' => 'list',
        'hint' => 'Abschließende Absätze des Abschnitts.',
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
                'text' => 'Für uns gehören diese Ebenen zusammen. Denn das, was wir fühlen, was wir denken und wie wir unser Leben gestalten, lässt sich nicht immer voneinander trennen.',
            ],
            [
                'text' => 'Genau aus dieser Verbindung ist B³ entstanden.',
            ],
        ],
    ],
    'bms.sign' => [
        'group' => '05 Body. Mind. Soul.',
        'label' => 'Signatur-Zeile',
        'type' => 'text',
        'hint' => 'Kurze, hervorgehobene Schlusszeile des Abschnitts.',
        'default' => 'Body. Mind. Soul. Be free.',
    ],
];
