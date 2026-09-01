<?php
/* B³ Retreats — Content-Register: Termin und Plätze (Abschnitt 24).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'termin.start' => [
        'group' => '24 Termin und Plätze',
        'label' => 'Erster Tag',
        'type' => 'datum',
        'hint' => 'Anreisetag. Alle Datumsangaben auf der Seite rechnen sich hieraus — auch die für Google unsichtbar mitgelieferten.',
        'default' => '2026-10-08',
    ],
    'termin.ende' => [
        'group' => '24 Termin und Plätze',
        'label' => 'Letzter Tag',
        'type' => 'datum',
        'hint' => 'Abreisetag.',
        'default' => '2026-10-11',
    ],
];
