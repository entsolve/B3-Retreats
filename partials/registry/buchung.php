<?php
/* B³ Retreats — Content-Register: Buchung (Abschnitt 16).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'buchung.url' => [
        'group' => '16 Buchung',
        'label' => 'Buchungslink',
        'type' => 'url',
        'hint' => 'Die Adresse, auf die alle Buchungsbuttons der Seite führen — der Link aus Stripe, vollständig mit https://. Solange das Feld leer ist, springen die Buttons nur zum Buchungsblock, damit kein Button ins Leere führt.',
        'default' => '',
    ],
    'buchung.bg.src' => [
        'group' => '16 Buchung',
        'label' => 'Hintergrundbild Buchung',
        'type' => 'image',
        'hint' => 'Dekoratives Hintergrundbild hinter dem Ticket, Bildgröße 1600 × 900 Pixel.',
        'default' => 'assets/img/buchung-bg.webp',
    ],
    'buchung.titel' => [
        'group' => '16 Buchung',
        'label' => 'Ticket-Titel',
        'type' => 'text',
        'hint' => 'Name des Retreats auf dem Ticket.',
        'default' => 'B³ Retreat Oktober 2026',
    ],
    'buchung.ort' => [
        'group' => '16 Buchung',
        'label' => 'Ort',
        'type' => 'text',
        'hint' => 'Ortsangabe unter dem Ticket-Titel.',
        'default' => 'Spabrücken, Rheinland-Pfalz',
    ],
    'buchung.termine' => [
        'group' => '16 Buchung',
        'label' => 'An- und Abreise',
        'type' => 'list',
        'itemLabel' => 'label',
        'fields' => [
            [
                'path' => 'label',
                'label' => 'Bezeichnung',
                'type' => 'text',
                'hint' => 'Zum Beispiel Anreise oder Abreise.',
            ],
            [
                'path' => 'zeit',
                'label' => 'Datum und Uhrzeit',
                'type' => 'text',
                'hint' => 'Wochentag, Datum und Uhrzeit als ein Text.',
            ],
        ],
        'default' => [
            [
                'label' => 'Anreise',
                'zeit' => 'Donnerstag, 08.10.2026 ab 16:00 Uhr',
            ],
            [
                'label' => 'Abreise',
                'zeit' => 'Sonntag, 11.10.2026 bis 12:00 Uhr',
            ],
        ],
    ],
    'buchung.preise' => [
        'group' => '16 Buchung',
        'label' => 'Preise',
        'type' => 'list',
        'itemLabel' => 'label',
        'fields' => [
            [
                'path' => 'label',
                'label' => 'Bezeichnung',
                'type' => 'text',
                'hint' => 'Name der Buchungsvariante, z. B. Shared House.',
            ],
            [
                'path' => 'preis',
                'label' => 'Preis',
                'type' => 'text',
                'hint' => 'Preis inklusive Währungszeichen, z. B. 1.549 €.',
            ],
            [
                'path' => 'hinweis',
                'label' => 'Preis-Zusatz',
                'type' => 'text',
                'hint' => 'Kleiner Zusatz unter dem Preis, z. B. pro Person.',
            ],
            [
                'path' => 'cta',
                'label' => 'Button-Beschriftung',
                'type' => 'text',
                'hint' => 'Text auf dem Buchungsbutton dieser Variante.',
            ],
            [
                'path' => 'url',
                'label' => 'Buchungslink dieser Variante',
                'type' => 'url',
                'hint' => 'Eigener Link für genau diese Variante. Leer lassen, wenn der allgemeine Buchungslink oben gilt.',
            ],
        ],
        'default' => [
            [
                'label' => 'Shared House',
                'preis' => '1.549 €',
                'hinweis' => 'pro Person',
                'cta' => 'Meinen Platz sichern',
                'url' => '',
            ],
            [
                'label' => 'Friends Special',
                'preis' => '3.950 €',
                'hinweis' => 'für 2 Personen',
                'cta' => 'Friends Special sichern',
                'url' => '',
            ],
        ],
    ],
    'buchung.hinweis' => [
        'group' => '16 Buchung',
        'label' => 'Leistungshinweis',
        'type' => 'textarea',
        'hint' => 'Was im Preis enthalten ist, direkt über dem Buchungsbutton.',
        'default' => 'Unterkunft, Verpflegung und alle regulären B³ Experiences inklusive. Ratenzahlung möglich.',
    ],
    'buchung.fine' => [
        'group' => '16 Buchung',
        'label' => 'Rechtlicher Hinweis',
        'type' => 'textarea',
        'hint' => 'Kleingedrucktes zu Zahlungsanbieter, AGB und Stornierung.',
        'default' => 'Zahlung über unseren externen Zahlungsdienstleister Stripe. AGB und Stornierungsbedingungen sind vor Abschluss der Buchung vollständig einsehbar.',
    ],
];
