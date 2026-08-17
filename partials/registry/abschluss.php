<?php
/* B³ Retreats — Content-Register: Abschluss (Abschnitt 18).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'abschluss.bg.src' => [
        'group' => '18 Abschluss',
        'label' => 'Hintergrundbild Abschluss',
        'type' => 'image',
        'hint' => 'Grossflaechiges Bild hinter dem Schlussabschnitt. Pfad relativ zur Startseite, z. B. assets/img/abschluss.webp. Format 16:9 (1920x1080).',
        'default' => 'assets/img/abschluss.webp',
    ],
    'abschluss.eyebrow' => [
        'group' => '18 Abschluss',
        'label' => 'Kleine Zeile ueber der Ueberschrift',
        'type' => 'text',
        'hint' => 'Kurzer Vorspann ueber der Schlussueberschrift, wenige Woerter.',
        'default' => 'Be free',
    ],
    'abschluss.headline' => [
        'group' => '18 Abschluss',
        'label' => 'Schlussueberschrift',
        'type' => 'text',
        'hint' => 'Die grosse Aussage am Ende der Seite.',
        'default' => 'Be free to be you.',
    ],
    'abschluss.lines' => [
        'group' => '18 Abschluss',
        'label' => 'Schlussabsaetze (normal)',
        'type' => 'list',
        'hint' => 'Die ruhigen Absaetze vor der Schlusspointe. Reihenfolge frei, Anzahl beliebig.',
        'default' => [
            [
                'text' => 'Vielleicht geht es in diesen vier Tagen gar nicht darum, Antworten auf jede Frage zu finden.',
            ],
            [
                'text' => 'Vielleicht geht es darum, dir genügend Zeit zu geben, damit du die richtigen Fragen überhaupt wieder hören kannst.',
            ],
            [
                'text' => 'Zeit für deinen Körper, deine Gedanken, deine Wünsche und all das, was im Alltag manchmal ein bisschen zu kurz kommt.',
            ],
        ],
    ],
    'abschluss.lines_big' => [
        'group' => '18 Abschluss',
        'label' => 'Schlussabsaetze (hervorgehoben)',
        'type' => 'list',
        'hint' => 'Die letzten, groesser gesetzten Saetze des Abschnitts.',
        'default' => [
            [
                'text' => 'Und vielleicht fährst du am Sonntag nicht als eine andere Frau nach Hause.',
            ],
            [
                'text' => 'Sondern einfach ein bisschen mehr als du selbst.',
            ],
        ],
    ],
    'abschluss.cta' => [
        'group' => '18 Abschluss',
        'label' => 'Knopfbeschriftung Abschluss',
        'type' => 'text',
        'hint' => 'Text des Buchungsknopfes am Seitenende, z. B. \'Ich bin dabei\'.',
        'default' => 'Ich bin dabei',
    ],
    'abschluss.stamp' => [
        'group' => '18 Abschluss',
        'label' => 'Eckdaten neben dem Knopf',
        'type' => 'text',
        'hint' => 'Kurzzeile mit Name, Datum und Ort. Trennzeichen bitte als &middot; schreiben.',
        'default' => 'B³ Retreat &middot; 08.–11. Oktober 2026 &middot; Spabrücken',
    ],
];
