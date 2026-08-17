<?php
/* B³ Retreats — Content-Register: Inklusive (Abschnitt 13).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'inkl.eyebrow' => [
        'group' => '13 Inklusive',
        'label' => 'Überzeile Inklusivleistungen',
        'type' => 'text',
        'hint' => 'Kleine Zeile über der Überschrift',
        'default' => 'Im Preis enthalten',
    ],
    'inkl.headline' => [
        'group' => '13 Inklusive',
        'label' => 'Überschrift Inklusivleistungen',
        'type' => 'text',
        'hint' => 'Hauptüberschrift des Abschnitts',
        'default' => 'Was ist inklusive?',
    ],
    'inkl.intro' => [
        'group' => '13 Inklusive',
        'label' => 'Einleitung Inklusivleistungen',
        'type' => 'textarea',
        'hint' => 'Satz über der Liste, endet mit Doppelpunkt',
        'default' => 'Mit deiner Buchung ist fast alles abgedeckt, was du während der gemeinsamen Tage brauchst:',
    ],
    'inkl.items' => [
        'group' => '13 Inklusive',
        'label' => 'Enthaltene Leistungen',
        'type' => 'list',
        'default' => [
            [
                'text' => '3 Übernachtungen',
            ],
            [
                'text' => 'Frühstück am Freitag, Samstag und Sonntag',
            ],
            [
                'text' => 'gemeinsame Abendessen',
            ],
            [
                'text' => 'Snacks für zwischendurch',
            ],
            [
                'text' => 'Yin Yoga',
            ],
            [
                'text' => 'Gruppen-Breathwork',
            ],
            [
                'text' => 'Astro Energy Reading',
            ],
            [
                'text' => 'Solfeggio-Frequenz-Erinnerungs-Reise',
            ],
            [
                'text' => 'Gruppen-Session „Mit Struktur zu mehr Leichtigkeit“',
            ],
            [
                'text' => 'unsere gemeinsame Zeit rund um den Neumond',
            ],
            [
                'text' => 'Nutzung der Retreat- und Gemeinschaftsbereiche',
            ],
            [
                'text' => 'ausreichend freie Zeit für dich',
            ],
        ],
    ],
    'inkl.excluded_label' => [
        'group' => '13 Inklusive',
        'label' => 'Titel Nicht enthalten',
        'type' => 'text',
        'hint' => 'Fette Überschrift des Kastens mit den nicht enthaltenen Leistungen',
        'default' => 'Nicht enthalten',
    ],
    'inkl.excluded_text' => [
        'group' => '13 Inklusive',
        'label' => 'Nicht enthaltene Leistungen',
        'type' => 'textarea',
        'hint' => 'Kurztext, was zusätzlich anfällt (Anreise, 1:1 Sessions)',
        'default' => 'Deine individuelle An- und Abreise sowie optional buchbare 1:1 Sessions.',
    ],
];
