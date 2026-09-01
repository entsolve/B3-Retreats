-- B³ Retreats — Inhalte fuer die content-Tabelle.
-- ERZEUGT von tools/build-registry.py aus content/site.json.
--
-- Optional: ohne diese Datei rendert die Seite die Standardwerte aus
-- partials/registry/. MIT ihr stehen die Texte auch in der Datenbank und
-- sind damit im Panel sichtbar und bearbeitbar.
--
-- Import: admin/setup.php legt sie auf Wunsch mit an, oder in phpMyAdmin
-- nach schema.sql einlesen. Wiederholbar: bestehende Zeilen werden
-- ueberschrieben (ON DUPLICATE KEY UPDATE), nichts wird geloescht.
SET NAMES utf8mb4;

INSERT INTO `content` (`k`, `v`, `type`) VALUES
('termin.start', '2026-10-08', 'text'),
('termin.ende', '2026-10-11', 'text'),
('meta.title', 'B³ Retreat für Frauen | {datum} in Spabrücken', 'text'),
('meta.description', 'Vier Tage zurück zu dir: Frauen-Retreat mit Yin Yoga, Breathwork, Astrologie und Struktur vom {datum} in Spabrücken. Ab 1.549 € inkl. Unterkunft und Verpflegung, Ratenzahlung möglich.', 'textarea'),
('meta.canonical', 'https://b3-retreats.de/', 'url'),
('meta.google_site_verification', 'rea52oRzw9FTP1FTqxqboVxYl6JjOcomWECNzU3VXcc', 'text'),
('meta.favicon.src', 'assets/img/favicon.svg', 'image'),
('meta.favicon.apple', 'assets/img/favicon-180.png', 'image'),
('meta.og.site_name', 'B³ Retreats', 'text'),
('meta.og.title', 'B³ Retreat – Be free to be you. | {datum}, Spabrücken', 'text'),
('meta.og.description', 'Vier Tage zurück zu dir, deinen Bedürfnissen und dem, was du wirklich willst. Yin Yoga, Breathwork, Astrologie und Struktur – für maximal 11 Frauen.', 'textarea'),
('meta.og.image', 'assets/img/og-image.webp', 'image'),
('meta.jsonld.name', 'B³ Retreat – Be free to be you.', 'text'),
('meta.jsonld.start_date', '{start_iso}T16:00', 'text'),
('meta.jsonld.end_date', '{ende_iso}T12:00', 'text'),
('meta.jsonld.description', 'Vier Tage Frauen-Retreat mit Yin Yoga, Breathwork, Astro Energy Reading, Solfeggio-Frequenz-Reise und einer Session zu intuitiver Struktur.', 'textarea'),
('meta.jsonld.location.name', 'Anwesen Spabrücken', 'text'),
('meta.jsonld.location.locality', 'Spabrücken', 'text'),
('meta.jsonld.location.region', 'Rheinland-Pfalz', 'text'),
('meta.jsonld.location.country', 'DE', 'text'),
('meta.jsonld.organizer.name', 'B³ Retreats', 'text'),
('meta.jsonld.organizer.url', 'https://b3-retreats.de/', 'url'),
('meta.jsonld.offers.shared.name', 'Shared House', 'text'),
('meta.jsonld.offers.shared.price', '1549', 'number'),
('meta.jsonld.offers.friends.name', 'Friends Special (2 Personen)', 'text'),
('meta.jsonld.offers.friends.price', '3950', 'number'),
('nav.programm', 'Programm', 'text'),
('nav.unterkunft', 'Unterkunft &amp; Preise', 'text'),
('nav.team', 'Über uns', 'text'),
('nav.cta', 'Platz sichern', 'text'),
('nav.meta.dates', '{datum}', 'text'),
('nav.meta.location', 'Spabrücken, Rheinland-Pfalz', 'text'),
('hero.meta', '{datum} &nbsp;|&nbsp; Spabrücken', 'html'),
('hero.headline', 'Be free to be&nbsp;you.', 'html'),
('hero.lead', 'Vier Tage zurück zu dir und dem, was du wirklich willst.', 'textarea'),
('hero.cta.label', 'Meinen Platz sichern', 'text'),
('hero.cta.note', 'Max. 11 Frauen &middot; 3 Coaches &middot; Ratenzahlung möglich', 'html'),
('hero.image.wide.src', 'assets/img/hero-tall.webp', 'image'),
('hero.image.tall.src', 'assets/img/hero-tall.webp', 'image'),
('hero.image.tall.alt', 'Drei Frauen von hinten am Feldrand oberhalb des Retreat-Anwesens bei Spabrücken', 'text'),
('hero.belt', 'Vier Tage &nbsp;·&nbsp; Max. 11 Frauen &nbsp;·&nbsp; 3 Coaches &nbsp;·&nbsp; Spabrücken, Rheinland-Pfalz', 'html'),
('einladung.rail', 'Kapitel 01', 'text'),
('einladung.eyebrow', 'Eine Einladung', 'text'),
('einladung.headline', 'Wann hast du dir das letzte Mal wirklich Zeit für dich genommen?', 'text'),
('einladung.body', '[{"text": "Nicht, um etwas abzuarbeiten oder das nächste Ziel zu erreichen. Sondern einfach, um wieder genauer hinzuhören."}, {"text": "B³ ist eine Einladung, für vier Tage Abstand vom Alltag zu nehmen: zu deinem Körper, deinen Gedanken, deiner Intuition und den Wünschen, die dabei manchmal leiser werden."}, {"text": "Vom 08.–11. Oktober schaffen wir inmitten der Natur einen Raum für Frauen, die wissen, wo sie stehen – und sich trotzdem bewusst Zeit für sich nehmen möchten."}, {"text": "Dich erwarten Yin Yoga und Breathwork, Astrologie und Frequenzarbeit, Reflexion und Struktur. Dazu gutes Essen, Natur, Gemeinschaft und genügend freie Zeit für das, was dir guttut."}]', 'json'),
('einladung.link.label', 'Das ganze Programm ansehen', 'text'),
('einladung.image.src', 'assets/img/detail-kerze.webp', 'image'),
('einladung.image.alt', 'Kerze und Lavendel auf einem Holzhocker im Retreat-Raum', 'text'),
('einladung.accent.title', 'Body. Mind. Soul.', 'text'),
('einladung.accent.text', 'Drei Perspektiven, die sich zu einer gemeinsamen Erfahrung verbinden.', 'textarea'),
('einladung.accent.said', 'B³ – gesprochen: Be free.', 'text'),
('bms.rail', 'Kapitel 02', 'text'),
('bms.eyebrow', 'Body. Mind. Soul.', 'text'),
('bms.headline', 'Das ist B³.', 'text'),
('bms.ruler', 'Drei Zugänge', 'text'),
('bms.intro', 'Wir sind Sophie, Sarah und Christina und bringen drei ganz unterschiedliche Perspektiven mit, die sich bei B³ miteinander verbinden.', 'textarea'),
('bms.cols', '[{"title": "Body", "sup": "1", "who": "Sarah", "text": "Sarah arbeitet mit dem Körper, dem Atem und unserer Wahrnehmung."}, {"title": "Soul", "sup": "2", "who": "Sophie", "text": "Sophie verbindet Astrologie, Energiearbeit und spirituelle Impulse."}, {"title": "Mind", "sup": "3", "who": "Christina", "text": "Christina bringt Klarheit, Reflexion und Struktur hinein und beschäftigt sich mit der Frage, wie wir unser Leben so gestalten können, dass das, was uns wirklich wichtig ist, darin auch seinen Platz findet."}]', 'json'),
('bms.outro', '[{"text": "Für uns gehören diese Ebenen zusammen. Denn das, was wir fühlen, was wir denken und wie wir unser Leben gestalten, lässt sich nicht immer voneinander trennen."}, {"text": "Genau aus dieser Verbindung ist B³ entstanden."}]', 'json'),
('bms.sign', 'Body. Mind. Soul. Be free.', 'text'),
('ablauf.rail', 'Kapitel 03', 'text'),
('ablauf.eyebrow', 'Donnerstag bis Sonntag', 'text'),
('ablauf.headline', 'Eine Reise zurück zu dir.', 'text'),
('ablauf.ruler', 'Der Bogen', 'text'),
('ablauf.intro', '[{"text": "Wir möchten diese vier Tage nicht wie ein klassisches Seminar gestalten, bei dem ein Programmpunkt auf den nächsten folgt."}, {"text": "Unsere Sessions bauen aufeinander auf, ohne dass wir jeden Moment deines Tages verplanen. Zwischen den gemeinsamen Zeiten bleibt bewusst Raum, damit du das Erlebte auch einfach einmal wirken lassen kannst."}]', 'json'),
('ablauf.image.src', 'assets/img/ablauf.webp', 'image'),
('ablauf.image.alt', 'Blick von der Terrasse über Felder und Wald', 'text'),
('ablauf.timeline', '[{"tag": "Donnerstag", "text": "Du darfst erst einmal ankommen und den Alltag langsam hinter dir lassen. Anreise ab 16:00 Uhr."}, {"tag": "Freitag", "text": "Wir tauchen gemeinsam tiefer ein und beschäftigen uns mit deinem Körper, deiner Wahrnehmung und deinen Bedürfnissen."}, {"tag": "Samstag", "text": "Mit unseren Experiences und rund um den Neumond gehen wir noch einmal tiefer."}, {"tag": "Sonntag", "text": "Wir lassen die gemeinsamen Tage ruhig ausklingen. Abreise bis 12:00 Uhr."}]', 'json'),
('ablauf.note', 'Denn am Ende sollst du nicht nach Hause fahren und erst einmal Urlaub von deinem Retreat brauchen.', 'textarea'),
('neumond.rail', 'Kapitel 04', 'text'),
('neumond.eyebrow', 'Mitten im Wochenende', 'text'),
('neumond.headline', 'Der Neumond als besonderer Moment', 'text'),
('neumond.ruler', 'Samstag', 'text'),
('neumond.intro', 'Mitten in unserem gemeinsamen Wochenende begleitet uns der Neumond und gibt uns einen schönen Anlass, noch einmal bewusster hinzuschauen.', 'textarea'),
('neumond.fragen', '[{"text": "Was brauche ich gerade wirklich?"}, {"text": "Was fühlt sich noch nach mir an?"}, {"text": "Wo wünsche ich mir Veränderung oder mehr Balance?"}, {"text": "Und welchen Dingen möchte ich in meinem Leben wieder mehr Raum geben?"}]', 'json'),
('neumond.outro', '[{"text": "Diese Fragen werden nicht in einer einzigen Session beantwortet. Sie dürfen sich durch unsere gemeinsamen Tage ziehen und aus ganz unterschiedlichen Perspektiven betrachtet werden."}, {"text": "Dabei geht es nicht darum, nach vier Tagen alles anders zu machen. Vielleicht geht es vielmehr darum, klarer zu sehen, was längst da ist und wieder mehr darauf zu vertrauen."}]', 'json'),
('exp.kapitel', 'Kapitel 05', 'text'),
('exp.eyebrow', 'Das Programm', 'text'),
('exp.headline', 'Deine B³ Experiences', 'text'),
('exp.ruler', 'Vier Zugänge', 'text'),
('exp.intro', 'Unsere Experiences greifen ineinander und begleiten dich über verschiedene Zugänge – über deinen Körper, deine innere Wahrnehmung und deine Gedanken.', 'textarea'),
('exp.items', '[{"image": {"src": "assets/img/exp-sarah.webp", "alt": "Sarah Bernhard mit gefalteten Händen auf der Terrasse am Feldrand", "height": 1250}, "who": "Mit Sarah", "title": "Yin Yoga &amp; Breathwork", "flip": "", "paragraphs": [{"text": "Wir starten gemeinsam mit ruhigem Yin Yoga in den Morgen. Zeit, um im Körper anzukommen, wahrzunehmen und bewusst in den Tag zu starten."}, {"text": "In einer gemeinsamen Breathwork Session schaffen wir Raum, tiefer zu fühlen, wahrzunehmen und dem zu begegnen, was sich gerade zeigen möchte."}, {"text": "Dabei geht es nicht darum, etwas zu erzwingen oder „wegzumachen“, sondern einen Raum zu schaffen, in dem du deinen Körper wieder wahrnehmen, zur Ruhe kommen und dich selbst ein Stück tiefer erfahren kannst."}], "second": {"who": "", "title": "", "paragraphs": []}}, {"image": {"src": "assets/img/exp-sophie.webp", "alt": "Sophie Christin Braun am Feldrand im späten Nachmittagslicht", "height": 1250}, "who": "Mit Sophie", "title": "Astro Energy Reading", "flip": " exp__item--flip", "paragraphs": [{"text": "Astrologie ist für Sophie keine Vorhersage, sondern ein Spiegel und eine Erinnerung."}, {"text": "Beim Retreat nimmt sie uns mit in ein Astro Energy Reading zu den aktuellen Energien – unter anderem zur Neumondenergie, zu Jupiter im Löwen sowie Saturn und Neptun im Widder."}, {"text": "Dabei geht es nicht darum, vorherzusagen, was passieren wird, sondern darum, welche Themen und Qualitäten wir gerade bewusst wahrnehmen und für uns nutzen dürfen."}], "second": {"who": "Mit Sophie", "title": "Solfeggio-Frequenz-Erinnerungs-Reise", "paragraphs": [{"text": "Sophie nimmt uns außerdem mit auf eine Reise zum Loslassen, Ankommen und Stillwerden. Begleitet von Solfeggio-Frequenzen und Energiearbeit entsteht ein Raum, in dem du dich zurücklehnen, wahrnehmen und wieder mehr bei dir und in der Verbindung zur Quelle ankommen darfst."}, {"text": "Denn manchmal brauchen wir keine weitere Antwort von außen, sondern die Ruhe, um wieder wahrzunehmen, was in uns längst da ist."}]}}, {"image": {"src": "assets/img/exp-christina.webp", "alt": "Christina am Feldrand im späten Nachmittagslicht", "height": 1250}, "who": "Mit Christina", "title": "Mit Struktur zu mehr Leichtigkeit", "flip": " exp__item--flip", "paragraphs": [{"text": "Du kannst alles wollen. Selbstverwirklichung, Business, Familie, Partnerschaft, Zeit für dich, Erfolg, Ruhe und Wachstum."}, {"text": "Die spannende Frage ist nicht, ob all das zusammenpassen darf, sondern wie du dein Leben so gestalten kannst, dass die Dinge, die dir wirklich wichtig sind, darin auch ihren Platz bekommen."}, {"text": "In unserer gemeinsamen Session beschäftigen wir uns deshalb nicht mit starren Regeln, perfekten Routinen oder dem nächsten durchgetakteten Wochenplan. Wir schauen auf eine intuitive und lebendige Struktur, die zu dir, deinen Bedürfnissen und deinem Leben passt."}, {"text": "Eine Struktur, die dich unterstützt, statt dich einzuengen – und dadurch mehr Leichtigkeit möglich macht."}], "second": {"who": "", "title": "", "paragraphs": []}}]', 'json'),
('freizeit.frieze.src', 'assets/img/fries-hof.webp', 'image'),
('freizeit.frieze.alt', 'Zufahrt und Hof des Anwesens, gesäumt von Zypressen', 'text'),
('freizeit.kapitel', 'Kapitel 06', 'text'),
('freizeit.eyebrow', 'Raum dazwischen', 'text'),
('freizeit.headline', 'Genug Zeit für dich', 'text'),
('freizeit.p1', 'Zu einem Retreat gehört für uns nicht nur das, was wir gemeinsam machen, sondern genauso die Zeit dazwischen.', 'textarea'),
('freizeit.p2', 'Deshalb planen wir bewusst längere Pausen und freie Zeit ein. Du kannst durch Wald und Felder spazieren, dich mit einem Buch zurückziehen, deine Gedanken aufschreiben, Zeit mit den anderen Frauen verbringen oder einfach irgendwo auf dem Gelände sitzen und nichts tun.', 'textarea'),
('freizeit.pullout', 'Alle unsere Experiences sind freiwillig.', 'text'),
('freizeit.p3', 'Wenn du an einem Morgen länger schlafen möchtest oder während einer Session merkst, dass du gerade lieber für dich sein möchtest, ist das genauso in Ordnung.', 'textarea'),
('freizeit.small', 'Während ausgewählter freier Zeitfenster kannst du außerdem persönliche 1:1 Sessions mit Sophie, Sarah oder Christina gegen Aufpreis buchen.', 'textarea'),
('freizeit.cta', 'Ich möchte dabei sein', 'text'),
('freizeit.belt', 'Du kommst an. Du musst nichts mitbringen außer dir selbst.', 'text'),
('ort.kapitel', 'Kapitel 07', 'text'),
('ort.eyebrow', 'Spabrücken | Rheinland-Pfalz', 'text'),
('ort.headline', 'Unser Retreat-Ort', 'text'),
('ort.map.line1', 'Spabrücken, Landkreis Bad Kreuznach.', 'text'),
('ort.p1', 'Unser Retreat findet auf einem großzügigen Anwesen in Spabrücken statt. Abgelegen vom Trubel und umgeben von Feldern, Wald und kleinen Wegen ist es ein Ort, an dem man erstaunlich schnell ein bisschen langsamer wird.', 'textarea'),
('ort.p2', 'Das Herzstück unseres Retreats ist eine große, moderne Remise. Hier finden viele unserer Experiences statt, hier essen wir gemeinsam und wahrscheinlich entstehen hier auch einige der Gespräche, die man vorher nicht planen kann.', 'textarea'),
('ort.p3', 'Bei schönem Wetter verbringen wir die Abende draußen auf der großen Holzterrasse. Von dort blickt man weit über die Landschaft und kann den Sonnenuntergang genießen, während auf dem Holzgrill unser Abendessen zubereitet wird.', 'textarea'),
('ort.p4', 'Und wenn du zwischendurch raus möchtest, gehst du einfach los. Rund um das Anwesen führen Feld- und Waldwege durch die Umgebung.', 'textarea'),
('ort.mosaic[0].src', 'assets/img/ort-terrasse.webp', 'image'),
('ort.mosaic[0].alt', 'Überdachte Terrasse mit langem Tisch, dahinter offene Felder', 'text'),
('ort.mosaic[1].src', 'assets/img/ort-sonne.webp', 'image'),
('ort.mosaic[1].alt', 'Sonnenlicht durch die Baumkronen am Rand des Grundstücks', 'text'),
('ort.mosaic[2].src', 'assets/img/ort-anwesen.webp', 'image'),
('ort.mosaic[2].alt', 'Das Anwesen mit Zypressenreihe und Sitzgruppe', 'text'),
('ort.mosaic[3].src', 'assets/img/ort-remise.webp', 'image'),
('ort.mosaic[3].alt', 'Zufahrt zur Remise', 'text'),
('ort.mosaic[4].src', 'assets/img/ort-terrasse2.webp', 'image'),
('ort.mosaic[4].alt', 'Lounge auf der Terrasse mit Blick über die Felder', 'text'),
('ort.mosaic[5].src', 'assets/img/ort-eingang.webp', 'image'),
('ort.mosaic[5].alt', 'Eingangsbereich des Anwesens', 'text'),
('ort.mosaic[6].src', 'assets/img/ort-saeule.webp', 'image'),
('ort.mosaic[6].alt', 'Überdachter Außenbereich mit Blick ins Tal', 'text'),
('ort.mosaic[7].src', 'assets/img/ort-haus.webp', 'image'),
('ort.mosaic[7].alt', 'Überdachter Balkon mit Sitzgelegenheit', 'text'),
('kulinarik.kapitel', 'Kapitel 08', 'text'),
('kulinarik.eyebrow', 'Frühstück &amp; Holzgrill', 'text'),
('kulinarik.headline', 'Gutes Essen und lange Abende', 'text'),
('kulinarik.ruler', 'Am Tisch', 'text'),
('kulinarik.p1', 'Freitag, Samstag und Sonntag starten wir gemeinsam mit einem reichhaltigen Frühstück in den Tag. Es gibt unter anderem frische Brötchen, Aufschnitt, Obst, Porridge und weitere warme Speisen.', 'textarea'),
('kulinarik.p2', 'Auch zwischendurch steht im Haus immer etwas zum Snacken bereit.', 'textarea'),
('kulinarik.p3', 'Am Abend kommen wir wieder gemeinsam zum Essen zusammen. Wenn das Wetter mitspielt, sitzen wir draußen auf der Holzterrasse, essen frisch zubereitete Speisen vom Holzgrill und lassen den Tag gemeinsam ausklingen.', 'textarea'),
('kulinarik.tagline', 'Bei der Buchung wählbar: normale oder vegetarische Verpflegung', 'text'),
('haus.frieze.image.src', 'assets/img/haus-fries.webp', 'image'),
('haus.frieze.image.alt', 'Gemeinschaftsbereich mit Essplatz und Sitzlandschaft', 'text'),
('haus.rail', 'Kapitel 09', 'text'),
('haus.eyebrow', 'Unterbringung &amp; Preise', 'text'),
('haus.headline', 'Dein Zuhause für diese vier Tage', 'text'),
('haus.sub', 'Du kannst zwischen zwei Möglichkeiten der Unterbringung wählen.', 'textarea'),
('haus.shared.label', 'Shared House', 'text'),
('haus.shared.headline', 'Gemeinsam wohnen, gemeinsam erleben.', 'text'),
('haus.shared.text', 'Unser ca. 150 m² großer Neubau verbindet moderne Einrichtung mit rustikaler Holzoptik und bietet Platz für bis zu 11 Frauen.', 'textarea'),
('haus.shared.feats', '[{"text": "4 Schlafzimmer"}, {"text": "2- und 3-Bettzimmer mit Einzelbetten"}, {"text": "Gemeinschaftsraum"}, {"text": "Küchenzeile"}, {"text": "Badezimmer"}, {"text": "separates WC"}]', 'json'),
('haus.shared.note', 'Die Zimmer und Betten werden von uns zugeteilt. Eine Vorauswahl ist nicht möglich.', 'textarea'),
('haus.shared.galerie', '[{"src": "assets/img/haus-g1.webp", "alt": "Schlafzimmer mit gepolstertem Kopfteil und bodentiefem Fenster"}, {"src": "assets/img/haus-g2.webp", "alt": "Zweites Schlafzimmer mit Blick ins Grüne"}, {"src": "assets/img/haus-g3.webp", "alt": "Gemeinschaftsraum mit Küchenzeile, Essplatz und Sitzlandschaft"}, {"src": "assets/img/haus-g4.webp", "alt": "Badezimmer mit ebenerdiger Dusche"}]', 'json'),
('haus.shared.price', '1.549&nbsp;€', 'text'),
('haus.shared.price_note', 'pro Person &middot; 3 Übernachtungen, Verpflegung und alle regulären B³ Experiences inklusive. Ratenzahlung möglich.', 'textarea'),
('haus.shared.cta', 'Meinen Platz sichern', 'text'),
('haus.shared.url', '', 'url'),
('haus.shared.plaetze', '8', 'number'),
('haus.friends.label', 'Friends Special', 'text'),
('haus.friends.badge', 'Exklusiv', 'text'),
('haus.friends.headline', 'Euer eigener Rückzugsort.', 'text'),
('haus.friends.text', 'Wenn ihr zu zweit kommt und euch während des Retreats mehr Privatsphäre gönnen möchtet, könnt ihr unser exklusives Apartment buchen. Auf ca. 150 m² habt ihr jede Menge Platz nur für euch und einen wunderschönen Blick über die Landschaft.', 'textarea'),
('haus.friends.feats', '[{"text": "Schlafzimmer mit Doppelbett"}, {"text": "großzügiges Wohnzimmer"}, {"text": "eigene Küchenzeile"}, {"text": "Badezimmer"}, {"text": "separates WC"}, {"text": "großer Balkon mit weitem Ausblick"}]', 'json'),
('haus.friends.note', 'Ihr seid bei allen gemeinsamen Experiences und Mahlzeiten dabei und könnt euch zwischendurch jederzeit in eure eigenen vier Wände zurückziehen.', 'textarea'),
('haus.friends.galerie', '[{"src": "assets/img/friends-g1.webp", "alt": "Schlafzimmer des Apartments mit Doppelbett"}, {"src": "assets/img/friends-g2.webp", "alt": "Wohnzimmer des Apartments mit eigener Küchenzeile"}, {"src": "assets/img/friends-g3.webp", "alt": "Balkon mit weitem Blick über die Landschaft"}, {"src": "assets/img/friends-g4.webp", "alt": "Überdachter Außenbereich am Apartment"}]', 'json'),
('haus.friends.price', '3.950&nbsp;€', 'text'),
('haus.friends.price_note', 'für 2 Personen &middot; 3 Übernachtungen, Verpflegung und alle regulären B³ Experiences inklusive. Ratenzahlung möglich.', 'textarea'),
('haus.friends.cta', 'Friends Special sichern', 'text'),
('haus.friends.url', '', 'url'),
('haus.friends.plaetze', '2', 'number'),
('inkl.eyebrow', 'Im Preis enthalten', 'text'),
('inkl.headline', 'Was ist inklusive?', 'text'),
('inkl.intro', 'Mit deiner Buchung ist fast alles abgedeckt, was du während der gemeinsamen Tage brauchst:', 'textarea'),
('inkl.items', '[{"text": "3 Übernachtungen"}, {"text": "Frühstück am Freitag, Samstag und Sonntag"}, {"text": "gemeinsame Abendessen"}, {"text": "Snacks für zwischendurch"}, {"text": "Yin Yoga"}, {"text": "Gruppen-Breathwork"}, {"text": "Astro Energy Reading"}, {"text": "Solfeggio-Frequenz-Erinnerungs-Reise"}, {"text": "Gruppen-Session „Mit Struktur zu mehr Leichtigkeit“"}, {"text": "unsere gemeinsame Zeit rund um den Neumond"}, {"text": "Nutzung der Retreat- und Gemeinschaftsbereiche"}, {"text": "ausreichend freie Zeit für dich"}]', 'json'),
('inkl.excluded_label', 'Nicht enthalten', 'text'),
('inkl.excluded_text', 'Deine individuelle An- und Abreise sowie optional buchbare 1:1 Sessions.', 'textarea'),
('team.rail', 'Kapitel 10', 'text'),
('team.eyebrow', 'Sophie &middot; Sarah &middot; Christina', 'text'),
('team.headline', 'Die drei Frauen hinter B³', 'text'),
('team.intro', 'Drei Persönlichkeiten, drei unterschiedliche Welten und ziemlich unterschiedliche Wege haben uns hierhergebracht. Was uns verbindet, ist die Überzeugung, dass wir uns und unser Leben nicht in einzelne Schubladen aufteilen müssen.', 'textarea'),
('team.link.insta', 'Instagram', 'text'),
('team.link.web', 'Website', 'text'),
('team.sophie.image.src', 'assets/img/sophie.webp', 'image'),
('team.sophie.image.alt', 'Sophie Christin Braun am Feldrand', 'text'),
('team.sophie.name', 'Sophie Christin Braun', 'text'),
('team.sophie.craft', 'Astrologie | Energiearbeit | spirituelle &amp; mediale Impulse', 'text'),
('team.sophie.web', 'https://www.sophiechristinbraun.de', 'url'),
('team.sophie.insta', 'https://www.instagram.com/sophiechristinbraun/', 'url'),
('team.sophie.text1', 'Ich bin Sophie – neugierig, intuitiv, manchmal ziemlich tiefgründig und definitiv ein kleiner Freigeist.', 'textarea'),
('team.sophie.text2', 'Ich war noch nie besonders gut darin, mich in Schubladen stecken zu lassen. Ich mag kein Schwarz-Weiß-Denken und keine fertigen Antworten.', 'textarea'),
('team.sophie.accent', 'Aber warum eigentlich? Muss das wirklich so sein?', 'textarea'),
('team.sophie.text3', 'Genau diese Freiheit, selbst zu denken, zu fühlen und den eigenen Weg zu gehen, möchte ich auch in meine Arbeit mitgeben. Meine Arbeit verbindet Astrologie, Energiearbeit und spirituelle sowie mediale Impulse. Im Mittelpunkt steht für mich dabei immer die Verbindung zur Quelle, zu Gott und zu unserer eigenen inneren Wahrheit.', 'textarea'),
('team.sophie.text4', 'Ich freue mich sehr auf echte Begegnungen, tiefe Gespräche, kleine Aha-Momente und darauf, diese besondere Zeit miteinander zu erleben.', 'textarea'),
('team.sarah.image.src', 'assets/img/sarah.webp', 'image'),
('team.sarah.image.alt', 'Sarah Bernhard am Feldrand', 'text'),
('team.sarah.name', 'Sarah Bernhard', 'text'),
('team.sarah.craft', 'Yin Yoga | Breathwork | Bodywork', 'text'),
('team.sarah.web', 'https://atemraum-yoga.de', 'url'),
('team.sarah.insta', 'https://www.instagram.com/atemraum.yoga', 'url'),
('team.sarah.text1', 'Ich bin Sarah und begleite Menschen dabei, wieder mehr in Kontakt mit sich selbst, ihrem Körper und dem eigenen Erleben zu kommen.', 'textarea'),
('team.sarah.text2', 'Mir geht es dabei nicht darum, etwas zu erzwingen oder „wegzumachen“, sondern einen Raum zu schaffen, in dem du deinen Körper wieder wahrnehmen, zur Ruhe kommen und dich selbst ein Stück tiefer erfahren kannst.', 'textarea'),
('team.christina.image.src', 'assets/img/christina.webp', 'image'),
('team.christina.image.alt', 'Christina am Feldrand', 'text'),
('team.christina.name', 'Christina', 'text'),
('team.christina.craft', 'Strategie | Business | Struktur', 'text'),
('team.christina.web', 'https://christina-brumm.com', 'url'),
('team.christina.insta', 'https://www.instagram.com/christina.brumm', 'url'),
('team.christina.text1', 'Ich bin Christina – kreative Seele, Unternehmerin und ziemlich schlecht darin, mich mit einem „entweder oder“ zufriedenzugeben.', 'textarea'),
('team.christina.accent', 'Ich will alles.', 'textarea'),
('team.christina.text2', 'Ich möchte mich in meinem Business verwirklichen, meine Familie und Partnerschaft lieben und leben, erfolgreich sein, frei sein und trotzdem genügend Zeit für die Menschen und Dinge haben, die mir wichtig sind.', 'textarea'),
('team.christina.text3', 'Mit klassischer Work-Life-Balance konnte ich deshalb noch nie besonders viel anfangen. Mein Business steht für mich nicht auf der einen und mein Leben auf der anderen Seite. Es ist ein Teil meines Lebens, den ich liebe und den ich genauso bewusst gestalten möchte wie alle anderen Bereiche.', 'textarea'),
('team.christina.text4', 'Ich glaube daran, dass die Dinge, die uns wichtig sind, gleichzeitig Platz haben dürfen. Nicht immer zu gleichen Teilen und ganz sicher nicht immer perfekt. Aber ohne uns selbst dabei ständig auf später zu verschieben.', 'textarea'),
('team.christina.text5', 'Dafür braucht es Struktur. Nur eben keine starren Regeln und keinen Kalender, der jede Minute kontrolliert. Ich arbeite mit einer intuitiven, lebendigen Struktur, die sich dem Leben anpassen darf und uns dabei hilft, unsere Prioritäten tatsächlich zu leben.', 'textarea'),
('team.christina.text6', 'Denn Selbstverwirklichung bedeutet für mich nicht, immer mehr zu schaffen. Es bedeutet, ein Leben aufzubauen, das wirklich zu mir gehört.', 'textarea'),
('fuerwen.rail', 'Kapitel 11', 'text'),
('fuerwen.eyebrow', 'Ausschließlich für Frauen', 'text'),
('fuerwen.headline', 'Ist B³ für dich?', 'text'),
('fuerwen.body', '[{"text": "B³ ist für Frauen, die grundsätzlich wissen, wer sie sind und wohin sie möchten. Frauen mit Wünschen, Zielen und Ideen, die sich ganz bewusst ein paar Tage Zeit nehmen möchten, um wieder genauer hinzuhören."}, {"text": "Vielleicht hast du dir bereits ein Leben aufgebaut, das du sehr magst, und trotzdem gibt es Fragen, die im Alltag zu wenig Raum bekommen."}]', 'json'),
('fuerwen.image.src', 'assets/img/fuerwen-blick.webp', 'image'),
('fuerwen.image.alt', 'Blick von der Terrasse über Bäume und Felder', 'text'),
('fuerwen.fragen', '[{"text": "Was will ich gerade wirklich?"}, {"text": "Was brauche ich mehr und wovon vielleicht weniger?"}, {"text": "Was fühlt sich noch nach mir an?"}, {"text": "Wo möchte ich etwas verändern?"}, {"text": "Was möchte ich genauso behalten, wie es ist?"}]', 'json'),
('fuerwen.fazit', 'Du musst bei B³ keine neue Version von dir werden. Es geht vielmehr darum, wieder näher an das heranzukommen, was längst zu dir gehört.', 'textarea'),
('fuerwen.cta.label', 'Ja, ich möchte dabei sein', 'text'),
('fuerwen.cta.note', '{datum} &middot; Spabrücken &middot; Ratenzahlung möglich', 'text'),
('buchung.url', '', 'url'),
('buchung.bg.src', 'assets/img/buchung-bg.webp', 'image'),
('buchung.titel', 'B³ Retreat {monat}', 'text'),
('buchung.ort', 'Spabrücken, Rheinland-Pfalz', 'text'),
('buchung.termine', '[{"label": "Anreise", "zeit": "{start_tag}, {start} ab 16:00 Uhr"}, {"label": "Abreise", "zeit": "{ende_tag}, {ende} bis 12:00 Uhr"}]', 'json'),
('buchung.preise', '[{"label": "Shared House", "preis": "1.549 €", "hinweis": "pro Person", "cta": "Meinen Platz sichern", "url": ""}, {"label": "Friends Special", "preis": "3.950 €", "hinweis": "für 2 Personen", "cta": "Friends Special sichern", "url": ""}]', 'json'),
('buchung.hinweis', 'Unterkunft, Verpflegung und alle regulären B³ Experiences inklusive. Ratenzahlung möglich.', 'textarea'),
('buchung.fine', 'Zahlung über unseren externen Zahlungsdienstleister Stripe. AGB und Stornierungsbedingungen sind vor Abschluss der Buchung vollständig einsehbar.', 'textarea'),
('faq.rail', 'Kapitel 12', 'text'),
('faq.eyebrow', 'Gut zu wissen', 'text'),
('faq.headline', 'Häufige Fragen', 'text'),
('faq.spalte1', '[{"frage": "Muss ich an allen Experiences teilnehmen?", "antwort": "<p>Nein. Alle Experiences sind freiwillig. Wenn du eine Session auslassen und stattdessen spazieren gehen, schlafen, lesen oder einfach Zeit für dich verbringen möchtest, ist das vollkommen in Ordnung.</p>"}, {"frage": "Gibt es genügend Freizeit?", "antwort": "<p>Ja. Wir planen bewusst freie Zeiten zwischen den gemeinsamen Experiences ein, damit du das Erlebte wirken lassen und das Retreat in deinem eigenen Tempo genießen kannst.</p>"}, {"frage": "Kann ich eine persönliche 1:1 Session buchen?", "antwort": "<p>Ja. In ausgewählten freien Zeitfenstern kannst du zusätzliche 1:1 Sessions mit Sophie, Sarah oder Christina buchen. Diese sind nicht im Retreat-Preis enthalten und werden separat berechnet.</p>"}, {"frage": "Ist das Retreat ausschließlich für Frauen?", "antwort": "<p>Ja. Dieses B³ Retreat richtet sich ausschließlich an Frauen.</p>"}, {"frage": "Ist die Verpflegung inklusive?", "antwort": "<p>Ja. Frühstück, gemeinsame Abendessen und Snacks für zwischendurch sind inklusive. Bei der Buchung kannst du zwischen normaler und vegetarischer Verpflegung wählen.</p>"}, {"frage": "Was ist bei Allergien oder Unverträglichkeiten?", "antwort": "\\n            <p>Wir können keine individuell allergenfreie Zubereitung und keinen vollständigen Ausschluss von Kreuzkontaminationen gewährleisten.</p>\\n            <p>Wenn du Allergien oder Unverträglichkeiten hast, informiere uns bitte vorab und achte vor Ort eigenverantwortlich darauf, welche Lebensmittel für dich geeignet sind.</p>\\n          "}, {"frage": "Kann ich mein Zimmer auswählen?", "antwort": "<p>Nein. Im Shared House erfolgt die Unterbringung in 2- und 3-Bettzimmern mit Einzelbetten. Die Zimmer und Betten werden von uns zugeteilt.</p>"}]', 'json'),
('faq.spalte2', '[{"frage": "Kann ich auch alleine kommen?", "antwort": "<p>Natürlich. Du musst niemanden kennen oder gemeinsam mit jemandem anreisen. Du wirst während der vier Tage Teil unserer kleinen B³ Gruppe sein und hast gleichzeitig jederzeit die Möglichkeit, dich zurückzuziehen.</p>"}, {"frage": "Kann ich mit einer Freundin kommen?", "antwort": "<p>Ja. Ihr könnt entweder jeweils einen Platz im Shared House buchen oder gemeinsam unser Friends Special wählen. Damit steht euch das ca. 150 m² große Apartment exklusiv zur Verfügung.</p>"}, {"frage": "Ist Ratenzahlung möglich?", "antwort": "<p>Ja. Die verfügbaren Möglichkeiten zur Ratenzahlung kannst du direkt im Buchungsprozess auswählen.</p>"}, {"frage": "Wie funktioniert die Anreise?", "antwort": "<p>Die An- und Abreise organisierst du eigenständig und sie ist nicht im Retreat-Preis enthalten. Die genaue Adresse in Spabrücken sowie weitere Informationen erhältst du rechtzeitig vor dem Retreat.</p>"}, {"frage": "Wann beginnt und endet das Retreat?", "antwort": "<p>Die Anreise ist am Donnerstag, den 08. Oktober 2026 ab 16:00 Uhr möglich. Am Sonntag, den 11. Oktober 2026 erfolgt die Abreise vormittags bis spätestens 12:00 Uhr.</p>"}, {"frage": "Gibt es einen Hund auf dem Gelände?", "antwort": "<p>Ja. Auf dem Anwesen lebt ein großer Wachhund. Er befindet sich in einem eigenen, gesicherten Bereich und läuft während des Retreats nicht frei auf dem Gelände herum. Diese Information ist vor allem wichtig, wenn du Angst vor großen Hunden hast.</p>"}, {"frage": "Was passiert, wenn ich stornieren muss?", "antwort": "\\n            <p>Deine Buchung ist verbindlich. Für eine Stornierung gelten unsere zum Zeitpunkt deiner Buchung gültigen Stornierungsbedingungen und <a class=\\"link\\" href=\\"/agb\\">AGB</a>, die du vor Abschluss deiner Buchung vollständig einsehen kannst.</p>\\n            <p>Für unvorhergesehene Fälle empfehlen wir dir, eine passende Reiserücktrittsversicherung abzuschließen.</p>\\n          "}]', 'json'),
('foot.brand.tagline', 'Body. Mind. Soul.<br>Yoga &middot; Astro &middot; Business', 'html'),
('foot.brand.dateline', '{datum}<br>Spabrücken, Rheinland-Pfalz', 'html'),
('foot.nav.retreat.label', 'Retreat', 'text'),
('foot.nav.retreat.start', 'Startseite', 'text'),
('foot.nav.retreat.programm', 'Programm', 'text'),
('foot.nav.retreat.ort', 'Der Ort', 'text'),
('foot.nav.retreat.unterkunft', 'Unterkunft &amp; Preise', 'text'),
('foot.nav.retreat.team', 'Über uns', 'text'),
('foot.nav.retreat.faq', 'Häufige Fragen', 'text'),
('foot.nav.legal.label', 'Rechtliches', 'text'),
('foot.nav.legal.impressum', 'Impressum', 'text'),
('foot.nav.legal.datenschutz', 'Datenschutz', 'text'),
('foot.nav.legal.agb', 'AGB', 'text'),
('foot.nav.legal.kontakt', 'Kontakt', 'text'),
('foot.nav.legal.consent', 'Cookie-Einstellungen', 'text'),
('foot.booking.label', 'Buchung', 'text'),
('foot.booking.prices', 'Shared House ab 1.549 &euro; pro Person<br>Friends Special 3.950 &euro; für zwei', 'html'),
('foot.booking.cta', 'Meinen Platz sichern', 'text'),
('foot.legal.line', 'B³ Retreats &middot; Christina Brumm &middot; An den Nahewiesen 20, 55450 Langenlonsheim', 'text'),
('foot.legal.note', 'Buchung und Zahlungsabwicklung über Stripe', 'text'),
('bar.price', 'Shared House ab 1.549 €', 'text'),
('bar.cta', 'Platz sichern', 'text'),
('consent.eyebrow', 'Cookies', 'text'),
('consent.text', 'Wir setzen nur technisch notwendige Cookies. Optionale Cookies für Statistik oder Marketing
         erst mit deiner Zustimmung — mehr dazu in der <a class="link" href="/datenschutz">Datenschutzerklärung</a>.', 'html'),
('consent.btn_necessary', 'Nur notwendige', 'text'),
('consent.btn_all', 'Alle akzeptieren', 'text'),
('danke.meta.title', 'Du bist dabei! | B³ Retreats', 'text'),
('danke.eyebrow', 'Buchung bestätigt', 'text'),
('danke.headline', 'Du bist dabei! ♡', 'html'),
('danke.lead', 'Dein Platz beim B³ Retreat ist für dich reserviert.', 'html'),
('danke.intro', '[{"text": "Wie schön, dass du dich für diese besonderen Tage entschieden hast. Wir freuen uns riesig darauf, dich beim B³ Retreat willkommen zu heißen und gemeinsam eine unvergessliche Zeit zu erleben."}, {"text": "Jetzt darf die Vorfreude beginnen. ♡"}]', 'json'),
('danke.image.src', 'assets/img/hero-tall.webp', 'image'),
('danke.image.alt', 'Christina, Sophie und Sarah vom B³ Retreat', 'text'),
('danke.next.title', 'Wie geht es jetzt weiter?', 'text'),
('danke.next.body', '[{"text": "In den nächsten Stunden erhältst du von uns eine persönliche E-Mail mit allen wichtigen Informationen zu deinem Retreat."}, {"text": "Darin findest du alles, was du für die nächsten Schritte wissen musst."}]', 'json'),
('danke.ask.title', 'Eine kleine Sache brauchen wir noch von dir', 'text'),
('danke.ask.text', 'Bitte antworte direkt auf unsere E-Mail und teile uns mit, welche Verpflegung du während des Retreats möchtest:', 'html'),
('danke.ask.options', '[{"text": "Vegetarisch"}, {"text": "Mit Fleisch &amp; Fisch"}]', 'json'),
('danke.ask.note', 'So können wir deine Verpflegung entsprechend einplanen. Solltest du die E-Mail nicht direkt finden, wirf bitte auch einen Blick in deinen Spam-Ordner.', 'html'),
('danke.outro.title', 'Das war’s für den Moment.', 'text'),
('danke.outro.body', '[{"text": "Alles Weitere bekommst du persönlich von uns per E-Mail. Bis dahin musst du nichts weiter tun – außer dich auf deine Auszeit zu freuen."}, {"text": "Wir freuen uns auf dich!"}]', 'json'),
('danke.sign', 'Christina &amp; das B³ Retreat Team', 'html'),
('danke.cta', 'Zur Startseite', 'text'),
('recht.impressum.title', 'Impressum', 'text'),
('recht.impressum.h1', 'Impressum', 'text'),
('recht.impressum.desc', 'Impressum und Anbieterkennzeichnung von B³ Retreats, Christina Brumm, Langenlonsheim.', 'textarea'),
('recht.impressum.body', '<p>Angaben gemäß § 5 DDG (Digitale-Dienste-Gesetz, vormals § 5 TMG)</p>
<h2 id="angaben-gemaess-5-ddg">Angaben gemäß § 5 DDG</h2>
<p>Christina Brumm<br>
B³ Retreats (Geschäftsbezeichnung)<br>
An den Nahewiesen 20<br>
55450 Langenlonsheim<br>
Deutschland</p>
<p>B³ Retreats ist eine Geschäftsbezeichnung der Einzelunternehmerin Christina Brumm und keine eigenständige Gesellschaft.</p>
<h2 id="vertreten-durch">Vertreten durch</h2>
<p>Christina Brumm (Inhaberin)</p>
<p>Es handelt sich um ein Einzelunternehmen. Eine Eintragung im Handelsregister besteht nicht.</p>
<h2 id="kontakt">Kontakt</h2>
<p>E-Mail: hello@b3-retreats.de</p>
<p>Telefon: auf Anfrage</p>
<p>Website: www.b3-retreats.de</p>
<h2 id="umsatzsteuer-identifikationsnummer">Umsatzsteuer-Identifikationsnummer</h2>
<p>Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:</p>
<p>DE369850912</p>
<h2 id="redaktionell-verantwortlich-gemaess-18-abs-2-mst">Redaktionell verantwortlich gemäß § 18 Abs. 2 MStV</h2>
<p>Christina Brumm<br>
An den Nahewiesen 20<br>
55450 Langenlonsheim<br>
Deutschland</p>
<p>(Verantwortlich für journalistisch-redaktionell gestaltete Inhalte im Sinne des § 18 Abs. 2 Medienstaatsvertrag.)</p>
<h2 id="verbraucherstreitbeilegung-universalschlichtungs">Verbraucherstreitbeilegung / Universalschlichtungsstelle</h2>
<p>Wir sind nicht bereit und nicht verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>
<p>Hinweis zur EU-Streitschlichtung: Die von der Europäischen Kommission bereitgestellte Plattform zur Online-Streitbeilegung (OS-Plattform) wurde zum 20. Juli 2025 endgültig eingestellt. Ein Link auf diese Plattform ist daher nicht mehr vorgesehen und wird bewusst nicht mehr aufgeführt.</p>
<p>Allgemeine Informationen zur außergerichtlichen Streitbeilegung für Verbraucherinnen und Verbraucher sind bei der Allgemeinen Verbraucherschlichtungsstelle des Zentrums für Schlichtung e. V., Straßburger Straße 8, 77694 Kehl am Rhein, www.verbraucher-schlichter.de, erhältlich.</p>
<h2 id="haftung-fuer-inhalte">Haftung für Inhalte</h2>
<p>Als Diensteanbieterin sind wir gemäß § 7 Abs. 1 DDG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach den §§ 8 bis 10 DDG sind wir als Diensteanbieterin jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen.</p>
<p>Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.</p>
<p>Die Inhalte dieser Website wurden mit größtmöglicher Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte kann jedoch keine Gewähr übernommen werden. Die auf dieser Website dargestellten Inhalte zu Retreats, Yoga, Breathwork, Bodywork, Coaching, Meditation und vergleichbaren Angeboten ersetzen keine medizinische, psychotherapeutische oder sonstige heilkundliche Beratung oder Behandlung.</p>
<h2 id="haftung-fuer-links">Haftung für Links</h2>
<p>Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets die jeweilige Anbieterin oder der jeweilige Anbieter oder Betreiber der Seiten verantwortlich.</p>
<p>Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.</p>
<h2 id="urheberrecht">Urheberrecht</h2>
<p>Die durch die Seitenbetreiberin erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung der jeweiligen Autorin bzw. des jeweiligen Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet.</p>
<p>Soweit die Inhalte auf dieser Seite nicht von der Betreiberin erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.</p>
<h2 id="bildnachweise">Bildnachweise</h2>
<p>Aufnahmen des Anwesens, der Räumlichkeiten und des Außengeländes: © Christina Brumm.</p>
<p>Hinweis zu den Personenaufnahmen: Ein Teil der auf dieser Website gezeigten Aufnahmen von Personen wurde mit Hilfe generativer künstlicher Intelligenz erstellt oder bearbeitet. Abgebildet sind die Veranstalterinnen selbst; die Veröffentlichung erfolgt mit deren Einwilligung.</p>
<h2 id="stand">Stand</h2>
<p>Stand dieses Impressums: August 2026</p>', 'html'),
('recht.datenschutz.title', 'Datenschutzerklärung', 'text'),
('recht.datenschutz.h1', 'Datenschutz', 'text'),
('recht.datenschutz.desc', 'Informationen zur Verarbeitung personenbezogener Daten auf b3-retreats.de nach Art. 13 DSGVO.', 'textarea'),
('recht.datenschutz.body', '<h2 id="datenschutzerklaerung">Datenschutzerklärung</h2>
<p>Wir freuen uns über Ihr Interesse an B³ Retreats. Der Schutz Ihrer personenbezogenen Daten ist uns ein wichtiges Anliegen. Nachfolgend informieren wir Sie gemäß Art. 13 und Art. 14 der Datenschutz-Grundverordnung (DSGVO) darüber, welche personenbezogenen Daten wir beim Besuch dieser Website und bei der Buchung eines Retreats verarbeiten, zu welchen Zwecken dies geschieht und welche Rechte Ihnen zustehen.</p>
<h2 id="1-datenschutz-auf-einen-blick">1. Datenschutz auf einen Blick</h2>
<ul>
  <li>Diese Website ist eine reine Informations- und Buchungsseite. Sie können sie besuchen, ohne von sich aus personenbezogene Daten anzugeben.</li>
  <li>Beim bloßen Aufruf der Seite werden lediglich die technisch erforderlichen Zugriffsdaten (Server-Log-Dateien) verarbeitet.</li>
  <li>Personenbezogene Daten verarbeiten wir darüber hinaus insbesondere dann, wenn Sie uns per E-Mail kontaktieren oder ein Retreat über unsere Buchungsplattform buchen.</li>
  <li>Es werden keine Tracking-, Analyse- oder Marketing-Cookies eingesetzt. Verwendete Cookies sind ausschließlich technisch notwendig. Ihre Auswahl im Cookie-Hinweis wird nur lokal in Ihrem Browser gespeichert.</li>
  <li>Schriften (Web Fonts) werden lokal von unserem Server ausgeliefert. Es besteht keine Verbindung zu Servern von Google.</li>
  <li>Ihre Daten werden nicht zum Zweck von Profiling oder automatisierter Entscheidungsfindung verwendet.</li>
</ul>
<h2 id="2-verantwortliche-stelle">2. Verantwortliche Stelle</h2>
<p>Verantwortliche im Sinne der DSGVO ist:</p>
<p>Christina Brumm<br>
B³ Retreats (Geschäftsbezeichnung)<br>
An den Nahewiesen 20<br>
55450 Langenlonsheim<br>
Deutschland</p>
<p>E-Mail: hello@b3-retreats.de<br>
Telefon: auf Anfrage</p>
<p>Verantwortliche Stelle ist die natürliche oder juristische Person, die allein oder gemeinsam mit anderen über die Zwecke und Mittel der Verarbeitung von personenbezogenen Daten (z. B. Namen, E-Mail-Adressen o. Ä.) entscheidet.</p>
<p>Eine gesetzliche Pflicht zur Benennung einer bzw. eines Datenschutzbeauftragten besteht nach derzeitigem Stand nicht.</p>
<h2 id="3-allgemeine-hinweise-und-rechtsgrundlagen-der-v">3. Allgemeine Hinweise und Rechtsgrundlagen der Verarbeitung</h2>
<p>Wir verarbeiten personenbezogene Daten ausschließlich im Rahmen der geltenden datenschutzrechtlichen Bestimmungen, insbesondere der DSGVO, des Bundesdatenschutzgesetzes (BDSG) und des Telekommunikation-Digitale-Dienste-Datenschutz-Gesetzes (TDDDG).</p>
<p>Je nach Verarbeitungssituation stützen wir uns auf folgende Rechtsgrundlagen:</p>
<ul>
  <li>Art. 6 Abs. 1 lit. a DSGVO – Einwilligung: wenn Sie uns eine ausdrückliche Einwilligung in eine bestimmte Verarbeitung erteilt haben.</li>
  <li>Art. 6 Abs. 1 lit. b DSGVO – Vertrag oder vorvertragliche Maßnahmen: wenn die Verarbeitung zur Durchführung eines Retreat-Vertrages oder zur Beantwortung einer Anfrage erforderlich ist.</li>
  <li>Art. 6 Abs. 1 lit. c DSGVO – rechtliche Verpflichtung: insbesondere zur Erfüllung handels- und steuerrechtlicher Aufbewahrungspflichten.</li>
  <li>Art. 6 Abs. 1 lit. f DSGVO – berechtigtes Interesse: insbesondere am technisch fehlerfreien, sicheren und stabilen Betrieb dieser Website.</li>
</ul>
<p>Durch uns selbst findet keine Übermittlung personenbezogener Daten in ein Drittland außerhalb der EU bzw. des EWR statt.</p>
<p>Vertragspartner für Kundinnen und Kunden in der EU ist bei den eingesetzten Zahlungsdienstleistern jeweils eine Gesellschaft mit Sitz in der EU (siehe Abschnitt 7). Soweit im Rahmen der Zahlungsabwicklung dennoch Daten an Konzerngesellschaften in den USA übermittelt werden, stützt sich diese Übermittlung auf die Standardvertragsklauseln der EU-Kommission gemäß Art. 46 Abs. 2 lit. c DSGVO bzw. auf einen Angemessenheitsbeschluss (EU-US Data Privacy Framework), soweit der Anbieter dort zertifiziert ist.</p>
<p>Wir weisen darauf hin, dass die Datenübertragung im Internet (z. B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich.</p>
<p>Der Nutzung von im Rahmen der Impressumspflicht veröffentlichten Kontaktdaten zur Übersendung von nicht ausdrücklich angeforderter Werbung und Informationsmaterialien wird hiermit ausdrücklich widersprochen. Wir behalten uns ausdrücklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-E-Mails, vor.</p>
<h2 id="4-hosting-dieser-website">4. Hosting dieser Website</h2>
<p>Diese Website wird bei einem externen Dienstleister gehostet (Hoster). Die personenbezogenen Daten, die beim Aufruf dieser Website erfasst werden, werden auf den Servern des Hosters gespeichert. Hierbei kann es sich insbesondere um IP-Adressen, Kontaktanfragen, Meta- und Kommunikationsdaten, Websitezugriffe und sonstige Daten handeln, die über eine Website generiert werden.</p>
<p>Anbieter des Hostings ist:</p>
<p>brumm Hosting &amp; IT-Services<br>
An den Nahewiesen 20<br>
55450 Langenlonsheim<br>
Deutschland</p>
<p>Die Server stehen in Deutschland. Die technische Infrastruktur wird dabei von der webhoster.de AG, Zum Haunert 22, 59519 Möhnesee, bereitgestellt.</p>
<p>Der Einsatz des Hosters erfolgt zum Zweck der Vertragserfüllung gegenüber unseren potenziellen und bestehenden Teilnehmerinnen (Art. 6 Abs. 1 lit. b DSGVO) und im Interesse einer sicheren, schnellen und effizienten Bereitstellung unseres Online-Angebots durch einen professionellen Anbieter (Art. 6 Abs. 1 lit. f DSGVO).</p>
<p>Vertrag über Auftragsverarbeitung: Wir haben mit dem Hoster einen Vertrag über Auftragsverarbeitung gemäß Art. 28 DSGVO geschlossen. Dabei handelt es sich um einen datenschutzrechtlich vorgeschriebenen Vertrag, der gewährleistet, dass der Hoster die personenbezogenen Daten unserer Websitebesucherinnen und -besucher nur nach unseren Weisungen und unter Einhaltung der DSGVO verarbeitet.</p>
<h2 id="5-server-log-dateien">5. Server-Log-Dateien</h2>
<p>Der Provider dieser Seiten erhebt und speichert automatisch Informationen in sogenannten Server-Log-Dateien, die Ihr Browser automatisch an uns übermittelt. Dies sind:</p>
<ul>
  <li>Browsertyp und Browserversion</li>
  <li>verwendetes Betriebssystem</li>
  <li>Referrer-URL (die zuvor besuchte Seite)</li>
  <li>Hostname des zugreifenden Rechners</li>
  <li>Datum und Uhrzeit der Serveranfrage</li>
  <li>die angeforderte Datei bzw. aufgerufene Seite und die übertragene Datenmenge</li>
  <li>IP-Adresse</li>
</ul>
<p>Eine Zusammenführung dieser Daten mit anderen Datenquellen wird nicht vorgenommen. Eine Auswertung zu Marketing- oder Analysezwecken findet nicht statt.</p>
<p>Die Erfassung dieser Daten erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Wir haben ein berechtigtes Interesse an der technisch fehlerfreien Darstellung, der Stabilität und der Sicherheit unserer Website – hierzu müssen die Server-Log-Dateien erfasst werden.</p>
<p>Speicherdauer: Die Log-Dateien werden spätestens nach sieben Tagen automatisch gelöscht, sofern sie nicht ausnahmsweise zur Aufklärung eines konkreten Sicherheitsvorfalls oder Missbrauchsfalls länger benötigt werden.</p>
<h2 id="6-kontaktaufnahme-per-e-mail">6. Kontaktaufnahme per E-Mail</h2>
<p>Wenn Sie uns per E-Mail kontaktieren, werden Ihre Angaben inklusive der von Ihnen angegebenen Kontaktdaten (insbesondere Ihr Name und Ihre E-Mail-Adresse) sowie der Inhalt Ihrer Nachricht bei uns gespeichert, um Ihre Anfrage zu bearbeiten und für den Fall von Anschlussfragen.</p>
<p>Zwecke der Verarbeitung: Bearbeitung und Beantwortung Ihrer Anfrage, Kommunikation zu Retreats, Terminabstimmung sowie – sofern einschlägig – Vorbereitung eines Vertragsschlusses.</p>
<p>Rechtsgrundlagen:</p>
<ul>
  <li>Art. 6 Abs. 1 lit. b DSGVO, sofern Ihre Anfrage mit der Erfüllung eines Vertrages zusammenhängt oder zur Durchführung vorvertraglicher Maßnahmen erforderlich ist.</li>
  <li>Art. 6 Abs. 1 lit. f DSGVO in allen übrigen Fällen; unser berechtigtes Interesse liegt in der effektiven Bearbeitung der an uns gerichteten Anfragen.</li>
  <li>Art. 6 Abs. 1 lit. a DSGVO, sofern Sie uns eine Einwilligung erteilt haben; diese ist jederzeit für die Zukunft widerrufbar.</li>
</ul>
<p>Die Angabe personenbezogener Daten in Ihrer Nachricht erfolgt freiwillig. Ohne Angabe einer Kontaktmöglichkeit können wir Ihre Anfrage jedoch nicht beantworten.</p>
<p>Speicherdauer: Wir speichern Ihre Anfrage, bis der Zweck der Speicherung entfällt, Sie uns zur Löschung auffordern oder eine erteilte Einwilligung widerrufen – in der Regel also bis zur abschließenden Bearbeitung Ihres Anliegens und dem Ablauf einer angemessenen Nachlauffrist. Zwingende gesetzliche Aufbewahrungsfristen, insbesondere handels- und steuerrechtliche Aufbewahrungsfristen, bleiben unberührt.</p>
<p>Bitte beachten Sie: Der Versand unverschlüsselter E-Mails erfolgt über das Internet und kann von Dritten mitgelesen werden. Bitte übersenden Sie uns keine besonders sensiblen Informationen (insbesondere Gesundheitsdaten im Sinne des Art. 9 DSGVO) unaufgefordert per unverschlüsselter E-Mail. Sofern Sie uns im Zusammenhang mit einem Retreat freiwillig Gesundheitsinformationen mitteilen (z. B. Allergien, Unverträglichkeiten oder körperliche Einschränkungen), verarbeiten wir diese ausschließlich zur Durchführung des Retreats und auf Grundlage Ihrer ausdrücklichen Einwilligung nach Art. 9 Abs. 2 lit. a DSGVO.</p>
<h2 id="7-buchung-und-zahlungsabwicklung-ueber-stripe">7. Buchung und Zahlungsabwicklung über Stripe</h2>
<p>Für die Buchung unserer Retreats und die Abwicklung der Zahlung setzen wir Stripe ein. Der Buchungsvorgang findet auf einer von Stripe bereitgestellten und gehosteten Bezahlseite statt: Wenn Sie einen Buchungsbutton anklicken, verlassen Sie diese Website und geben Ihre Daten unmittelbar bei Stripe ein.</p>
<p>Anbieterin für Kundinnen und Kunden im Europäischen Wirtschaftsraum ist die Stripe Payments Europe, Ltd., 1 Grand Canal Street Lower, Grand Canal Dock, Dublin, Irland.</p>
<p>Im Rahmen der Buchung werden insbesondere folgende Daten verarbeitet:</p>
<ul>
  <li>Vor- und Nachname</li>
  <li>E-Mail-Adresse</li>
  <li>Rechnungs- bzw. Postanschrift</li>
  <li>gebuchtes Retreat und gewählte Buchungsoption</li>
  <li>gewählte Zahlungsart und die zur Durchführung der Zahlung erforderlichen Zahlungsdaten</li>
  <li>Buchungs- und Transaktionsdaten (Datum, Betrag, Währung, Status)</li>
  <li>technische Daten wie IP-Adresse und Geräteinformationen, die Stripe zur Betrugsprävention erhebt</li>
  <li>ggf. weitere im Buchungsvorgang abgefragte Angaben</li>
</ul>
<p>Zwecke der Verarbeitung: Durchführung des Buchungsvorgangs, Vertragsabwicklung, Zahlungsabwicklung, Rechnungsstellung, Kommunikation zur Buchung sowie Erfüllung gesetzlicher Aufbewahrungspflichten.</p>
<p>Rechtsgrundlagen: Art. 6 Abs. 1 lit. b DSGVO (Erfüllung des Retreat-Vertrages bzw. Durchführung vorvertraglicher Maßnahmen), Art. 6 Abs. 1 lit. c DSGVO (Erfüllung handels- und steuerrechtlicher Pflichten) sowie Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse an einer sicheren und betrugsgeschützten Zahlungsabwicklung).</p>
<p>Rollenverteilung: Für die Buchungsdaten, die wir zur Erfüllung des Retreat-Vertrages benötigen, sind wir die Verantwortliche; Stripe verarbeitet diese Daten insoweit in unserem Auftrag, hierüber besteht ein Vertrag über Auftragsverarbeitung gemäß Art. 28 DSGVO. Für die eigentliche Zahlungsabwicklung sowie für Betrugsprävention und die Erfüllung eigener aufsichtsrechtlicher Pflichten (insbesondere nach Geldwäsche- und Zahlungsdiensterecht) ist Stripe eigenständig Verantwortliche und verarbeitet die Daten nach den eigenen Datenschutzbestimmungen.</p>
<p>Zahlungsdaten wie vollständige Kreditkartennummern oder Bankzugangsdaten werden von uns selbst weder erhoben noch gespeichert. Wir erhalten von Stripe die zur Vertragserfüllung nötigen Buchungsdaten sowie die Information, ob und wann eine Zahlung erfolgreich war.</p>
<p>Im Buchungsvorgang stehen über Stripe folgende Zahlungsarten zur Verfügung:</p>
<ul>
  <li>Kreditkarte</li>
  <li>Klarna (Ratenzahlung möglich)</li>
  <li>Apple Pay</li>
  <li>Google Pay</li>
  <li>Link (Bezahldienst von Stripe)</li>
</ul>
<p>Datenschutzerklärung von Stripe: https://stripe.com/de/privacy</p>
<p>Link: Link ist der Bezahldienst von Stripe, mit dem Zahlungs- und Kontaktdaten für spätere Zahlungen gespeichert werden können. Wenn Sie Link nutzen, speichert Stripe die von Ihnen hinterlegten Daten in Ihrem Link-Konto und stellt sie Ihnen auch bei anderen Händlern zur Verfügung, die Link einsetzen. Die Nutzung von Link ist freiwillig; Verantwortliche für das Link-Konto ist Stripe.</p>
<p>Klarna und Ratenzahlung: Wenn Sie Klarna als Zahlungsart wählen – etwa um von der Möglichkeit der Ratenzahlung Gebrauch zu machen –, werden die für die Zahlungsabwicklung erforderlichen Daten an Klarna übermittelt. Anbieterin ist die Klarna Bank AB (publ), Sveavägen 46, 111 34 Stockholm, Schweden. Klarna ist für diese Verarbeitung eigenständig Verantwortliche. Bei Zahlungsarten mit Zahlungsaufschub oder Ratenzahlung kann Klarna eine Identitäts- und Bonitätsprüfung durchführen und hierfür Auskünfte bei Auskunfteien einholen. Auf Ablauf und Ergebnis dieser Prüfung haben wir keinen Einfluss. Einzelheiten hierzu sowie zu Ihren Rechten gegenüber Klarna entnehmen Sie bitte der Datenschutzerklärung von Klarna: https://www.klarna.com/de/datenschutz/</p>
<p>Apple Pay und Google Pay: Bei Auswahl von Apple Pay oder Google Pay wird die Zahlung über den jeweiligen Wallet-Dienst freigegeben und anschließend über Stripe abgewickelt. Verantwortliche für den jeweiligen Wallet-Dienst sind die Apple Distribution International Ltd., Hollyhill Industrial Estate, Hollyhill, Cork, Irland (Datenschutzerklärung: https://www.apple.com/legal/privacy/de-ww/) bzw. die Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland (Datenschutzerklärung: https://policies.google.com/privacy). Auf dieser Website selbst sind keine Skripte oder Schnittstellen von Apple oder Google eingebunden; die Wallet-Dienste kommen erst im Buchungs- und Bezahlvorgang zum Einsatz.</p>
<p>Welche dieser Zahlungsarten im Einzelfall zum Einsatz kommt, entscheiden Sie selbst im Buchungsvorgang.</p>
<p>Speicherdauer: Buchungs-, Vertrags- und Zahlungsdaten werden für die Dauer der Vertragsdurchführung sowie darüber hinaus im Rahmen der gesetzlichen Aufbewahrungsfristen gespeichert (insbesondere gemäß § 257 HGB und § 147 AO in der Regel sechs bzw. zehn Jahre). Nach Ablauf dieser Fristen werden die Daten gelöscht.</p>
<h2 id="7a-warteliste">7a. Warteliste</h2>
<p>Auf unserer Startseite können Sie sich unverbindlich in eine Warteliste eintragen, um benachrichtigt zu werden, sobald der Termin für ein Retreat feststeht.</p>
<p>Verarbeitet werden dabei die von Ihnen angegebenen Daten:</p>
<ul>
<li>Vor- und Nachname</li>
<li>E-Mail-Adresse</li>
<li>Telefonnummer, sofern Sie sie freiwillig angeben</li>
<li>die von Ihnen angekreuzte Buchungsvariante, für die Sie sich interessieren</li>
<li>Zeitpunkt Ihres Eintrags sowie der Wortlaut der Einwilligung, der Ihnen dabei angezeigt wurde</li>
<li>ein nicht rückrechenbarer Prüfwert Ihrer IP-Adresse</li>
</ul>
<p>Zweck: Sie zu benachrichtigen, sobald ein Termin feststeht, und Ihre Anfrage zuordnen zu können. Der Prüfwert der IP-Adresse dient ausschließlich dazu, das massenhafte automatisierte Eintragen fremder Adressen zu unterbinden; die IP-Adresse selbst wird nicht gespeichert.</p>
<p>Rechtsgrundlagen: Art. 6 Abs. 1 lit. a DSGVO (Ihre Einwilligung) für die Benachrichtigung; Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse am Schutz des Formulars vor Missbrauch) für den Prüfwert der IP-Adresse. Die Dokumentation Ihrer Einwilligung erfolgt zur Erfüllung unserer Nachweispflicht nach Art. 5 Abs. 2 DSGVO.</p>
<p>Ihre Angaben werden in der Datenbank dieser Website gespeichert und zusätzlich per E-Mail an unsere Adresse hello@b3-retreats.de übermittelt.</p>
<p>Widerruf: Sie können Ihre Einwilligung jederzeit und ohne Angabe von Gründen formlos widerrufen, zum Beispiel per E-Mail an hello@b3-retreats.de. Die Rechtmäßigkeit der bis zum Widerruf erfolgten Verarbeitung bleibt davon unberührt.</p>
<p>Speicherdauer: Wir löschen Ihren Eintrag, sobald Sie widerrufen, spätestens jedoch, wenn der Zweck entfällt – also nachdem wir Sie über den Termin benachrichtigt haben und Sie sich nicht angemeldet haben, längstens nach zwölf Monaten. Eine Weitergabe an Dritte zu Werbezwecken findet nicht statt.</p>
<h2 id="8-schriftarten-google-fonts-lokal-gehostet">8. Schriftarten (Google Fonts – lokal gehostet)</h2>
<p>Diese Website nutzt zur einheitlichen Darstellung von Schriftarten sogenannte Web Fonts. Die verwendeten Schriften (u. a. Google Fonts) sind ausschließlich lokal auf dem Server dieser Website installiert und werden von dort ausgeliefert.</p>
<p>Eine Verbindung zu Servern von Google wird dabei ausdrücklich nicht hergestellt. Beim Aufruf dieser Website werden daher keine Daten – insbesondere nicht Ihre IP-Adresse – an Google übertragen.</p>
<p>Die Verwendung lokal gehosteter Schriftarten erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Wir haben ein berechtigtes Interesse an einer einheitlichen, ansprechenden und technisch zuverlässigen Darstellung unseres Onlineangebots ohne Datenübermittlung an Dritte.</p>
<p>Weitere Informationen zu Google Fonts finden Sie unter https://developers.google.com/fonts/faq und in der Datenschutzerklärung von Google unter https://policies.google.com/privacy.</p>
<p>[BITTE ERGÄNZEN / TECHNISCH PRÜFEN: Vor dem Livegang bitte sicherstellen, dass die Website keinerlei Anfragen an fonts.googleapis.com oder fonts.gstatic.com sendet – auch nicht über eingebundene Themes, Page-Builder, Plugins oder Icon-Bibliotheken. Andernfalls ist entweder die Einbindung auf lokales Hosting umzustellen oder dieser Abschnitt entsprechend anzupassen und eine Einwilligungslösung vorzusehen.]</p>
<h2 id="9-cookies">9. Cookies</h2>
<p>Unsere Website verwendet ausschließlich technisch notwendige Cookies. Cookies sind kleine Textdateien, die auf Ihrem Endgerät gespeichert werden und keinen Schaden anrichten. Sie enthalten keine Viren, Trojaner oder sonstige Schadsoftware.</p>
<p>Wir setzen keine Cookies zu Analyse-, Tracking-, Retargeting- oder Marketingzwecken ein. Es findet keine Erstellung von Nutzungsprofilen statt. Es sind keine Cookies von Drittanbietern eingebunden, die eine Einwilligung erfordern würden.</p>
<p>Technisch notwendige Cookies sind solche, die erforderlich sind, um bestimmte von Ihnen gewünschte Funktionen der Website bereitzustellen oder um die Sicherheit und Funktionsfähigkeit der Seite zu gewährleisten (z. B. Sitzungsverwaltung, Speicherung von Spracheinstellungen, Schutz vor Missbrauch).</p>
<p>Rechtsgrundlage: Die Speicherung technisch notwendiger Cookies bzw. der Zugriff auf Informationen in Ihrem Endgerät erfolgt gemäß § 25 Abs. 2 Nr. 2 TDDDG ohne Einwilligung, da sie für die Bereitstellung des von Ihnen ausdrücklich gewünschten Dienstes unbedingt erforderlich sind. Die weitere Verarbeitung stützt sich auf Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse an einer technisch einwandfreien und sicheren Bereitstellung unseres Angebots).</p>
<p>Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden, Cookies nur im Einzelfall erlauben, die Annahme von Cookies für bestimmte Fälle oder generell ausschließen sowie das automatische Löschen der Cookies beim Schließen des Browsers aktivieren. Bei der Deaktivierung von Cookies kann die Funktionalität dieser Website eingeschränkt sein.</p>
<p>Hinweis: Der Buchungsvorgang findet auf einer Bezahlseite von Stripe statt. Dort können weitere Cookies gesetzt werden; hierfür gelten die Datenschutz- und Cookie-Hinweise von Stripe.</p>
<h2 id="10-einwilligungsbanner-und-lokale-speicherung-ih">10. Einwilligungsbanner und lokale Speicherung Ihrer Entscheidung</h2>
<p>Beim ersten Aufruf unserer Website erhalten Sie einen Hinweis zu Cookies mit den Auswahlmöglichkeiten „Nur notwendige“ und „Alle akzeptieren“.</p>
<p>Zum Zeitpunkt der Veröffentlichung dieser Erklärung setzen wir keine einwilligungspflichtigen Dienste ein. Die Auswahl „Alle akzeptieren“ hat daher derzeit keine zusätzliche Datenverarbeitung zur Folge. Wir zeigen den Hinweis dennoch an, damit Sie Ihre Entscheidung von Anfang an selbst treffen und jederzeit ändern können.</p>
<p>Ihre Entscheidung wird ausschließlich lokal in Ihrem Browser gespeichert (Local Storage, Schlüssel „b3-consent“), damit wir Sie nicht bei jedem Besuch erneut fragen müssen. Gespeichert werden lediglich die getroffene Auswahl und der Zeitpunkt der Entscheidung. Es werden dabei keine personenbezogenen Daten an uns oder an Dritte übertragen.</p>
<p>Rechtsgrundlage: § 25 Abs. 2 Nr. 2 TDDDG. Die Speicherung der Entscheidung ist unbedingt erforderlich, um Ihre Auswahl umzusetzen.</p>
<p>Sie können Ihre Entscheidung jederzeit ändern. Nutzen Sie hierfür den Link „Cookie-Einstellungen“ im Fußbereich jeder Seite. Alternativ können Sie den Local Storage Ihres Browsers für diese Website löschen; beim nächsten Aufruf werden Sie dann erneut gefragt.</p>
<h2 id="11-ssl-bzw-tls-verschluesselung">11. SSL- bzw. TLS-Verschlüsselung</h2>
<p>Diese Seite nutzt aus Sicherheitsgründen und zum Schutz der Übertragung vertraulicher Inhalte eine SSL- bzw. TLS-Verschlüsselung. Eine verschlüsselte Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von „http://“ auf „https://“ wechselt und an dem Schloss-Symbol in Ihrer Browserzeile.</p>
<p>Wenn die SSL- bzw. TLS-Verschlüsselung aktiviert ist, können die Daten, die Sie an uns übermitteln, nicht von Dritten mitgelesen werden.</p>
<h2 id="12-speicherdauer">12. Speicherdauer</h2>
<p>Soweit innerhalb dieser Datenschutzerklärung keine speziellere Speicherdauer genannt wird, verbleiben Ihre personenbezogenen Daten bei uns, bis der Zweck für die Datenverarbeitung entfällt.</p>
<p>Wenn Sie ein berechtigtes Löschersuchen geltend machen oder eine Einwilligung zur Datenverarbeitung widerrufen, werden Ihre Daten gelöscht, sofern wir keine anderen rechtlich zulässigen Gründe für die Speicherung Ihrer personenbezogenen Daten haben. Zu solchen Gründen zählen insbesondere gesetzliche Aufbewahrungsfristen nach Handels- und Steuerrecht (§ 257 HGB, § 147 AO) sowie die Erforderlichkeit zur Geltendmachung, Ausübung oder Verteidigung von Rechtsansprüchen. Im letztgenannten Fall erfolgt die Löschung nach Fortfall dieser Gründe.</p>
<h2 id="13-ihre-rechte-als-betroffene-person-art-15-bis">13. Ihre Rechte als betroffene Person (Art. 15 bis 21 DSGVO)</h2>
<p>Ihnen stehen im Hinblick auf die Verarbeitung Ihrer personenbezogenen Daten die folgenden Rechte zu:</p>
<ul>
  <li>Auskunftsrecht (Art. 15 DSGVO): Sie haben das Recht, jederzeit unentgeltlich Auskunft über die zu Ihrer Person gespeicherten personenbezogenen Daten, deren Herkunft und Empfänger sowie den Zweck der Datenverarbeitung zu erhalten.</li>
  <li>Recht auf Berichtigung (Art. 16 DSGVO): Sie haben das Recht, die unverzügliche Berichtigung unrichtiger oder die Vervollständigung unvollständiger personenbezogener Daten zu verlangen.</li>
  <li>Recht auf Löschung (Art. 17 DSGVO): Sie haben das Recht, die Löschung Ihrer personenbezogenen Daten zu verlangen, sofern einer der gesetzlich vorgesehenen Gründe vorliegt und keine gesetzlichen Aufbewahrungspflichten entgegenstehen.</li>
  <li>Recht auf Einschränkung der Verarbeitung (Art. 18 DSGVO): Sie haben das Recht, die Einschränkung der Verarbeitung Ihrer personenbezogenen Daten zu verlangen, etwa wenn Sie die Richtigkeit der Daten bestreiten oder die Verarbeitung unrechtmäßig ist, Sie aber statt der Löschung eine Einschränkung wünschen.</li>
  <li>Recht auf Datenübertragbarkeit (Art. 20 DSGVO): Sie haben das Recht, Daten, die wir auf Grundlage Ihrer Einwilligung oder in Erfüllung eines Vertrags automatisiert verarbeiten, in einem gängigen, maschinenlesbaren Format an sich oder an eine bzw. einen Dritten aushändigen zu lassen. Sofern Sie die direkte Übertragung der Daten an eine andere Verantwortliche bzw. einen anderen Verantwortlichen verlangen, erfolgt dies nur, soweit es technisch machbar ist.</li>
  <li>Widerspruchsrecht (Art. 21 DSGVO): Wenn die Verarbeitung Ihrer Daten auf Grundlage von Art. 6 Abs. 1 lit. e oder lit. f DSGVO erfolgt, haben Sie jederzeit das Recht, aus Gründen, die sich aus Ihrer besonderen Situation ergeben, gegen diese Verarbeitung Widerspruch einzulegen. Wir verarbeiten die betroffenen personenbezogenen Daten dann nicht mehr, es sei denn, wir können zwingende schutzwürdige Gründe für die Verarbeitung nachweisen, die Ihre Interessen, Rechte und Freiheiten überwiegen, oder die Verarbeitung dient der Geltendmachung, Ausübung oder Verteidigung von Rechtsansprüchen. Werden Ihre personenbezogenen Daten verarbeitet, um Direktwerbung zu betreiben, haben Sie das Recht, jederzeit ohne Angabe von Gründen Widerspruch einzulegen; Ihre Daten werden dann nicht mehr zu diesem Zweck verwendet.</li>
  <li>Widerruf einer erteilten Einwilligung (Art. 7 Abs. 3 DSGVO): Viele Datenverarbeitungsvorgänge sind nur mit Ihrer ausdrücklichen Einwilligung möglich. Sie können eine bereits erteilte Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen. Die Rechtmäßigkeit der bis zum Widerruf erfolgten Datenverarbeitung bleibt vom Widerruf unberührt.</li>
</ul>
<p>Zur Ausübung Ihrer Rechte genügt eine formlose Mitteilung an die unter „Verantwortliche Stelle“ genannten Kontaktdaten. Die Ausübung Ihrer Rechte ist für Sie grundsätzlich kostenfrei. Zur Prüfung Ihrer Identität können wir zusätzliche Informationen anfordern.</p>
<h2 id="14-beschwerderecht-bei-der-zustaendigen-aufsicht">14. Beschwerderecht bei der zuständigen Aufsichtsbehörde</h2>
<p>Im Falle von Verstößen gegen die DSGVO steht den Betroffenen ein Beschwerderecht bei einer Aufsichtsbehörde zu, insbesondere in dem Mitgliedstaat ihres gewöhnlichen Aufenthalts, ihres Arbeitsplatzes oder des Orts des mutmaßlichen Verstoßes. Das Beschwerderecht besteht unbeschadet anderweitiger verwaltungsrechtlicher oder gerichtlicher Rechtsbehelfe.</p>
<p>Die für uns zuständige Aufsichtsbehörde ist:</p>
<p>Der Landesbeauftragte für den Datenschutz und die Informationsfreiheit Rheinland-Pfalz Hintere Bleiche 34 55116 Mainz Deutschland</p>
<p>Postanschrift: Postfach 30 40, 55020 Mainz<br>
Telefon: +49 6131 8920-0<br>
Telefax: +49 6131 8920-299<br>
E-Mail: poststelle@datenschutz.rlp.de<br>
Website: https://www.datenschutz.rlp.de</p>
<h2 id="15-keine-automatisierte-entscheidungsfindung">15. Keine automatisierte Entscheidungsfindung</h2>
<p>Eine automatisierte Entscheidungsfindung einschließlich Profiling im Sinne des Art. 22 Abs. 1 und 4 DSGVO findet nicht statt.</p>
<h2 id="16-aenderungen-dieser-datenschutzerklaerung">16. Änderungen dieser Datenschutzerklärung</h2>
<p>Wir behalten uns vor, diese Datenschutzerklärung anzupassen, damit sie stets den aktuellen rechtlichen Anforderungen entspricht oder um Änderungen unserer Leistungen in der Datenschutzerklärung umzusetzen, z. B. bei der Einführung neuer Angebote, Funktionen oder eingesetzter Dienste.</p>
<p>Für Ihren erneuten Besuch gilt dann die jeweils aktuelle, auf dieser Seite veröffentlichte Fassung. Wir empfehlen Ihnen daher, diese Datenschutzerklärung von Zeit zu Zeit erneut zu lesen.</p>
<h2 id="17-stand">17. Stand</h2>
<p>Stand dieser Datenschutzerklärung: August 2026</p>', 'html'),
('recht.agb.title', 'Allgemeine Geschäftsbedingungen', 'text'),
('recht.agb.h1', 'AGB', 'text'),
('recht.agb.desc', 'Allgemeine Geschäftsbedingungen für die Teilnahme an B³ Retreats.', 'textarea'),
('recht.agb.body', '<p>für B³ Retreats</p>
<p><strong>Anbieterin und Vertragspartnerin:</strong><br>
Christina Brumm<br>
An den Nahewiesen 20<br>
55450 Langenlonsheim<br>
Deutschland<br>
E-Mail: <a href="mailto:hello@b3-retreats.de">hello@b3-retreats.de</a></p>
<p>nachfolgend „Veranstalterin“.</p>
<h2 id="1-geltungsbereich">§ 1 Geltungsbereich</h2>
<p>(1) Diese Allgemeinen Geschäftsbedingungen gelten für Verträge über die Teilnahme an Retreats,
die von Christina Brumm insbesondere unter der Bezeichnung „B³ Retreats“ angeboten werden.</p>
<p>(2) Das Angebot kann sich sowohl an Verbraucherinnen im Sinne des § 13 BGB
als auch an Unternehmerinnen im Sinne des § 14 BGB richten.</p>
<p>(3) Vertragspartnerin der Teilnehmerin ist ausschließlich Christina Brumm.</p>
<p>(4) Die Veranstalterin kann zur Durchführung eines Retreats selbstständige
Kooperationspartnerinnen, Coaches, Trainerinnen oder sonstige Dienstleisterinnen einsetzen.
Hierdurch entsteht grundsätzlich kein gesondertes Vertragsverhältnis zwischen der Teilnehmerin
und der jeweiligen Kooperationspartnerin, soweit eine Zusatzleistung nicht ausdrücklich
unmittelbar bei dieser gebucht wird.</p>
<h2 id="2-leistungsbeschreibung-des-jeweiligen-retreats">§ 2 Leistungsbeschreibung des jeweiligen Retreats</h2>
<p>(1) Die konkrete Ausgestaltung eines Retreats ergibt sich aus der zum Zeitpunkt der Buchung
geltenden jeweiligen Angebots- und Leistungsbeschreibung. Dort werden insbesondere der Termin
und die Dauer des Retreats, der Veranstaltungsort, die enthaltene Unterkunft und Verpflegung,
das Retreat-Programm, die enthaltenen Sessions und sonstigen Leistungen, die An- und Abreisezeiten,
der Gesamtpreis sowie gegebenenfalls verfügbare Buchungsoptionen und die Mindestteilnehmerinnenzahl
beschrieben.</p>
<p>(2) Die jeweilige Leistungsbeschreibung wird Bestandteil des zwischen der Veranstalterin und
der Teilnehmerin geschlossenen Vertrages.</p>
<p>(3) Im Retreat-Preis sind ausschließlich diejenigen Leistungen enthalten, die in der jeweiligen
Leistungsbeschreibung ausdrücklich als enthalten bezeichnet werden.</p>
<p>(4) Die An- und Abreise zum Retreat-Ort ist nicht Bestandteil der von der Veranstalterin
geschuldeten Leistungen und erfolgt, soweit in der jeweiligen Leistungsbeschreibung nicht
ausdrücklich etwas anderes angegeben ist, eigenverantwortlich und auf eigene Kosten der Teilnehmerin.</p>
<p>(5) Soweit auf den jeweiligen Vertrag zwingende gesetzliche Vorschriften Anwendung finden,
insbesondere besondere verbraucherschützende Vorschriften, bleiben diese durch diese
Allgemeinen Geschäftsbedingungen unberührt.</p>
<h2 id="3-vertragsschluss">§ 3 Vertragsschluss</h2>
<p>(1) Die Darstellung eines Retreats auf einer Website, Landingpage oder sonstigen Verkaufsseite
stellt noch kein verbindliches Vertragsangebot dar, sondern eine Aufforderung zur Abgabe einer Buchung.</p>
<p>(2) Die Buchung erfolgt über den für das jeweilige Retreat bereitgestellten Buchungs- und Zahlungsprozess.</p>
<p>(3) Die technische Zahlungsabwicklung erfolgt über den externen Zahlungsdienstleister Stripe.
Je nach Verfügbarkeit können innerhalb des Stripe-Bezahlvorgangs verschiedene Zahlungsmethoden
angeboten werden.</p>
<p>(4) Mit Abschluss des zahlungspflichtigen Buchungsvorgangs gibt die Teilnehmerin eine verbindliche
Buchung für das ausgewählte Retreat ab.</p>
<p>(5) Der Vertrag über die Teilnahme am ausgewählten Retreat kommt mit dem erfolgreichen Abschluss
des Buchungs- und Zahlungsvorgangs und der entsprechenden Bestätigung zustande, soweit im
Buchungsprozess nichts Abweichendes angegeben ist.</p>
<p>(6) Die Teilnehmerin ist verpflichtet, die im Rahmen der Buchung abgefragten Angaben vollständig
und wahrheitsgemäß zu machen. Insbesondere hat sie eine gültige E-Mail-Adresse anzugeben, über die
ihr Buchungsunterlagen, die Rechnung und weitere Informationen zum Retreat übermittelt werden können.</p>
<h2 id="4-preise-zahlung-und-rechnung">§ 4 Preise, Zahlung und Rechnung</h2>
<p>(1) Maßgeblich ist der für das jeweilige Retreat zum Zeitpunkt der Buchung ausgewiesene Gesamtpreis.</p>
<p>(2) Gegenüber Verbraucherinnen werden die Preise einschließlich der gesetzlich geschuldeten
Umsatzsteuer angegeben.</p>
<p>(3) Der vereinbarte Gesamtpreis ist grundsätzlich unmittelbar im Rahmen des Buchungsvorgangs zur
Zahlung fällig. Die Zahlungsabwicklung erfolgt über Stripe. Die jeweils tatsächlich verfügbaren
Zahlungsmethoden werden der Teilnehmerin im Bezahlvorgang angezeigt.</p>
<p>(4) Sofern im Rahmen des Stripe-Bezahlvorgangs eine Zahlung über Klarna angeboten und von der
Teilnehmerin ausgewählt wird, erfolgt die weitere Zahlungsabwicklung nach den Bedingungen von Klarna.
Je nach Verfügbarkeit und individueller Berechtigung der Teilnehmerin kann Klarna insbesondere eine
Raten- oder Finanzierungszahlung anbieten.</p>
<p>(5) Ob und zu welchen Konditionen Klarna eine Raten- oder Finanzierungszahlung ermöglicht, richtet
sich ausschließlich nach den Voraussetzungen und Bedingungen von Klarna. Auf die Entscheidung von
Klarna über die Freigabe einer solchen Zahlungsoption hat die Veranstalterin keinen Einfluss.</p>
<p>(6) Die Veranstalterin selbst bietet keine eigene Ratenzahlung oder individuelle Teilzahlungsvereinbarung
an. Eine über Klarna gewählte Raten- oder Finanzierungszahlung verändert weder den vereinbarten
Gesamtpreis des Retreats noch die Verbindlichkeit der Buchung.</p>
<p>(7) Nach erfolgreichem Abschluss des Zahlungsvorgangs erhält die Teilnehmerin eine Rechnung über den
gebuchten Retreat-Platz. Die Rechnung wird grundsätzlich elektronisch an die im Rahmen der Buchung
angegebene E-Mail-Adresse übermittelt.</p>
<p>(8) Bei einer über Klarna abgewickelten Zahlung gelten für das Verhältnis zwischen der Teilnehmerin
und Klarna hinsichtlich der Zahlungsabwicklung zusätzlich die jeweiligen Bedingungen von Klarna.</p>
<p>(9) Bei Zahlungsverzug gelten die gesetzlichen Vorschriften.</p>
<h2 id="5-an-und-abreise">§ 5 An- und Abreise</h2>
<p>(1) Die An- und Abreise zum jeweiligen Retreat-Ort erfolgt eigenverantwortlich und auf eigene Kosten
der Teilnehmerin, soweit in der jeweiligen Leistungsbeschreibung nicht ausdrücklich etwas anderes
vereinbart wird.</p>
<p>(2) Die Teilnehmerin ist selbst dafür verantwortlich, ihre An- und Abreise rechtzeitig und entsprechend
der kommunizierten An- und Abreisezeiten zu organisieren.</p>
<p>(3) Persönliche Reise-, Fahrt- oder sonstige Nebenkosten sind nicht im Retreat-Preis enthalten,
soweit nicht ausdrücklich etwas anderes angegeben wird.</p>
<h2 id="6-unterkunft">§ 6 Unterkunft</h2>
<p>(1) Art und Umfang der Unterkunft ergeben sich aus der jeweiligen Retreat-Beschreibung und der
gebuchten Kategorie.</p>
<p>(2) Soweit keine konkrete Zimmerkategorie, Zimmernummer, Bettart oder Zimmerbelegung ausdrücklich
zugesichert wird, besteht hierauf kein Anspruch.</p>
<p>(3) Die konkrete Zimmeraufteilung kann durch die Veranstalterin entsprechend der Buchungssituation
und den räumlichen Gegebenheiten vorgenommen werden.</p>
<p>(4) Besondere Unterkunftsoptionen oder Kategorien gelten nur dann als vereinbart, wenn sie ausdrücklich
gebucht und bestätigt wurden.</p>
<h2 id="7-verpflegung-und-besondere-ernaehrungsanforderu">§ 7 Verpflegung und besondere Ernährungsanforderungen</h2>
<p>(1) Art und Umfang der enthaltenen Verpflegung ergeben sich aus der jeweiligen Retreat-Beschreibung.</p>
<p>(2) Soweit verschiedene Ernährungsformen angeboten werden, können ausschließlich die im jeweiligen
Buchungsprozess ausdrücklich angebotenen Optionen verbindlich ausgewählt werden.</p>
<p>(3) Ein Anspruch auf die Berücksichtigung weiterer individueller Ernährungswünsche, Allergien oder
Unverträglichkeiten besteht nur, wenn deren Berücksichtigung vorab ausdrücklich von der Veranstalterin
bestätigt wurde.</p>
<p>(4) Teilnehmerinnen mit Lebensmittelallergien, Unverträglichkeiten oder medizinisch bedingten
Ernährungsanforderungen sind dafür verantwortlich, vor der Buchung zu prüfen, ob das angebotene
Verpflegungskonzept für sie geeignet ist.</p>
<p>(5) Eine vollständig allergenfreie Zubereitung oder die Vermeidung von Kreuzkontaminationen wird nur
geschuldet, wenn dies ausdrücklich vereinbart wurde.</p>
<h2 id="8-programm-und-aenderungen">§ 8 Programm und Änderungen</h2>
<p>(1) Inhalt und Umfang des Retreat-Programms ergeben sich aus der jeweiligen Leistungsbeschreibung.</p>
<p>(2) Die Veranstalterin ist berechtigt, einzelne Programmpunkte, Zeiten, Abläufe oder eingesetzte Personen
aus sachlichem Grund anzupassen, soweit der Gesamtcharakter und der wesentliche Leistungsumfang des
gebuchten Retreats dadurch nicht erheblich verändert werden und die Änderung für die Teilnehmerin
zumutbar ist.</p>
<p>(3) Sachliche Gründe können insbesondere Krankheit oder sonstige Verhinderung einer eingeplanten Person,
wetterbedingte Umstände, organisatorische Erfordernisse oder nicht vorhersehbare Umstände am Retreat-Ort sein.</p>
<p>(4) Soweit erforderlich, kann eine fachlich geeignete Ersatzperson eingesetzt werden.</p>
<p>(5) Wesentliche Änderungen der vertraglich geschuldeten Hauptleistungen richten sich nach den
gesetzlichen Vorschriften.</p>
<h2 id="9-freiwilligkeit-der-teilnahme">§ 9 Freiwilligkeit der Teilnahme</h2>
<p>(1) Die Teilnahme an einzelnen Sessions, Übungen und sonstigen Retreat-Aktivitäten ist grundsätzlich
freiwillig, soweit in der jeweiligen Leistungsbeschreibung nichts anderes ausdrücklich angegeben wird.</p>
<p>(2) Teilnehmerinnen können einzelne Programmpunkte auslassen und die entsprechend vorgesehene Zeit
individuell nutzen.</p>
<p>(3) Die freiwillige Nichtteilnahme an einzelnen angebotenen Programmpunkten begründet keinen Anspruch
auf vollständige oder anteilige Rückerstattung des Retreat-Preises.</p>
<h2 id="10-gesundheit-und-eigenverantwortung">§ 10 Gesundheit und Eigenverantwortung</h2>
<p>(1) Die im Rahmen eines Retreats angebotenen Inhalte können je nach Retreat insbesondere Yoga, Breathwork,
Bodywork, Coaching, Mentoring, Astrologie, Energiearbeit, Meditation oder vergleichbare Angebote umfassen.</p>
<p>(2) Diese Angebote ersetzen keine medizinische, psychiatrische, psychotherapeutische oder sonstige
heilkundliche Diagnose oder Behandlung.</p>
<p>(3) Jede Teilnehmerin entscheidet eigenverantwortlich, ob und in welchem Umfang eine angebotene Aktivität
für sie geeignet ist.</p>
<p>(4) Bei bestehenden gesundheitlichen Einschränkungen, Schwangerschaft, akuten oder chronischen Erkrankungen,
psychischen Erkrankungen oder sonstigen Umständen, aufgrund derer eine angebotene Aktivität möglicherweise
nicht geeignet sein könnte, ist die Teilnehmerin dafür verantwortlich, ihre Teilnahme erforderlichenfalls
vorab mit einer entsprechend qualifizierten medizinischen oder therapeutischen Fachperson abzuklären.</p>
<p>(5) Sicherheits- und Durchführungshinweise der jeweiligen durchführenden Person sind zu beachten.</p>
<p>(6) Bei auftretenden Beschwerden soll eine Übung oder Session unterbrochen und die durchführende Person
informiert werden.</p>
<p>(7) Die vorstehenden Bestimmungen führen nicht zu einer Einschränkung gesetzlicher Haftungsansprüche.</p>
<h2 id="11-individuelle-zusatzleistungen">§ 11 Individuelle Zusatzleistungen</h2>
<p>(1) Im Rahmen eines Retreats können zusätzliche Leistungen angeboten werden, die nicht im Retreat-Preis
enthalten sind. Hierzu können insbesondere individuelle 1:1-Sessions gehören.</p>
<p>(2) Ob eine solche Zusatzleistung unmittelbar durch die Veranstalterin oder eigenständig durch eine
Kooperationspartnerin angeboten wird, ergibt sich aus dem jeweiligen Angebot.</p>
<p>(3) Für individuell hinzugebuchte Leistungen können gesonderte Vertrags- und Zahlungsbedingungen gelten.</p>
<h2 id="12-stornierung-durch-die-teilnehmerin">§ 12 Stornierung durch die Teilnehmerin</h2>
<p>(1) Die Buchung eines Retreat-Platzes ist verbindlich.</p>
<p>(2) Unabhängig von etwaigen gesetzlichen Rechten räumt die Veranstalterin der Teilnehmerin die Möglichkeit ein,
ihre Teilnahme in Textform zu stornieren.</p>
<p>(3) Maßgeblich für die Berechnung der Stornokosten ist der Zugang der Stornierung bei der Veranstalterin.
Bei einer Stornierung werden folgende pauschalierte Stornokosten bezogen auf den vereinbarten Gesamtpreis berechnet:</p>
<p>(4) Bereits geleistete Zahlungen werden mit den geschuldeten Stornokosten verrechnet.
Ein gegebenenfalls darüber hinaus bereits gezahlter Betrag wird zurückerstattet.</p>
<p>(5) Der Teilnehmerin bleibt ausdrücklich der Nachweis gestattet, dass der Veranstalterin infolge der
Stornierung kein Schaden oder ein wesentlich geringerer Schaden entstanden ist als die jeweils vorgesehene Pauschale.</p>
<p>(6) Zwingende gesetzliche Rücktritts-, Kündigungs- und sonstige Rechte der Teilnehmerin bleiben unberührt.</p>
<h2 id="13-ersatzteilnehmerin">§ 13 Ersatzteilnehmerin</h2>
<p>(1) Kann eine Teilnehmerin nicht am Retreat teilnehmen, kann sie der Veranstalterin eine geeignete
Ersatzteilnehmerin vorschlagen.</p>
<p>(2) Die Übertragung der Buchung bedarf der vorherigen Zustimmung der Veranstalterin.
Die Zustimmung darf nicht ohne sachlichen Grund verweigert werden.</p>
<p>(3) Voraussetzung ist insbesondere, dass die Ersatzteilnehmerin die Teilnahmevoraussetzungen erfüllt
und die für das Retreat geltenden Vertragsbedingungen akzeptiert.</p>
<p>(4) Nachweislich aufgrund des Austauschs entstehende zusätzliche Kosten können der ursprünglichen
Teilnehmerin in Rechnung gestellt werden.</p>
<h2 id="14-mindestteilnehmerinnenzahl-und-absage">§ 14 Mindestteilnehmerinnenzahl und Absage</h2>
<p>(1) Für einzelne Retreats kann eine Mindestteilnehmerinnenzahl vorgesehen werden.</p>
<p>(2) Voraussetzung hierfür ist, dass die konkrete Mindestteilnehmerinnenzahl vor Vertragsschluss in der
jeweiligen Retreat-Beschreibung oder im Buchungsprozess angegeben wird.</p>
<p>(3) Ebenfalls wird vor Vertragsschluss angegeben, bis zu welchem Zeitpunkt die Veranstalterin das Retreat
wegen Nichterreichens der Mindestteilnehmerinnenzahl absagen kann.</p>
<p>(4) Wird die für das jeweilige Retreat kommunizierte Mindestteilnehmerinnenzahl innerhalb der angegebenen
Frist nicht erreicht, ist die Veranstalterin berechtigt, das Retreat abzusagen.</p>
<p>(5) Im Fall einer solchen Absage werden die von den Teilnehmerinnen bereits geleisteten Zahlungen für
das Retreat vollständig zurückerstattet.</p>
<p>(6) Weitere Aufwendungen der Teilnehmerin, insbesondere selbst gebuchte Reiseleistungen, werden nur ersetzt,
soweit hierfür eine gesetzliche Verpflichtung besteht.</p>
<p>(7) Muss ein Retreat aus anderen Gründen vollständig abgesagt werden, richten sich die Rechte der
Teilnehmerinnen nach den gesetzlichen Vorschriften.</p>
<p>(8) Die Veranstalterin informiert die betroffenen Teilnehmerinnen über eine Absage unverzüglich über die
bei der Buchung angegebenen Kontaktdaten.</p>
<h2 id="15-aussergewoehnliche-umstaende">§ 15 Außergewöhnliche Umstände</h2>
<p>Treten außergewöhnliche, von der Veranstalterin nicht zu vertretende Umstände ein, welche die Durchführung
eines Retreats unmöglich machen oder wesentlich beeinträchtigen, richten sich die Rechte und Pflichten der
Parteien nach den gesetzlichen Vorschriften. Die Veranstalterin informiert die Teilnehmerinnen über
entsprechende wesentliche Änderungen oder eine erforderliche Absage so früh wie möglich.</p>
<h2 id="16-widerrufsrecht">§ 16 Widerrufsrecht</h2>
<p>Die Retreats werden für einen konkret festgelegten Termin beziehungsweise Zeitraum angeboten.</p>
<p>Für Verträge über Dienstleistungen im Zusammenhang mit Freizeitbetätigungen sowie für Verträge über die
Bereitstellung von Beherbergungsleistungen zu anderen Zwecken als zu Wohnzwecken besteht gemäß
§ 312g Abs. 2 Nr. 9 BGB unter den dort genannten gesetzlichen Voraussetzungen kein Widerrufsrecht,
wenn für die Leistungserbringung ein spezifischer Termin oder Zeitraum vorgesehen ist.</p>
<p>Ob und in welchem Umfang ein gesetzliches Widerrufsrecht besteht, richtet sich nach den für den jeweiligen
Vertrag geltenden gesetzlichen Vorschriften.</p>
<p>Etwaige zwingende gesetzliche Rechte der Teilnehmerin sowie die in diesen AGB eingeräumten
Stornierungsmöglichkeiten bleiben hiervon unberührt.</p>
<h2 id="17-haftung">§ 17 Haftung</h2>
<p>(1) Die Veranstalterin haftet unbeschränkt für vorsätzlich oder grob fahrlässig verursachte Schäden.</p>
<p>(2) Die Veranstalterin haftet ebenfalls nach den gesetzlichen Vorschriften für Schäden aus der Verletzung
des Lebens, des Körpers oder der Gesundheit.</p>
<p>(3) Bei leicht fahrlässiger Verletzung wesentlicher Vertragspflichten haftet die Veranstalterin nach Maßgabe
der gesetzlichen Vorschriften. Soweit gesetzlich zulässig, ist die Haftung auf den bei Vertragsschluss
vorhersehbaren und vertragstypischen Schaden begrenzt.</p>
<p>(4) Im Übrigen ist die Haftung für leicht fahrlässig verursachte Schäden ausgeschlossen, soweit dies gesetzlich
zulässig ist.</p>
<p>(5) Die vorstehenden Haftungsregelungen gelten entsprechend für gesetzliche Vertreterinnen und Vertreter
sowie Erfüllungsgehilfen der Veranstalterin.</p>
<h2 id="18-persoenliche-gegenstaende">§ 18 Persönliche Gegenstände</h2>
<p>Für persönliche Gegenstände und Wertgegenstände der Teilnehmerinnen haftet die Veranstalterin ausschließlich
nach Maßgabe von § 17 und den gesetzlichen Vorschriften. Teilnehmerinnen sind selbst dafür verantwortlich,
ihre persönlichen Gegenstände angemessen zu sichern.</p>
<h2 id="19-verhalten-waehrend-des-retreats">§ 19 Verhalten während des Retreats</h2>
<p>(1) Die Teilnehmerinnen verpflichten sich zu einem respektvollen Umgang miteinander, mit den durchführenden
Personen sowie mit der Unterkunft und dem Retreat-Ort.</p>
<p>(2) Bei erheblichen oder wiederholten Störungen des Retreat-Ablaufs, einer Gefährdung anderer Personen
oder schwerwiegenden Verstößen gegen berechtigte Anweisungen kann die Veranstalterin nach erfolgloser
Abmahnung geeignete Maßnahmen ergreifen und als letztes Mittel die weitere Teilnahme untersagen.
Eine vorherige Abmahnung ist entbehrlich, wenn sie aufgrund der Schwere des Verhaltens nicht zumutbar ist.</p>
<p>(3) Die gesetzlichen Rechte der Parteien bleiben unberührt.</p>
<h2 id="20-foto-und-videoaufnahmen">§ 20 Foto- und Videoaufnahmen</h2>
<p>(1) Mit der Buchung eines Retreats erteilt eine Teilnehmerin nicht automatisch ihre Einwilligung, dass
erkennbare Foto- oder Videoaufnahmen von ihr zu Werbe- oder Marketingzwecken veröffentlicht werden dürfen.</p>
<p>(2) Soweit für entsprechende Aufnahmen und deren Veröffentlichung eine Einwilligung erforderlich ist,
wird diese gesondert eingeholt.</p>
<p>(3) Die Erteilung einer solchen Einwilligung ist freiwillig. Eine verweigerte Einwilligung hat keine
Auswirkungen auf die Teilnahme am Retreat.</p>
<h2 id="21-datenschutz">§ 21 Datenschutz</h2>
<p>Informationen über die Verarbeitung personenbezogener Daten ergeben sich aus der jeweils geltenden
Datenschutzerklärung der Veranstalterin.</p>
<p>Soweit für Buchung und Zahlungsabwicklung externe Plattformen oder Zahlungsdienstleister, insbesondere
Stripe und gegebenenfalls Klarna, eingesetzt werden, erfolgt die dortige Datenverarbeitung zusätzlich
nach den für den jeweiligen Dienst geltenden Datenschutzbestimmungen.</p>
<h2 id="22-schlussbestimmungen">§ 22 Schlussbestimmungen</h2>
<p>(1) Es gilt das Recht der Bundesrepublik Deutschland.</p>
<p>(2) Gegenüber Verbraucherinnen gilt diese Rechtswahl nur insoweit, als hierdurch zwingende
Verbraucherschutzvorschriften des Staates ihres gewöhnlichen Aufenthalts nicht entzogen werden.</p>
<p>(3) Gegenüber Verbraucherinnen gelten die gesetzlichen Gerichtsstandsregelungen.</p>
<p>(4) Für Unternehmerinnen gelten ergänzend die gesetzlichen Regelungen über den Gerichtsstand.</p>
<p>(5) Sollten einzelne Bestimmungen dieser AGB ganz oder teilweise unwirksam sein oder werden, gelten insoweit
die gesetzlichen Vorschriften. Die Wirksamkeit des Vertrages im Übrigen bleibt hiervon unberührt.</p>
<hr>
<p><strong>Stand: August 2026</strong></p>
<p>Christina Brumm<br>
An den Nahewiesen 20<br>
55450 Langenlonsheim<br>
Deutschland<br>
E-Mail: <a href="mailto:hello@b3-retreats.de">hello@b3-retreats.de</a></p>', 'html'),
('recht.eyebrow', 'Rechtliches', 'text'),
('recht.zurueck', 'Zurück zur Startseite', 'text'),
('warteliste.eyebrow', 'Warteliste', 'text'),
('warteliste.headline', 'Warteliste', 'text'),
('warteliste.intro', 'Trag dich ganz unverbindlich in unsere Warteliste ein und erfahre als Erste, sobald der Termin für das Retreat feststeht.', 'textarea'),
('warteliste.label_vorname', 'Vorname', 'text'),
('warteliste.label_nachname', 'Name', 'text'),
('warteliste.label_email', 'E-Mail-Adresse', 'text'),
('warteliste.label_telefon', 'Telefonnummer (optional)', 'text'),
('warteliste.label_interesse', 'Interesse an', 'text'),
('warteliste.label_shared', 'Shared House', 'text'),
('warteliste.label_friends', 'Friends Special', 'text'),
('warteliste.einwilligung', 'Ich möchte benachrichtigt werden, sobald der Termin feststeht, und bin damit einverstanden, dass meine Angaben dafür gespeichert werden. Ich kann das jederzeit formlos widerrufen. Mehr dazu in der <a class="link" href="/datenschutz">Datenschutzerklärung</a>.', 'html'),
('warteliste.cta', 'Absenden', 'text'),
('warteliste.danke', 'Wie schön, dass du dabei sein möchtest! 🤍

Du stehst jetzt ganz unverbindlich auf unserer Warteliste. Sobald der Termin für das Retreat feststeht, erfährst du als eine der Ersten davon.

Wir freuen uns schon sehr auf alles, was kommt – und vielleicht sehen wir uns ganz bald beim Retreat!', 'textarea'),
('warteliste.fehler', 'Da hat leider etwas nicht geklappt. Bitte prüf noch einmal, ob Vor- und Nachname, eine gültige E-Mail-Adresse und das Häkchen zur Einwilligung gesetzt sind.', 'textarea'),
('warteliste.pruefen', 'Fast geschafft! Wir haben dir eine E-Mail geschickt.

Bitte klick auf den Bestätigungslink darin – erst danach stehst du auf der Warteliste. Schau notfalls kurz im Spam-Ordner nach.', 'textarea'),
('warteliste.link_ungueltig', 'Dieser Bestätigungslink gilt leider nicht mehr – er ist entweder abgelaufen oder wurde bereits verwendet. Trag dich einfach noch einmal ein, dann schicken wir dir einen neuen.', 'textarea'),
('haus.plaetze_muster', 'Noch {n} Plätze frei', 'text'),
('haus.plaetze_einer', 'Nur noch 1 Platz frei', 'text'),
('haus.ausgebucht', 'Ausgebucht', 'text'),
('haus.zur_warteliste', 'Auf die Warteliste', 'text')
ON DUPLICATE KEY UPDATE `v` = VALUES(`v`), `type` = VALUES(`type`);
