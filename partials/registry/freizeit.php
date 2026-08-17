<?php
/* B³ Retreats — Content-Register: Freie Zeit (Abschnitt 09).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'freizeit.frieze.src' => [
        'group' => '09 Freie Zeit',
        'label' => 'Bildstreifen über "Freie Zeit" – Bild',
        'type' => 'image',
        'hint' => 'Breites Panoramabild 1920×800 px über dem Abschnitt Freie Zeit.',
        'default' => 'assets/img/fries-hof.webp',
    ],
    'freizeit.frieze.alt' => [
        'group' => '09 Freie Zeit',
        'label' => 'Bildstreifen über "Freie Zeit" – Alternativtext',
        'type' => 'text',
        'hint' => 'Beschreibung des Panoramabildes.',
        'default' => 'Zufahrt und Hof des Anwesens, gesäumt von Zypressen',
    ],
    'freizeit.kapitel' => [
        'group' => '09 Freie Zeit',
        'label' => 'Kapitelnummer Freie Zeit',
        'type' => 'text',
        'hint' => 'Randbeschriftung, z.B. "Kapitel 06".',
        'default' => 'Kapitel 06',
    ],
    'freizeit.eyebrow' => [
        'group' => '09 Freie Zeit',
        'label' => 'Übertitel Freie Zeit',
        'type' => 'text',
        'hint' => 'Kurze Zeile über der Überschrift, z.B. "Raum dazwischen".',
        'default' => 'Raum dazwischen',
    ],
    'freizeit.headline' => [
        'group' => '09 Freie Zeit',
        'label' => 'Überschrift Freie Zeit',
        'type' => 'text',
        'hint' => 'Hauptüberschrift des Abschnitts zur freien Zeit.',
        'default' => 'Genug Zeit für dich',
    ],
    'freizeit.p1' => [
        'group' => '09 Freie Zeit',
        'label' => 'Freie Zeit – Absatz 1',
        'type' => 'textarea',
        'hint' => 'Erster Fließtextabsatz.',
        'default' => 'Zu einem Retreat gehört für uns nicht nur das, was wir gemeinsam machen, sondern genauso die Zeit dazwischen.',
    ],
    'freizeit.p2' => [
        'group' => '09 Freie Zeit',
        'label' => 'Freie Zeit – Absatz 2',
        'type' => 'textarea',
        'hint' => 'Zweiter Fließtextabsatz.',
        'default' => 'Deshalb planen wir bewusst längere Pausen und freie Zeit ein. Du kannst durch Wald und Felder spazieren, dich mit einem Buch zurückziehen, deine Gedanken aufschreiben, Zeit mit den anderen Frauen verbringen oder einfach irgendwo auf dem Gelände sitzen und nichts tun.',
    ],
    'freizeit.pullout' => [
        'group' => '09 Freie Zeit',
        'label' => 'Freie Zeit – Hervorgehobener Satz',
        'type' => 'text',
        'hint' => 'Optisch hervorgehobene Kernaussage zwischen den Absätzen.',
        'default' => 'Alle unsere Experiences sind freiwillig.',
    ],
    'freizeit.p3' => [
        'group' => '09 Freie Zeit',
        'label' => 'Freie Zeit – Absatz 3',
        'type' => 'textarea',
        'hint' => 'Absatz nach der hervorgehobenen Aussage.',
        'default' => 'Wenn du an einem Morgen länger schlafen möchtest oder während einer Session merkst, dass du gerade lieber für dich sein möchtest, ist das genauso in Ordnung.',
    ],
    'freizeit.small' => [
        'group' => '09 Freie Zeit',
        'label' => 'Freie Zeit – Hinweis (klein)',
        'type' => 'textarea',
        'hint' => 'Kleingedruckter Zusatzhinweis, z.B. zu 1:1 Sessions gegen Aufpreis.',
        'default' => 'Während ausgewählter freier Zeitfenster kannst du außerdem persönliche 1:1 Sessions mit Sophie, Sarah oder Christina gegen Aufpreis buchen.',
    ],
    'freizeit.cta' => [
        'group' => '09 Freie Zeit',
        'label' => 'Freie Zeit – Button-Beschriftung',
        'type' => 'text',
        'hint' => 'Text des Buttons, der zum Buchungsabschnitt führt.',
        'default' => 'Ich möchte dabei sein',
    ],
    'freizeit.belt' => [
        'group' => '09 Freie Zeit',
        'label' => 'Zitatband nach Freie Zeit',
        'type' => 'text',
        'hint' => 'Breite, zitatartige Zeile zwischen den Abschnitten.',
        'default' => 'Du kommst an. Du musst nichts mitbringen außer dir selbst.',
    ],
];
