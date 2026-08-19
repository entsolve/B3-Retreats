<?php
/* B³ Retreats — Content-Register: Aufmacher (Abschnitt 03).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'hero.meta' => [
        'group' => '03 Aufmacher',
        'label' => 'Termin und Ort (kleine Zeile oben)',
        'type' => 'html',
        'hint' => 'Steht über der Hauptüberschrift. Das Trennzeichen &nbsp;|&nbsp; bitte unverändert lassen.',
        'default' => '08.–11. Oktober 2026 &nbsp;|&nbsp; Spabrücken',
    ],
    'hero.headline' => [
        'group' => '03 Aufmacher',
        'label' => 'Hauptüberschrift (H1)',
        'type' => 'html',
        'hint' => 'Der große Claim im Startbild. &nbsp; hält \'be you\' zusammen in einer Zeile.',
        'default' => 'Be free to be&nbsp;you.',
    ],
    'hero.lead' => [
        'group' => '03 Aufmacher',
        'label' => 'Einleitungssatz im Startbild',
        'type' => 'textarea',
        'hint' => 'Ein bis zwei Sätze direkt unter der Hauptüberschrift.',
        'default' => 'Vier Tage zurück zu dir und dem, was du wirklich willst.',
    ],
    'hero.cta.label' => [
        'group' => '03 Aufmacher',
        'label' => 'Beschriftung Buchungs-Button',
        'type' => 'text',
        'hint' => 'Text des Buttons, der zum Buchungsabschnitt springt. Kurz halten.',
        'default' => 'Meinen Platz sichern',
    ],
    'hero.cta.note' => [
        'group' => '03 Aufmacher',
        'label' => 'Hinweis unter dem Button',
        'type' => 'html',
        'hint' => 'Kurzer Zusatz zu Plätzen und Zahlung. &middot; ist das Trennzeichen.',
        'default' => 'Max. 11 Frauen &middot; 3 Coaches &middot; Ratenzahlung möglich',
    ],
    'hero.image.wide.src' => [
        'group' => '03 Aufmacher',
        'label' => 'Startbild mobil (quadratischer Ausschnitt)',
        'type' => 'image',
        'hint' => 'Wird auf Bildschirmen unter 860 px angezeigt. Der Rahmen ist dort quadratisch — am besten ein hochformatiges oder quadratisches Bild, die Personen mittig.',
        'default' => 'assets/img/hero-tall.webp',
    ],
    'hero.image.tall.src' => [
        'group' => '03 Aufmacher',
        'label' => 'Startbild Desktop (Hochformat)',
        'type' => 'image',
        'hint' => 'Hauptbild im Startbereich. Seitenverhältnis 1500 × 2000.',
        'default' => 'assets/img/hero-tall.webp',
    ],
    'hero.image.tall.alt' => [
        'group' => '03 Aufmacher',
        'label' => 'Bildbeschreibung Startbild',
        'type' => 'text',
        'hint' => 'Alternativtext für Screenreader und Google. Beschreibt, was zu sehen ist.',
        'default' => 'Drei Frauen von hinten am Feldrand oberhalb des Retreat-Anwesens bei Spabrücken',
    ],
    'hero.belt' => [
        'group' => '03 Aufmacher',
        'label' => 'Laufband unter dem Startbild',
        'type' => 'html',
        'hint' => 'Drei Eckdaten in einer Zeile, getrennt durch &nbsp;·&nbsp;.',
        'default' => 'Vier Tage &nbsp;·&nbsp; Max. 11 Frauen &nbsp;·&nbsp; 3 Coaches &nbsp;·&nbsp; Spabrücken, Rheinland-Pfalz',
    ],
];
