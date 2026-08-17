<?php
/* B³ Retreats — Content-Register: Mobile Buchungsleiste (Abschnitt 20).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'bar.price' => [
        'group' => '20 Mobile Buchungsleiste',
        'label' => 'Mobile Leiste: Preisangabe',
        'type' => 'text',
        'hint' => 'Kurzer Preistext in der festen Leiste am unteren Bildschirmrand (mobil). Hier steht ein echtes Eurozeichen.',
        'default' => 'Shared House ab 1.549 €',
    ],
    'bar.cta' => [
        'group' => '20 Mobile Buchungsleiste',
        'label' => 'Mobile Leiste: Knopfbeschriftung',
        'type' => 'text',
        'hint' => 'Sehr kurzer Knopftext in der mobilen Leiste, moeglichst unter 20 Zeichen.',
        'default' => 'Platz sichern',
    ],
];
