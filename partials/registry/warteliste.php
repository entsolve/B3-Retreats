<?php
/* B³ Retreats — Content-Register: Warteliste (Abschnitt 23).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'warteliste.statt_buchung' => [
        'group' => '23 Warteliste',
        'label' => 'Warteliste statt Buchung',
        'type' => 'schalter',
        'hint' => 'Angehakt führen ALLE Knöpfe der Seite zum Wartelisten-Formular, auch wenn ein Buchungslink hinterlegt ist. Ohne Haken entscheidet die Seite selbst: solange ein Buchungslink da ist und Plätze frei sind, wird gebucht — sonst geht es ebenfalls zur Warteliste.',
        'default' => '1',
    ],
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
    'warteliste.cta_knoepfe' => [
        'group' => '23 Warteliste',
        'label' => 'Beschriftung der Buchungsknöpfe, solange nicht gebucht werden kann',
        'type' => 'text',
        'hint' => 'NICHT der Knopf unter dem Formular — das ist das Feld darüber. Dieser Text ersetzt die Beschriftung ALLER Buchungsknöpfe der Seite, sobald kein Buchungslink hinterlegt oder alles ausgebucht ist: sie führen dann hierher und sollten nicht weiter „Meinen Platz sichern“ versprechen. Leer lassen behält die ursprünglichen Beschriftungen.',
        'default' => 'Auf die Warteliste',
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
    'warteliste.panne' => [
        'group' => '23 Warteliste',
        'label' => 'Nachricht bei einer technischen Störung',
        'type' => 'textarea',
        'hint' => 'Erscheint, wenn die Eingabe in Ordnung war und trotzdem etwas schiefging — Datenbank oder E-Mail-Versand. Der Besucherin dann „prüf deine Angaben“ zu sagen, wäre schlicht falsch: sie hat alles richtig gemacht.',
        'default' => 'Das lag diesmal nicht an dir — bei uns ist gerade etwas schiefgelaufen. Bitte versuch es später noch einmal oder schreib uns kurz an hello@b3-retreats.de, dann tragen wir dich von Hand ein.',
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
