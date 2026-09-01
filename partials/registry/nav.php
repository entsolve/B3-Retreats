<?php
/* B³ Retreats — Content-Register: Navigation (Abschnitt 02).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'nav.programm' => [
        'group' => '02 Navigation',
        'label' => 'Menüpunkt 01',
        'type' => 'text',
        'hint' => 'Beschriftung des ersten Navigationspunktes (führt zum Programm). Ein bis zwei Wörter.',
        'default' => 'Programm',
    ],
    'nav.unterkunft' => [
        'group' => '02 Navigation',
        'label' => 'Menüpunkt 02',
        'type' => 'text',
        'hint' => 'Beschriftung des zweiten Navigationspunktes (führt zu Unterkunft und Preisen). Ein Und-Zeichen muss als &amp; geschrieben werden.',
        'default' => 'Unterkunft &amp; Preise',
    ],
    'nav.team' => [
        'group' => '02 Navigation',
        'label' => 'Menüpunkt 03',
        'type' => 'text',
        'hint' => 'Beschriftung des dritten Navigationspunktes (führt zur Vorstellung des Teams).',
        'default' => 'Über uns',
    ],
    'nav.cta' => [
        'group' => '02 Navigation',
        'label' => 'Button im Menü',
        'type' => 'text',
        'hint' => 'Beschriftung des hervorgehobenen Buttons im Menü, der zum Buchungsformular springt. Kurz halten, maximal drei Wörter.',
        'default' => 'Platz sichern',
    ],
    'nav.meta.dates' => [
        'group' => '02 Navigation',
        'label' => 'Termin im Menü',
        'type' => 'text',
        'hint' => 'Datumszeile unter den Menüpunkten. Der Gedankenstrich ist ein Halbgeviertstrich (–). Verfügbare Marken: {datum} = 08.–11. Oktober 2026 · {monat} = Oktober 2026 · {start} / {ende} = 08.10.2026 · {start_tag} / {ende_tag} = Donnerstag · {start_iso} / {ende_iso} = 2026-10-08. Sie werden beim Anzeigen durch den Termin aus Abschnitt „24 Termin und Plätze“ ersetzt.',
        'default' => '{datum}',
    ],
    'nav.meta.location' => [
        'group' => '02 Navigation',
        'label' => 'Ort im Menü',
        'type' => 'text',
        'hint' => 'Ortszeile unter den Menüpunkten, z. B. Spabrücken, Rheinland-Pfalz.',
        'default' => 'Spabrücken, Rheinland-Pfalz',
    ],
];
