<?php
/* B³ Retreats — Content-Register: Warteliste (Abschnitt 23).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'warteliste.eyebrow' => [
        'group' => '23 Warteliste',
        'label' => 'Kleine Zeile über der Überschrift',
        'type' => 'text',
        'hint' => 'Text im Warteliste-Abschnitt.',
        'default' => 'Warteliste',
    ],
    'warteliste.headline' => [
        'group' => '23 Warteliste',
        'label' => 'Überschrift',
        'type' => 'text',
        'hint' => 'Text im Warteliste-Abschnitt.',
        'default' => 'Warteliste',
    ],
    'warteliste.intro' => [
        'group' => '23 Warteliste',
        'label' => 'Einleitung',
        'type' => 'textarea',
        'hint' => 'Text im Warteliste-Abschnitt.',
        'default' => 'Trag dich ganz unverbindlich in unsere Warteliste ein und erfahre als Erste, sobald der Termin für das Retreat feststeht.',
    ],
    'warteliste.label_vorname' => [
        'group' => '23 Warteliste',
        'label' => 'Feld: Vorname',
        'type' => 'text',
        'hint' => 'Text im Warteliste-Abschnitt.',
        'default' => 'Vorname',
    ],
    'warteliste.label_nachname' => [
        'group' => '23 Warteliste',
        'label' => 'Feld: Name',
        'type' => 'text',
        'hint' => 'Text im Warteliste-Abschnitt.',
        'default' => 'Name',
    ],
    'warteliste.label_email' => [
        'group' => '23 Warteliste',
        'label' => 'Feld: E-Mail',
        'type' => 'text',
        'hint' => 'Text im Warteliste-Abschnitt.',
        'default' => 'E-Mail-Adresse',
    ],
    'warteliste.label_telefon' => [
        'group' => '23 Warteliste',
        'label' => 'Feld: Telefon',
        'type' => 'text',
        'hint' => 'Das Feld ist freiwillig — der Hinweis darauf gehört in die Beschriftung.',
        'default' => 'Telefonnummer (optional)',
    ],
    'warteliste.label_interesse' => [
        'group' => '23 Warteliste',
        'label' => 'Feld: Interesse',
        'type' => 'text',
        'hint' => 'Text im Warteliste-Abschnitt.',
        'default' => 'Interesse an',
    ],
    'warteliste.label_shared' => [
        'group' => '23 Warteliste',
        'label' => 'Auswahl: erste Variante',
        'type' => 'text',
        'hint' => 'Text im Warteliste-Abschnitt.',
        'default' => 'Shared House',
    ],
    'warteliste.label_friends' => [
        'group' => '23 Warteliste',
        'label' => 'Auswahl: zweite Variante',
        'type' => 'text',
        'hint' => 'Text im Warteliste-Abschnitt.',
        'default' => 'Friends Special',
    ],
    'warteliste.einwilligung' => [
        'group' => '23 Warteliste',
        'label' => 'Einwilligung neben dem Häkchen',
        'type' => 'html',
        'hint' => 'Der Satz neben dem Häkchen. WORTLAUT NICHT ABSCHWÄCHEN: er wird zu jedem Eintrag mitgespeichert und ist der Nachweis, wozu zugestimmt wurde.',
        'default' => 'Ich möchte benachrichtigt werden, sobald der Termin feststeht, und bin damit einverstanden, dass meine Angaben dafür gespeichert werden. Ich kann das jederzeit formlos widerrufen. Mehr dazu in der <a class="link" href="/datenschutz">Datenschutzerklärung</a>.',
    ],
    'warteliste.cta' => [
        'group' => '23 Warteliste',
        'label' => 'Beschriftung des Knopfes',
        'type' => 'text',
        'hint' => 'Text im Warteliste-Abschnitt.',
        'default' => 'Absenden',
    ],
    'warteliste.danke' => [
        'group' => '23 Warteliste',
        'label' => 'Nachricht nach dem Absenden',
        'type' => 'textarea',
        'hint' => 'Erscheint anstelle des Formulars, nachdem jemand sich eingetragen hat.',
        'default' => 'Wie schön, dass du dabei sein möchtest! 🤍

Du stehst jetzt ganz unverbindlich auf unserer Warteliste. Sobald der Termin für das Retreat feststeht, erfährst du als eine der Ersten davon.

Wir freuen uns schon sehr auf alles, was kommt – und vielleicht sehen wir uns ganz bald beim Retreat!',
    ],
    'warteliste.fehler' => [
        'group' => '23 Warteliste',
        'label' => 'Nachricht, wenn etwas fehlt',
        'type' => 'textarea',
        'hint' => 'Erscheint, wenn Pflichtangaben fehlen. Das Formular bleibt daneben stehen.',
        'default' => 'Da hat leider etwas nicht geklappt. Bitte prüf noch einmal, ob Vor- und Nachname, eine gültige E-Mail-Adresse und das Häkchen zur Einwilligung gesetzt sind.',
    ],
    'warteliste.pruefen' => [
        'group' => '23 Warteliste',
        'label' => 'Nachricht: bitte Postfach prüfen',
        'type' => 'textarea',
        'hint' => 'Erscheint anstelle des Formulars.',
        'default' => 'Fast geschafft! Wir haben dir eine E-Mail geschickt.

Bitte klick auf den Bestätigungslink darin – erst danach stehst du auf der Warteliste. Schau notfalls kurz im Spam-Ordner nach.',
    ],
    'warteliste.link_ungueltig' => [
        'group' => '23 Warteliste',
        'label' => 'Nachricht: Link gilt nicht mehr',
        'type' => 'textarea',
        'hint' => 'Erscheint, wenn jemand einen alten oder schon benutzten Bestätigungslink öffnet.',
        'default' => 'Dieser Bestätigungslink gilt leider nicht mehr – er ist entweder abgelaufen oder wurde bereits verwendet. Trag dich einfach noch einmal ein, dann schicken wir dir einen neuen.',
    ],
];
