<?php
/* B³ Retreats — Content-Register: Kulinarik (Abschnitt 11).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'kulinarik.kapitel' => [
        'group' => '11 Kulinarik',
        'label' => 'Kapitelnummer Kulinarik',
        'type' => 'text',
        'hint' => 'Randbeschriftung, z.B. "Kapitel 08".',
        'default' => 'Kapitel 08',
    ],
    'kulinarik.eyebrow' => [
        'group' => '11 Kulinarik',
        'label' => 'Übertitel Kulinarik',
        'type' => 'text',
        'hint' => 'Kurze Zeile über der Überschrift. "&" muss als &amp; geschrieben werden.',
        'default' => 'Frühstück &amp; Holzgrill',
    ],
    'kulinarik.headline' => [
        'group' => '11 Kulinarik',
        'label' => 'Überschrift Kulinarik',
        'type' => 'text',
        'hint' => 'Hauptüberschrift des Essens-Abschnitts.',
        'default' => 'Gutes Essen und lange Abende',
    ],
    'kulinarik.ruler' => [
        'group' => '11 Kulinarik',
        'label' => 'Linien-Label Kulinarik',
        'type' => 'text',
        'hint' => 'Kurzer Text in der Zierlinie, z.B. "Am Tisch".',
        'default' => 'Am Tisch',
    ],
    'kulinarik.p1' => [
        'group' => '11 Kulinarik',
        'label' => 'Kulinarik – Absatz 1',
        'type' => 'textarea',
        'hint' => 'Absatz zum Frühstück.',
        'default' => 'Freitag, Samstag und Sonntag starten wir gemeinsam mit einem reichhaltigen Frühstück in den Tag. Es gibt unter anderem frische Brötchen, Aufschnitt, Obst, Porridge und weitere warme Speisen.',
    ],
    'kulinarik.p2' => [
        'group' => '11 Kulinarik',
        'label' => 'Kulinarik – Absatz 2',
        'type' => 'textarea',
        'hint' => 'Absatz zu Snacks zwischendurch.',
        'default' => 'Auch zwischendurch steht im Haus immer etwas zum Snacken bereit.',
    ],
    'kulinarik.p3' => [
        'group' => '11 Kulinarik',
        'label' => 'Kulinarik – Absatz 3',
        'type' => 'textarea',
        'hint' => 'Absatz zum Abendessen vom Holzgrill.',
        'default' => 'Am Abend kommen wir wieder gemeinsam zum Essen zusammen. Wenn das Wetter mitspielt, sitzen wir draußen auf der Holzterrasse, essen frisch zubereitete Speisen vom Holzgrill und lassen den Tag gemeinsam ausklingen.',
    ],
    'kulinarik.tagline' => [
        'group' => '11 Kulinarik',
        'label' => 'Kulinarik – Schlusszeile',
        'type' => 'text',
        'hint' => 'Hervorgehobene Zeile am Ende, z.B. Hinweis auf vegetarische Verpflegung.',
        'default' => 'Bei der Buchung wählbar: normale oder vegetarische Verpflegung',
    ],
];
