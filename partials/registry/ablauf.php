<?php
/* B³ Retreats — Content-Register: Ablauf (Abschnitt 06).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'ablauf.rail' => [
        'group' => '06 Ablauf',
        'label' => 'Kapitelmarke (seitlich)',
        'type' => 'text',
        'hint' => 'Senkrechte Beschriftung am Seitenrand, z. B. \'Kapitel 03\'.',
        'default' => 'Kapitel 03',
    ],
    'ablauf.eyebrow' => [
        'group' => '06 Ablauf',
        'label' => 'Übertitel',
        'type' => 'text',
        'hint' => 'Kleine Zeile über der Abschnittsüberschrift, hier der Zeitraum.',
        'default' => 'Donnerstag bis Sonntag',
    ],
    'ablauf.headline' => [
        'group' => '06 Ablauf',
        'label' => 'Überschrift Ablauf',
        'type' => 'text',
        'hint' => 'Hauptüberschrift (H2) des Abschnitts.',
        'default' => 'Eine Reise zurück zu dir.',
    ],
    'ablauf.ruler' => [
        'group' => '06 Ablauf',
        'label' => 'Linienbeschriftung',
        'type' => 'text',
        'hint' => 'Kurzes Wort in der Zierlinie unter der Überschrift.',
        'default' => 'Der Bogen',
    ],
    'ablauf.intro' => [
        'group' => '06 Ablauf',
        'label' => 'Einleitungsabsätze',
        'type' => 'list',
        'hint' => 'Absätze über der Zeitleiste.',
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
                'text' => 'Wir möchten diese vier Tage nicht wie ein klassisches Seminar gestalten, bei dem ein Programmpunkt auf den nächsten folgt.',
            ],
            [
                'text' => 'Unsere Sessions bauen aufeinander auf, ohne dass wir jeden Moment deines Tages verplanen. Zwischen den gemeinsamen Zeiten bleibt bewusst Raum, damit du das Erlebte auch einfach einmal wirken lassen kannst.',
            ],
        ],
    ],
    'ablauf.image.src' => [
        'group' => '06 Ablauf',
        'label' => 'Bild im Ablaufabschnitt',
        'type' => 'image',
        'hint' => 'Hochformat, Seitenverhältnis 1000 × 1333.',
        'default' => 'assets/img/ablauf.webp',
    ],
    'ablauf.image.alt' => [
        'group' => '06 Ablauf',
        'label' => 'Bildbeschreibung Ablauf',
        'type' => 'text',
        'hint' => 'Alternativtext für Screenreader und Google.',
        'default' => 'Blick von der Terrasse über Felder und Wald',
    ],
    'ablauf.timeline' => [
        'group' => '06 Ablauf',
        'label' => 'Zeitleiste der vier Tage',
        'type' => 'list',
        'hint' => 'Ein Eintrag je Tag, in der Reihenfolge der Anzeige.',
        'itemLabel' => 'tag',
        'fields' => [
            [
                'path' => 'tag',
                'label' => 'Tag',
                'type' => 'text',
                'hint' => 'Wochentag, fett vorangestellt.',
            ],
            [
                'path' => 'text',
                'label' => 'Beschreibung des Tages',
                'type' => 'textarea',
                'hint' => 'Was an diesem Tag passiert, inklusive Uhrzeiten.',
            ],
        ],
        'default' => [
            [
                'tag' => 'Donnerstag',
                'text' => 'Du darfst erst einmal ankommen und den Alltag langsam hinter dir lassen. Anreise ab 16:00 Uhr.',
            ],
            [
                'tag' => 'Freitag',
                'text' => 'Wir tauchen gemeinsam tiefer ein und beschäftigen uns mit deinem Körper, deiner Wahrnehmung und deinen Bedürfnissen.',
            ],
            [
                'tag' => 'Samstag',
                'text' => 'Mit unseren Experiences und rund um den Neumond gehen wir noch einmal tiefer.',
            ],
            [
                'tag' => 'Sonntag',
                'text' => 'Wir lassen die gemeinsamen Tage ruhig ausklingen. Abreise bis 12:00 Uhr.',
            ],
        ],
    ],
    'ablauf.note' => [
        'group' => '06 Ablauf',
        'label' => 'Schlusssatz Ablauf',
        'type' => 'textarea',
        'hint' => 'Hervorgehobener Satz unter der Zeitleiste.',
        'default' => 'Denn am Ende sollst du nicht nach Hause fahren und erst einmal Urlaub von deinem Retreat brauchen.',
    ],
];
