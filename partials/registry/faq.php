<?php
/* B³ Retreats — Content-Register: Häufige Fragen (Abschnitt 17).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'faq.rail' => [
        'group' => '17 Häufige Fragen',
        'label' => 'Kapitelmarke',
        'type' => 'text',
        'hint' => 'Kleine Kapitelnummer am Seitenrand, z. B. Kapitel 12.',
        'default' => 'Kapitel 12',
    ],
    'faq.eyebrow' => [
        'group' => '17 Häufige Fragen',
        'label' => 'Übertitel',
        'type' => 'text',
        'hint' => 'Kleine Zeile über der Abschnittsüberschrift.',
        'default' => 'Gut zu wissen',
    ],
    'faq.headline' => [
        'group' => '17 Häufige Fragen',
        'label' => 'Überschrift',
        'type' => 'text',
        'hint' => 'Hauptüberschrift des FAQ-Abschnitts.',
        'default' => 'Häufige Fragen',
    ],
    'faq.spalte1' => [
        'group' => '17 Häufige Fragen',
        'label' => 'Fragen – linke Spalte',
        'type' => 'list',
        'itemLabel' => 'frage',
        'fields' => [
            [
                'path' => 'frage',
                'label' => 'Frage',
                'type' => 'text',
                'hint' => 'Sichtbare Frage, die aufgeklappt werden kann.',
            ],
            [
                'path' => 'antwort',
                'label' => 'Antwort',
                'type' => 'html',
                'hint' => 'Antworttext. Jeder Absatz steht in eigenen <p>-Tags, Links sind erlaubt.',
            ],
        ],
        'default' => [
            [
                'frage' => 'Muss ich an allen Experiences teilnehmen?',
                'antwort' => '<p>Nein. Alle Experiences sind freiwillig. Wenn du eine Session auslassen und stattdessen spazieren gehen, schlafen, lesen oder einfach Zeit für dich verbringen möchtest, ist das vollkommen in Ordnung.</p>',
            ],
            [
                'frage' => 'Gibt es genügend Freizeit?',
                'antwort' => '<p>Ja. Wir planen bewusst freie Zeiten zwischen den gemeinsamen Experiences ein, damit du das Erlebte wirken lassen und das Retreat in deinem eigenen Tempo genießen kannst.</p>',
            ],
            [
                'frage' => 'Kann ich eine persönliche 1:1 Session buchen?',
                'antwort' => '<p>Ja. In ausgewählten freien Zeitfenstern kannst du zusätzliche 1:1 Sessions mit Sophie, Sarah oder Christina buchen. Diese sind nicht im Retreat-Preis enthalten und werden separat berechnet.</p>',
            ],
            [
                'frage' => 'Ist das Retreat ausschließlich für Frauen?',
                'antwort' => '<p>Ja. Dieses B³ Retreat richtet sich ausschließlich an Frauen.</p>',
            ],
            [
                'frage' => 'Ist die Verpflegung inklusive?',
                'antwort' => '<p>Ja. Frühstück, gemeinsame Abendessen und Snacks für zwischendurch sind inklusive. Bei der Buchung kannst du zwischen normaler und vegetarischer Verpflegung wählen.</p>',
            ],
            [
                'frage' => 'Was ist bei Allergien oder Unverträglichkeiten?',
                'antwort' => '
            <p>Wir können keine individuell allergenfreie Zubereitung und keinen vollständigen Ausschluss von Kreuzkontaminationen gewährleisten.</p>
            <p>Wenn du Allergien oder Unverträglichkeiten hast, informiere uns bitte vorab und achte vor Ort eigenverantwortlich darauf, welche Lebensmittel für dich geeignet sind.</p>
          ',
            ],
            [
                'frage' => 'Kann ich mein Zimmer auswählen?',
                'antwort' => '<p>Nein. Im Shared House erfolgt die Unterbringung in 2- und 3-Bettzimmern mit Einzelbetten. Die Zimmer und Betten werden von uns zugeteilt.</p>',
            ],
        ],
    ],
    'faq.spalte2' => [
        'group' => '17 Häufige Fragen',
        'label' => 'Fragen – rechte Spalte',
        'type' => 'list',
        'itemLabel' => 'frage',
        'fields' => [
            [
                'path' => 'frage',
                'label' => 'Frage',
                'type' => 'text',
                'hint' => 'Sichtbare Frage, die aufgeklappt werden kann.',
            ],
            [
                'path' => 'antwort',
                'label' => 'Antwort',
                'type' => 'html',
                'hint' => 'Antworttext. Jeder Absatz steht in eigenen <p>-Tags, Links sind erlaubt.',
            ],
        ],
        'default' => [
            [
                'frage' => 'Kann ich auch alleine kommen?',
                'antwort' => '<p>Natürlich. Du musst niemanden kennen oder gemeinsam mit jemandem anreisen. Du wirst während der vier Tage Teil unserer kleinen B³ Gruppe sein und hast gleichzeitig jederzeit die Möglichkeit, dich zurückzuziehen.</p>',
            ],
            [
                'frage' => 'Kann ich mit einer Freundin kommen?',
                'antwort' => '<p>Ja. Ihr könnt entweder jeweils einen Platz im Shared House buchen oder gemeinsam unser Friends Special wählen. Damit steht euch das ca. 150 m² große Apartment exklusiv zur Verfügung.</p>',
            ],
            [
                'frage' => 'Ist Ratenzahlung möglich?',
                'antwort' => '<p>Ja. Die verfügbaren Möglichkeiten zur Ratenzahlung kannst du direkt im Buchungsprozess auswählen.</p>',
            ],
            [
                'frage' => 'Wie funktioniert die Anreise?',
                'antwort' => '<p>Die An- und Abreise organisierst du eigenständig und sie ist nicht im Retreat-Preis enthalten. Die genaue Adresse in Spabrücken sowie weitere Informationen erhältst du rechtzeitig vor dem Retreat.</p>',
            ],
            [
                'frage' => 'Wann beginnt und endet das Retreat?',
                'antwort' => '<p>Die Anreise ist am Donnerstag, den 08. Oktober 2026 ab 16:00 Uhr möglich. Am Sonntag, den 11. Oktober 2026 erfolgt die Abreise vormittags bis spätestens 12:00 Uhr.</p>',
            ],
            [
                'frage' => 'Gibt es einen Hund auf dem Gelände?',
                'antwort' => '<p>Ja. Auf dem Anwesen lebt ein großer Wachhund. Er befindet sich in einem eigenen, gesicherten Bereich und läuft während des Retreats nicht frei auf dem Gelände herum. Diese Information ist vor allem wichtig, wenn du Angst vor großen Hunden hast.</p>',
            ],
            [
                'frage' => 'Was passiert, wenn ich stornieren muss?',
                'antwort' => '
            <p>Deine Buchung ist verbindlich. Für eine Stornierung gelten unsere zum Zeitpunkt deiner Buchung gültigen Stornierungsbedingungen und <a class="link" href="/agb">AGB</a>, die du vor Abschluss deiner Buchung vollständig einsehen kannst.</p>
            <p>Für unvorhergesehene Fälle empfehlen wir dir, eine passende Reiserücktrittsversicherung abzuschließen.</p>
          ',
            ],
        ],
    ],
];
