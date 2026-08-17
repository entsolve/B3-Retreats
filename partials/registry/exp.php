<?php
/* B³ Retreats — Content-Register: Experiences (Abschnitt 08).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'exp.kapitel' => [
        'group' => '08 Experiences',
        'label' => 'Kapitelnummer Experiences',
        'type' => 'text',
        'hint' => 'Kleine Randbeschriftung am linken Seitenrand, z.B. "Kapitel 05".',
        'default' => 'Kapitel 05',
    ],
    'exp.eyebrow' => [
        'group' => '08 Experiences',
        'label' => 'Übertitel Experiences',
        'type' => 'text',
        'hint' => 'Kurze Zeile über der Überschrift, z.B. "Das Programm".',
        'default' => 'Das Programm',
    ],
    'exp.headline' => [
        'group' => '08 Experiences',
        'label' => 'Überschrift Experiences',
        'type' => 'text',
        'hint' => 'Hauptüberschrift des Programm-Abschnitts.',
        'default' => 'Deine B³ Experiences',
    ],
    'exp.ruler' => [
        'group' => '08 Experiences',
        'label' => 'Linien-Label Experiences',
        'type' => 'text',
        'hint' => 'Kurzer Text in der Zierlinie unter der Überschrift, z.B. "Vier Zugänge".',
        'default' => 'Vier Zugänge',
    ],
    'exp.intro' => [
        'group' => '08 Experiences',
        'label' => 'Einleitung Experiences',
        'type' => 'textarea',
        'hint' => 'Hervorgehobener Einleitungssatz über den vier Experiences.',
        'default' => 'Unsere Experiences greifen ineinander und begleiten dich über verschiedene Zugänge – über deinen Körper, deine innere Wahrnehmung und deine Gedanken.',
    ],
    'exp.items' => [
        'group' => '08 Experiences',
        'label' => 'Die einzelnen Experiences',
        'type' => 'list',
        'hint' => 'Reihenfolge, Anzahl und Inhalt frei. Die Seite des Bildes bitte abwechseln lassen.',
        'default' => [
            [
                'image' => [
                    'src' => 'assets/img/exp-sarah.webp',
                    'alt' => 'Sarah Bernhard mit gefalteten Händen auf der Terrasse am Feldrand',
                    'height' => 1250,
                ],
                'who' => 'Mit Sarah',
                'title' => 'Yin Yoga &amp; Breathwork',
                'flip' => '',
                'paragraphs' => [
                    [
                        'text' => 'Wir starten gemeinsam mit ruhigem Yin Yoga in den Morgen. Zeit, um im Körper anzukommen, wahrzunehmen und bewusst in den Tag zu starten.',
                    ],
                    [
                        'text' => 'In einer gemeinsamen Breathwork Session schaffen wir Raum, tiefer zu fühlen, wahrzunehmen und dem zu begegnen, was sich gerade zeigen möchte.',
                    ],
                    [
                        'text' => 'Dabei geht es nicht darum, etwas zu erzwingen oder „wegzumachen“, sondern einen Raum zu schaffen, in dem du deinen Körper wieder wahrnehmen, zur Ruhe kommen und dich selbst ein Stück tiefer erfahren kannst.',
                    ],
                ],
                'second' => [
                    'who' => '',
                    'title' => '',
                    'paragraphs' => [],
                ],
            ],
            [
                'image' => [
                    'src' => 'assets/img/exp-sophie.webp',
                    'alt' => 'Sophie Christin Braun am Feldrand im späten Nachmittagslicht',
                    'height' => 1250,
                ],
                'who' => 'Mit Sophie',
                'title' => 'Astro Energy Reading',
                'flip' => ' exp__item--flip',
                'paragraphs' => [
                    [
                        'text' => 'Astrologie ist für Sophie keine Vorhersage, sondern ein Spiegel und eine Erinnerung.',
                    ],
                    [
                        'text' => 'Beim Retreat nimmt sie uns mit in ein Astro Energy Reading zu den aktuellen Energien – unter anderem zur Neumondenergie, zu Jupiter im Löwen sowie Saturn und Neptun im Widder.',
                    ],
                    [
                        'text' => 'Dabei geht es nicht darum, vorherzusagen, was passieren wird, sondern darum, welche Themen und Qualitäten wir gerade bewusst wahrnehmen und für uns nutzen dürfen.',
                    ],
                ],
                'second' => [
                    'who' => 'Mit Sophie',
                    'title' => 'Solfeggio-Frequenz-Erinnerungs-Reise',
                    'paragraphs' => [
                        [
                            'text' => 'Sophie nimmt uns außerdem mit auf eine Reise zum Loslassen, Ankommen und Stillwerden. Begleitet von Solfeggio-Frequenzen und Energiearbeit entsteht ein Raum, in dem du dich zurücklehnen, wahrnehmen und wieder mehr bei dir und in der Verbindung zur Quelle ankommen darfst.',
                        ],
                        [
                            'text' => 'Denn manchmal brauchen wir keine weitere Antwort von außen, sondern die Ruhe, um wieder wahrzunehmen, was in uns längst da ist.',
                        ],
                    ],
                ],
            ],
            [
                'image' => [
                    'src' => 'assets/img/exp-christina.webp',
                    'alt' => 'Christina am Feldrand im späten Nachmittagslicht',
                    'height' => 1250,
                ],
                'who' => 'Mit Christina',
                'title' => 'Mit Struktur zu mehr Leichtigkeit',
                'flip' => ' exp__item--flip',
                'paragraphs' => [
                    [
                        'text' => 'Du kannst alles wollen. Selbstverwirklichung, Business, Familie, Partnerschaft, Zeit für dich, Erfolg, Ruhe und Wachstum.',
                    ],
                    [
                        'text' => 'Die spannende Frage ist nicht, ob all das zusammenpassen darf, sondern wie du dein Leben so gestalten kannst, dass die Dinge, die dir wirklich wichtig sind, darin auch ihren Platz bekommen.',
                    ],
                    [
                        'text' => 'In unserer gemeinsamen Session beschäftigen wir uns deshalb nicht mit starren Regeln, perfekten Routinen oder dem nächsten durchgetakteten Wochenplan. Wir schauen auf eine intuitive und lebendige Struktur, die zu dir, deinen Bedürfnissen und deinem Leben passt.',
                    ],
                    [
                        'text' => 'Eine Struktur, die dich unterstützt, statt dich einzuengen – und dadurch mehr Leichtigkeit möglich macht.',
                    ],
                ],
                'second' => [
                    'who' => '',
                    'title' => '',
                    'paragraphs' => [],
                ],
            ],
        ],
    ],
];
