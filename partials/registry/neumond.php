<?php
/* B³ Retreats — Content-Register: Neumond (Abschnitt 07).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'neumond.rail' => [
        'group' => '07 Neumond',
        'label' => 'Kapitelmarke (seitlich)',
        'type' => 'text',
        'hint' => 'Senkrechte Beschriftung am Seitenrand, z. B. \'Kapitel 04\'.',
        'default' => 'Kapitel 04',
    ],
    'neumond.eyebrow' => [
        'group' => '07 Neumond',
        'label' => 'Übertitel',
        'type' => 'text',
        'hint' => 'Kleine Zeile über der Abschnittsüberschrift.',
        'default' => 'Mitten im Wochenende',
    ],
    'neumond.headline' => [
        'group' => '07 Neumond',
        'label' => 'Überschrift Neumond',
        'type' => 'text',
        'hint' => 'Hauptüberschrift (H2) des Abschnitts.',
        'default' => 'Der Neumond als besonderer Moment',
    ],
    'neumond.ruler' => [
        'group' => '07 Neumond',
        'label' => 'Linienbeschriftung',
        'type' => 'text',
        'hint' => 'Kurzes Wort in der Zierlinie, hier der Wochentag.',
        'default' => 'Samstag',
    ],
    'neumond.intro' => [
        'group' => '07 Neumond',
        'label' => 'Einleitung Neumond',
        'type' => 'textarea',
        'hint' => 'Absatz, der den Neumond einordnet.',
        'default' => 'Mitten in unserem gemeinsamen Wochenende begleitet uns der Neumond und gibt uns einen schönen Anlass, noch einmal bewusster hinzuschauen.',
    ],
    'neumond.fragen' => [
        'group' => '07 Neumond',
        'label' => 'Reflexionsfragen',
        'type' => 'list',
        'hint' => 'Aufzählung der Fragen, die das Wochenende begleiten.',
        'itemLabel' => 'text',
        'fields' => [
            [
                'path' => 'text',
                'label' => 'Frage',
                'type' => 'text',
                'hint' => 'Eine Frage pro Eintrag.',
            ],
        ],
        'default' => [
            [
                'text' => 'Was brauche ich gerade wirklich?',
            ],
            [
                'text' => 'Was fühlt sich noch nach mir an?',
            ],
            [
                'text' => 'Wo wünsche ich mir Veränderung oder mehr Balance?',
            ],
            [
                'text' => 'Und welchen Dingen möchte ich in meinem Leben wieder mehr Raum geben?',
            ],
        ],
    ],
    'neumond.outro' => [
        'group' => '07 Neumond',
        'label' => 'Schlussabsätze Neumond',
        'type' => 'list',
        'hint' => 'Absätze unter den Fragen.',
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
                'text' => 'Diese Fragen werden nicht in einer einzigen Session beantwortet. Sie dürfen sich durch unsere gemeinsamen Tage ziehen und aus ganz unterschiedlichen Perspektiven betrachtet werden.',
            ],
            [
                'text' => 'Dabei geht es nicht darum, nach vier Tagen alles anders zu machen. Vielleicht geht es vielmehr darum, klarer zu sehen, was längst da ist und wieder mehr darauf zu vertrauen.',
            ],
        ],
    ],
];
