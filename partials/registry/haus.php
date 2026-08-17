<?php
/* B³ Retreats — Content-Register: Unterkunft und Preise (Abschnitt 12).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'haus.frieze.image.src' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Bildstreifen Unterkunft',
        'type' => 'image',
        'hint' => 'Breites Bild über dem Abschnitt Unterbringung (Querformat 3:2, 1600x1067)',
        'default' => 'assets/img/haus-fries.webp',
    ],
    'haus.frieze.image.alt' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Bildbeschreibung Bildstreifen',
        'type' => 'text',
        'hint' => 'Alternativtext für Screenreader und Suchmaschinen',
        'default' => 'Gemeinschaftsbereich mit Essplatz und Sitzlandschaft',
    ],
    'haus.rail' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Kapitelmarke',
        'type' => 'text',
        'hint' => 'Seitliche Kapitelnummer, z. B. „Kapitel 09“',
        'default' => 'Kapitel 09',
    ],
    'haus.eyebrow' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Überzeile',
        'type' => 'text',
        'hint' => 'Kleine Zeile über der Überschrift',
        'default' => 'Unterbringung &amp; Preise',
    ],
    'haus.headline' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Überschrift',
        'type' => 'text',
        'hint' => 'Hauptüberschrift des Abschnitts Unterbringung & Preise',
        'default' => 'Dein Zuhause für diese vier Tage',
    ],
    'haus.sub' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Einleitungssatz',
        'type' => 'textarea',
        'hint' => 'Kurzer Satz unter der Überschrift, erklärt die zwei Wohnoptionen',
        'default' => 'Du kannst zwischen zwei Möglichkeiten der Unterbringung wählen.',
    ],
    'haus.shared.image.src' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Bild Shared House',
        'type' => 'image',
        'hint' => 'Hochformat 900x1125, zeigt das Gemeinschaftshaus',
        'default' => 'assets/img/haus-schlafen.webp',
    ],
    'haus.shared.image.alt' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Bildbeschreibung Shared House',
        'type' => 'text',
        'hint' => 'Alternativtext des Bildes',
        'default' => 'Schlafzimmer mit bodentiefem Fenster',
    ],
    'haus.shared.label' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Name der Option',
        'type' => 'text',
        'hint' => 'Bezeichnung der Unterbringung, z. B. „Shared House“',
        'default' => 'Shared House',
    ],
    'haus.shared.headline' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Überschrift Shared House',
        'type' => 'text',
        'hint' => 'Kurze Zeile, die die Option beschreibt',
        'default' => 'Gemeinsam wohnen, gemeinsam erleben.',
    ],
    'haus.shared.text' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Beschreibung Shared House',
        'type' => 'textarea',
        'hint' => 'Fließtext zu Größe, Einrichtung und Personenzahl',
        'default' => 'Unser ca. 150 m² großer Neubau verbindet moderne Einrichtung mit rustikaler Holzoptik und bietet Platz für bis zu 11 Frauen.',
    ],
    'haus.shared.feats' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Ausstattung Shared House',
        'type' => 'list',
        'itemLabel' => 'text',
        'fields' => [
            [
                'path' => 'text',
                'label' => 'Ausstattungspunkt',
                'type' => 'text',
                'hint' => 'Ein Stichpunkt der Ausstattungsliste, z. B. „4 Schlafzimmer“',
            ],
        ],
        'default' => [
            [
                'text' => '4 Schlafzimmer',
            ],
            [
                'text' => '2- und 3-Bettzimmer mit Einzelbetten',
            ],
            [
                'text' => 'Gemeinschaftsraum',
            ],
            [
                'text' => 'Küchenzeile',
            ],
            [
                'text' => 'Badezimmer',
            ],
            [
                'text' => 'separates WC',
            ],
        ],
    ],
    'haus.shared.note' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Hinweis Shared House',
        'type' => 'textarea',
        'hint' => 'Kleingedruckter Hinweis unter der Ausstattungsliste (Zimmerzuteilung)',
        'default' => 'Die Zimmer und Betten werden von uns zugeteilt. Eine Vorauswahl ist nicht möglich.',
    ],
    'haus.shared.galerie' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Shared House — Bildergalerie',
        'type' => 'list',
        'hint' => 'Reihenfolge und Anzahl frei. Beim Klick öffnet sich das Bild groß.',
        'itemLabel' => 'alt',
        'fields' => [
            [
                'path' => 'src',
                'label' => 'Bild',
                'type' => 'image',
                'hint' => 'Querformat 3:2 — im Reiter „Bilder“ anlegen.',
            ],
            [
                'path' => 'alt',
                'label' => 'Bildbeschreibung',
                'type' => 'text',
                'hint' => 'Welcher Raum ist zu sehen? Steht auch als Beschriftung in der Lupe.',
            ],
        ],
        'default' => [
            [
                'src' => 'assets/img/haus-g1.webp',
                'alt' => 'Schlafzimmer mit gepolstertem Kopfteil und bodentiefem Fenster',
            ],
            [
                'src' => 'assets/img/haus-g2.webp',
                'alt' => 'Zweites Schlafzimmer mit Blick ins Grüne',
            ],
            [
                'src' => 'assets/img/haus-g3.webp',
                'alt' => 'Gemeinschaftsraum mit Küchenzeile, Essplatz und Sitzlandschaft',
            ],
            [
                'src' => 'assets/img/haus-g4.webp',
                'alt' => 'Badezimmer mit ebenerdiger Dusche',
            ],
        ],
    ],
    'haus.shared.price' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Preis Shared House',
        'type' => 'text',
        'hint' => 'Preisangabe, geschütztes Leerzeichen &nbsp; vor dem €-Zeichen beibehalten',
        'default' => '1.549&nbsp;€',
    ],
    'haus.shared.price_note' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Preiszusatz Shared House',
        'type' => 'textarea',
        'hint' => 'Kleintext unter dem Preis: worauf sich der Preis bezieht und was inklusive ist',
        'default' => 'pro Person &middot; 3 Übernachtungen, Verpflegung und alle regulären B³ Experiences inklusive. Ratenzahlung möglich.',
    ],
    'haus.shared.cta' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Button Shared House',
        'type' => 'text',
        'hint' => 'Beschriftung des Buchungsbuttons',
        'default' => 'Meinen Platz sichern',
    ],
    'haus.friends.image.src' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Bild Friends Special',
        'type' => 'image',
        'hint' => 'Hochformat 900x1125, zeigt das exklusive Apartment',
        'default' => 'assets/img/haus-wohnen.webp',
    ],
    'haus.friends.image.alt' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Bildbeschreibung Friends Special',
        'type' => 'text',
        'hint' => 'Alternativtext des Bildes',
        'default' => 'Wohnbereich mit Vorhängen und Tageslicht',
    ],
    'haus.friends.label' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Name der Option',
        'type' => 'text',
        'hint' => 'Bezeichnung der Unterbringung, z. B. „Friends Special“',
        'default' => 'Friends Special',
    ],
    'haus.friends.badge' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Auszeichnung',
        'type' => 'text',
        'hint' => 'Kleiner Zusatz neben dem Namen, z. B. „Exklusiv“',
        'default' => 'Exklusiv',
    ],
    'haus.friends.headline' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Überschrift Friends Special',
        'type' => 'text',
        'hint' => 'Kurze Zeile, die die Option beschreibt',
        'default' => 'Euer eigener Rückzugsort.',
    ],
    'haus.friends.text' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Beschreibung Friends Special',
        'type' => 'textarea',
        'hint' => 'Fließtext zum Apartment für zwei Personen',
        'default' => 'Wenn ihr zu zweit kommt und euch während des Retreats mehr Privatsphäre gönnen möchtet, könnt ihr unser exklusives Apartment buchen. Auf ca. 150 m² habt ihr jede Menge Platz nur für euch und einen wunderschönen Blick über die Landschaft.',
    ],
    'haus.friends.feats' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Ausstattung Friends Special',
        'type' => 'list',
        'itemLabel' => 'text',
        'fields' => [
            [
                'path' => 'text',
                'label' => 'Ausstattungspunkt',
                'type' => 'text',
                'hint' => 'Ein Stichpunkt der Ausstattungsliste, z. B. „großer Balkon mit weitem Ausblick“',
            ],
        ],
        'default' => [
            [
                'text' => 'Schlafzimmer mit Doppelbett',
            ],
            [
                'text' => 'großzügiges Wohnzimmer',
            ],
            [
                'text' => 'eigene Küchenzeile',
            ],
            [
                'text' => 'Badezimmer',
            ],
            [
                'text' => 'separates WC',
            ],
            [
                'text' => 'großer Balkon mit weitem Ausblick',
            ],
        ],
    ],
    'haus.friends.note' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Hinweis Friends Special',
        'type' => 'textarea',
        'hint' => 'Kleingedruckter Hinweis unter der Ausstattungsliste (Teilnahme an gemeinsamen Programmpunkten)',
        'default' => 'Ihr seid bei allen gemeinsamen Experiences und Mahlzeiten dabei und könnt euch zwischendurch jederzeit in eure eigenen vier Wände zurückziehen.',
    ],
    'haus.friends.galerie' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Friends Special — Bildergalerie',
        'type' => 'list',
        'hint' => 'Reihenfolge und Anzahl frei. Beim Klick öffnet sich das Bild groß.',
        'itemLabel' => 'alt',
        'fields' => [
            [
                'path' => 'src',
                'label' => 'Bild',
                'type' => 'image',
                'hint' => 'Querformat 3:2 — im Reiter „Bilder“ anlegen.',
            ],
            [
                'path' => 'alt',
                'label' => 'Bildbeschreibung',
                'type' => 'text',
                'hint' => 'Welcher Raum ist zu sehen? Steht auch als Beschriftung in der Lupe.',
            ],
        ],
        'default' => [
            [
                'src' => 'assets/img/friends-g1.webp',
                'alt' => 'Schlafzimmer des Apartments mit Doppelbett',
            ],
            [
                'src' => 'assets/img/friends-g2.webp',
                'alt' => 'Wohnzimmer des Apartments mit eigener Küchenzeile',
            ],
            [
                'src' => 'assets/img/friends-g3.webp',
                'alt' => 'Balkon mit weitem Blick über die Landschaft',
            ],
            [
                'src' => 'assets/img/friends-g4.webp',
                'alt' => 'Überdachter Außenbereich am Apartment',
            ],
        ],
    ],
    'haus.friends.price' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Preis Friends Special',
        'type' => 'text',
        'hint' => 'Preisangabe, geschütztes Leerzeichen &nbsp; vor dem €-Zeichen beibehalten',
        'default' => '3.950&nbsp;€',
    ],
    'haus.friends.price_note' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Preiszusatz Friends Special',
        'type' => 'textarea',
        'hint' => 'Kleintext unter dem Preis: worauf sich der Preis bezieht und was inklusive ist',
        'default' => 'für 2 Personen &middot; 3 Übernachtungen, Verpflegung und alle regulären B³ Experiences inklusive. Ratenzahlung möglich.',
    ],
    'haus.friends.cta' => [
        'group' => '12 Unterkunft und Preise',
        'label' => 'Button Friends Special',
        'type' => 'text',
        'hint' => 'Beschriftung des Buchungsbuttons',
        'default' => 'Friends Special sichern',
    ],
];
