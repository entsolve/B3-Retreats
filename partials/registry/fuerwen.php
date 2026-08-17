<?php
/* B³ Retreats — Content-Register: Für wen (Abschnitt 15).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'fuerwen.rail' => [
        'group' => '15 Für wen',
        'label' => 'Kapitelmarke',
        'type' => 'text',
        'hint' => 'Kleine Kapitelnummer am Seitenrand, z. B. Kapitel 11.',
        'default' => 'Kapitel 11',
    ],
    'fuerwen.eyebrow' => [
        'group' => '15 Für wen',
        'label' => 'Übertitel',
        'type' => 'text',
        'hint' => 'Kleine Zeile über der Abschnittsüberschrift.',
        'default' => 'Ausschließlich für Frauen',
    ],
    'fuerwen.headline' => [
        'group' => '15 Für wen',
        'label' => 'Überschrift',
        'type' => 'text',
        'hint' => 'Hauptüberschrift des Abschnitts Für wen.',
        'default' => 'Ist B³ für dich?',
    ],
    'fuerwen.body' => [
        'group' => '15 Für wen',
        'label' => 'Einleitungsabsätze',
        'type' => 'list',
        'default' => [
            [
                'text' => 'B³ ist für Frauen, die grundsätzlich wissen, wer sie sind und wohin sie möchten. Frauen mit Wünschen, Zielen und Ideen, die sich ganz bewusst ein paar Tage Zeit nehmen möchten, um wieder genauer hinzuhören.',
            ],
            [
                'text' => 'Vielleicht hast du dir bereits ein Leben aufgebaut, das du sehr magst, und trotzdem gibt es Fragen, die im Alltag zu wenig Raum bekommen.',
            ],
        ],
    ],
    'fuerwen.image.src' => [
        'group' => '15 Für wen',
        'label' => 'Bild',
        'type' => 'image',
        'hint' => 'Hochformat-Bild neben dem Text, Bildgröße 900 × 1125 Pixel.',
        'default' => 'assets/img/fuerwen-blick.webp',
    ],
    'fuerwen.image.alt' => [
        'group' => '15 Für wen',
        'label' => 'Bildbeschreibung',
        'type' => 'text',
        'hint' => 'Alternativtext für Screenreader und Suchmaschinen.',
        'default' => 'Blick von der Terrasse über Bäume und Felder',
    ],
    'fuerwen.fragen' => [
        'group' => '15 Für wen',
        'label' => 'Fragen an dich',
        'type' => 'list',
        'default' => [
            [
                'text' => 'Was will ich gerade wirklich?',
            ],
            [
                'text' => 'Was brauche ich mehr und wovon vielleicht weniger?',
            ],
            [
                'text' => 'Was fühlt sich noch nach mir an?',
            ],
            [
                'text' => 'Wo möchte ich etwas verändern?',
            ],
            [
                'text' => 'Was möchte ich genauso behalten, wie es ist?',
            ],
        ],
    ],
    'fuerwen.fazit' => [
        'group' => '15 Für wen',
        'label' => 'Schlusssatz',
        'type' => 'textarea',
        'hint' => 'Hervorgehobener Satz in Serifenschrift am Ende des Abschnitts.',
        'default' => 'Du musst bei B³ keine neue Version von dir werden. Es geht vielmehr darum, wieder näher an das heranzukommen, was längst zu dir gehört.',
    ],
    'fuerwen.cta.label' => [
        'group' => '15 Für wen',
        'label' => 'Button-Beschriftung',
        'type' => 'text',
        'hint' => 'Text des Buttons, der zum Buchungsabschnitt springt.',
        'default' => 'Ja, ich möchte dabei sein',
    ],
    'fuerwen.cta.note' => [
        'group' => '15 Für wen',
        'label' => 'Kleinzeile unter dem Button',
        'type' => 'text',
        'hint' => 'Termin, Ort und Hinweis. &middot; erzeugt den Trennpunkt zwischen den Angaben.',
        'default' => '08.–11. Oktober 2026 &middot; Spabrücken &middot; Ratenzahlung möglich',
    ],
];
