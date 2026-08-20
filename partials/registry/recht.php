<?php
/* B³ Retreats — Content-Register: Rechtstexte (Abschnitt 22).
   ERZEUGT von tools/build-registry.py aus admin/schema.json und
   content/site.json. Nicht von Hand aendern: der naechste Lauf
   ueberschreibt die Datei. Texte gehoeren in content/site.json,
   danach `python3 tools/build-registry.py`. */
return [
    'recht.impressum.title' => [
        'group' => '22 Rechtstexte',
        'label' => 'Impressum — Titel im Browser-Tab',
        'type' => 'text',
        'hint' => 'Steht im Reiter des Browsers und als Überschrift in Suchergebnissen.',
        'default' => 'Impressum',
    ],
    'recht.impressum.h1' => [
        'group' => '22 Rechtstexte',
        'label' => 'Impressum — Überschrift auf der Seite',
        'type' => 'text',
        'hint' => 'Die große Überschrift oben auf der Seite.',
        'default' => 'Impressum',
    ],
    'recht.impressum.desc' => [
        'group' => '22 Rechtstexte',
        'label' => 'Impressum — Beschreibung für Suchmaschinen',
        'type' => 'textarea',
        'hint' => 'Der Zweizeiler unter dem Titel in Suchergebnissen. Etwa 150 Zeichen.',
        'default' => 'Impressum und Anbieterkennzeichnung von B³ Retreats, Christina Brumm, Langenlonsheim.',
    ],
    'recht.impressum.body' => [
        'group' => '22 Rechtstexte',
        'label' => 'Impressum',
        'type' => 'html',
        'hint' => 'Der vollständige Text der Seite /impressum. Überschriften, Absätze und Listen über die Knöpfe im Editor. Pflichtangaben nach § 5 DDG nicht entfernen — Name, Anschrift, E-Mail und Umsatzsteuer-Angabe müssen stehen bleiben.',
        'default' => '<p>Angaben gemäß § 5 DDG (Digitale-Dienste-Gesetz, vormals § 5 TMG)</p>
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
<p>Stand dieses Impressums: August 2026</p>',
    ],
    'recht.datenschutz.title' => [
        'group' => '22 Rechtstexte',
        'label' => 'Datenschutz — Titel im Browser-Tab',
        'type' => 'text',
        'hint' => 'Steht im Reiter des Browsers und als Überschrift in Suchergebnissen.',
        'default' => 'Datenschutzerklärung',
    ],
    'recht.datenschutz.h1' => [
        'group' => '22 Rechtstexte',
        'label' => 'Datenschutz — Überschrift auf der Seite',
        'type' => 'text',
        'hint' => 'Die große Überschrift oben auf der Seite.',
        'default' => 'Datenschutz',
    ],
    'recht.datenschutz.desc' => [
        'group' => '22 Rechtstexte',
        'label' => 'Datenschutz — Beschreibung für Suchmaschinen',
        'type' => 'textarea',
        'hint' => 'Der Zweizeiler unter dem Titel in Suchergebnissen. Etwa 150 Zeichen.',
        'default' => 'Informationen zur Verarbeitung personenbezogener Daten auf b3-retreats.de nach Art. 13 DSGVO.',
    ],
    'recht.datenschutz.body' => [
        'group' => '22 Rechtstexte',
        'label' => 'Datenschutzerklärung',
        'type' => 'html',
        'hint' => 'Der vollständige Text der Seite /datenschutz. Vorsicht bei den Abschnitten zu Hosting, Stripe und den Betroffenenrechten: sie beschreiben, was technisch tatsächlich passiert. Ändert sich ein Dienst, muss der Text mitgeführt werden.',
        'default' => '<h2 id="datenschutzerklaerung">Datenschutzerklärung</h2>
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
<p>Stand dieser Datenschutzerklärung: August 2026</p>',
    ],
    'recht.agb.title' => [
        'group' => '22 Rechtstexte',
        'label' => 'AGB — Titel im Browser-Tab',
        'type' => 'text',
        'hint' => 'Steht im Reiter des Browsers und als Überschrift in Suchergebnissen.',
        'default' => 'Allgemeine Geschäftsbedingungen',
    ],
    'recht.agb.h1' => [
        'group' => '22 Rechtstexte',
        'label' => 'AGB — Überschrift auf der Seite',
        'type' => 'text',
        'hint' => 'Die große Überschrift oben auf der Seite.',
        'default' => 'AGB',
    ],
    'recht.agb.desc' => [
        'group' => '22 Rechtstexte',
        'label' => 'AGB — Beschreibung für Suchmaschinen',
        'type' => 'textarea',
        'hint' => 'Der Zweizeiler unter dem Titel in Suchergebnissen. Etwa 150 Zeichen.',
        'default' => 'Allgemeine Geschäftsbedingungen für die Teilnahme an B³ Retreats.',
    ],
    'recht.agb.body' => [
        'group' => '22 Rechtstexte',
        'label' => 'Allgemeine Geschäftsbedingungen',
        'type' => 'html',
        'hint' => 'Der vollständige Text der Seite /agb. Storno-, Zahlungs- und Widerrufsregeln sind Vertragsinhalt — Änderungen wirken nur für Buchungen ab dem Zeitpunkt der Änderung.',
        'default' => '<p>Allgemeine Geschäftsbedingungen für B³ Retreats Anbieterin und Vertragspartnerin Christina Brumm An den Nahewiesen 20 55450 Langenlonsheim Deutschland E-Mail: hello@b3-retreats.de nachfolgend „Veranstalterin“.</p>
<h2 id="1-geltungsbereich">§ 1 Geltungsbereich</h2>
<p>(1) Diese Allgemeinen Geschäftsbedingungen gelten für Verträge über die Teilnahme an Retreats, die von Christina Brumm insbesondere unter der Bezeichnung B³ Retreats angeboten werden. (2) Das Angebot kann sich sowohl an Verbraucherinnen im Sinne des § 13 BGB als auch an Unternehmerinnen im Sinne des § 14 BGB richten. (3) Vertragspartnerin der Teilnehmerin ist ausschließlich Christina Brumm. (4) Die Veranstalterin kann zur Durchführung eines Retreats selbstständige Kooperationspartnerinnen, Coaches, Trainerinnen oder sonstige Dienstleisterinnen einsetzen. Hierdurch entsteht grundsätzlich kein gesondertes Vertragsverhältnis zwischen der Teilnehmerin und der jeweiligen Kooperationspartnerin, soweit eine Zusatzleistung nicht ausdrücklich unmittelbar bei dieser gebucht wird.</p>
<h2 id="2-leistungsbeschreibung-des-jeweiligen-retreats">§ 2 Leistungsbeschreibung des jeweiligen Retreats</h2>
<p>(1) Die konkrete Ausgestaltung eines Retreats ergibt sich aus der zum Zeitpunkt der Buchung geltenden jeweiligen Angebots- und Leistungsbeschreibung. Dort können insbesondere geregelt werden:</p>
<ul>
  <li>Termin und Dauer</li>
  <li>Veranstaltungsort</li>
  <li>Unterkunft</li>
  <li>Verpflegung</li>
  <li>Retreat-Programm</li>
  <li>enthaltene Sessions und Leistungen</li>
  <li>An- und Abreisezeiten</li>
  <li>Preis</li>
  <li>verfügbare Buchungsoptionen</li>
  <li>Mindestteilnehmerinnenzahl</li>
  <li>besondere Voraussetzungen oder Hinweise</li>
  <li>optionale Zusatzleistungen</li>
</ul>
<p>(2) Die jeweilige Leistungsbeschreibung wird Bestandteil des zwischen der Veranstalterin und der Teilnehmerin geschlossenen Vertrages. (3) Im Retreat-Preis sind ausschließlich diejenigen Leistungen enthalten, die in der jeweiligen Leistungsbeschreibung ausdrücklich als enthalten bezeichnet werden.</p>
<h2 id="3-vertragsschluss">§ 3 Vertragsschluss</h2>
<p>(1) Die Darstellung eines Retreats auf einer Website, Landingpage oder Verkaufsplattform stellt grundsätzlich noch kein verbindliches Vertragsangebot dar. (2) Die Buchung erfolgt über den für das jeweilige Retreat bereitgestellten Buchungsprozess. (3) Die technische Buchungs- und Zahlungsabwicklung erfolgt über einen externen Zahlungsdienstleister. Derzeit wird hierfür Stripe eingesetzt. (4) Der konkrete technische Ablauf des Vertragsschlusses ergibt sich aus dem jeweiligen Buchungsprozess. (5) Mit erfolgreichem Abschluss des Buchungsvorgangs kommt der Vertrag über die Teilnahme am ausgewählten Retreat zwischen der Teilnehmerin und Christina Brumm zustande, soweit im Buchungsprozess nichts Abweichendes angegeben wird. (6) Die Teilnehmerin ist verpflichtet, die im Rahmen der Buchung abgefragten Angaben vollständig und wahrheitsgemäß zu machen.</p>
<h2 id="4-preise-und-zahlung">§ 4 Preise und Zahlung</h2>
<p>(1) Maßgeblich ist der für das jeweilige Retreat zum Zeitpunkt der Buchung ausgewiesene Preis. (2) Gegenüber Verbraucherinnen werden die Preise einschließlich der gesetzlich geschuldeten Umsatzsteuer angegeben. (3) Die verfügbaren Zahlungsmethoden ergeben sich aus dem jeweiligen Buchungsprozess. (4) Die Zahlungsabwicklung kann über externe Zahlungsdienstleister erfolgen. (5) Soweit eine Ratenzahlung angeboten wird, ergeben sich Anzahl, Höhe und Fälligkeit der jeweiligen Raten aus der bei Vertragsschluss ausgewählten Zahlungsoption. (6) Eine vereinbarte Ratenzahlung lässt die Verbindlichkeit der Buchung und des vereinbarten Gesamtpreises unberührt. (7) Bei Zahlungsverzug gelten die gesetzlichen Vorschriften.</p>
<h2 id="5-an-und-abreise">§ 5 An- und Abreise</h2>
<p>(1) Die An- und Abreise zum jeweiligen Retreat-Ort erfolgt grundsätzlich eigenverantwortlich und auf eigene Kosten der Teilnehmerin, soweit in der jeweiligen Leistungsbeschreibung nicht ausdrücklich etwas anderes vereinbart wird. (2) Die Teilnehmerin ist selbst dafür verantwortlich, ihre An- und Abreise rechtzeitig und entsprechend der kommunizierten An- und Abreisezeiten zu organisieren. (3) Persönliche Reise-, Fahrt- oder sonstige Nebenkosten sind nicht im Retreat-Preis enthalten, soweit nicht ausdrücklich etwas anderes angegeben wird.</p>
<h2 id="6-unterkunft">§ 6 Unterkunft</h2>
<p>(1) Art und Umfang der Unterkunft ergeben sich aus der jeweiligen Retreat-Beschreibung und der gebuchten Kategorie. (2) Soweit keine konkrete Zimmerkategorie, Zimmernummer, Bettart oder Zimmerbelegung ausdrücklich zugesichert wird, besteht hierauf kein Anspruch. (3) Die konkrete Zimmeraufteilung kann durch die Veranstalterin entsprechend der Buchungssituation und den räumlichen Gegebenheiten vorgenommen werden. (4) Besondere Unterkunftsoptionen oder Kategorien gelten nur dann als vereinbart, wenn sie ausdrücklich gebucht und bestätigt wurden.</p>
<h2 id="7-verpflegung-und-besondere-ernaehrungsanforderu">§ 7 Verpflegung und besondere Ernährungsanforderungen</h2>
<p>(1) Art und Umfang der enthaltenen Verpflegung ergeben sich aus der jeweiligen Retreat-Beschreibung. (2) Soweit verschiedene Ernährungsformen angeboten werden, können ausschließlich die im jeweiligen Buchungsprozess ausdrücklich angebotenen Optionen verbindlich ausgewählt werden. (3) Ein Anspruch auf die Berücksichtigung weiterer individueller Ernährungswünsche, Allergien oder Unverträglichkeiten besteht nur, wenn deren Berücksichtigung vorab ausdrücklich von der Veranstalterin bestätigt wurde. (4) Teilnehmerinnen mit Lebensmittelallergien, Unverträglichkeiten oder medizinisch bedingten Ernährungsanforderungen sind dafür verantwortlich, vor der Buchung zu prüfen, ob das angebotene Verpflegungskonzept für sie geeignet ist. (5) Eine vollständig allergenfreie Zubereitung oder die Vermeidung von Kreuzkontaminationen wird nur geschuldet, wenn dies ausdrücklich vereinbart wurde.</p>
<h2 id="8-programm-und-aenderungen">§ 8 Programm und Änderungen</h2>
<p>(1) Inhalt und Umfang des Retreat-Programms ergeben sich aus der jeweiligen Leistungsbeschreibung. (2) Die Veranstalterin ist berechtigt, einzelne Programmpunkte, Zeiten, Abläufe oder eingesetzte Personen aus sachlichem Grund anzupassen, soweit der Gesamtcharakter und der wesentliche Leistungsumfang des gebuchten Retreats dadurch nicht erheblich verändert werden und die Änderung für die Teilnehmerin zumutbar ist. (3) Sachliche Gründe können insbesondere Krankheit oder sonstige Verhinderung einer eingeplanten Person, wetterbedingte Umstände, organisatorische Erfordernisse oder nicht vorhersehbare Umstände am Retreat-Ort sein. (4) Soweit erforderlich, kann eine fachlich geeignete Ersatzperson eingesetzt werden. (5) Wesentliche Änderungen der vertraglich geschuldeten Hauptleistungen richten sich nach den gesetzlichen Vorschriften.</p>
<h2 id="9-freiwilligkeit-der-teilnahme">§ 9 Freiwilligkeit der Teilnahme</h2>
<p>(1) Die Teilnahme an einzelnen Sessions, Übungen und sonstigen Retreat-Aktivitäten ist grundsätzlich freiwillig, soweit in der jeweiligen Leistungsbeschreibung nichts anderes ausdrücklich angegeben wird. (2) Teilnehmerinnen können einzelne Programmpunkte auslassen und die entsprechend vorgesehene Zeit individuell nutzen. (3) Die freiwillige Nichtteilnahme an einzelnen angebotenen Programmpunkten begründet keinen Anspruch auf vollständige oder anteilige Rückerstattung des Retreat-Preises.</p>
<h2 id="10-gesundheit-und-eigenverantwortung">§ 10 Gesundheit und Eigenverantwortung</h2>
<p>(1) Die im Rahmen eines Retreats angebotenen Inhalte können je nach Retreat insbesondere Yoga, Breathwork, Bodywork, Coaching, Mentoring, Astrologie, Energiearbeit, Meditation oder vergleichbare Angebote umfassen. (2) Diese Angebote ersetzen keine medizinische, psychiatrische, psychotherapeutische oder sonstige heilkundliche Diagnose oder Behandlung. (3) Jede Teilnehmerin entscheidet eigenverantwortlich, ob und in welchem Umfang eine angebotene Aktivität für sie geeignet ist. (4) Bei bestehenden gesundheitlichen Einschränkungen, Schwangerschaft, akuten oder chronischen Erkrankungen, psychischen Erkrankungen oder sonstigen Umständen, aufgrund derer eine angebotene Aktivität möglicherweise nicht geeignet sein könnte, ist die Teilnehmerin dafür verantwortlich, ihre Teilnahme erforderlichenfalls vorab mit einer entsprechend qualifizierten medizinischen oder therapeutischen Fachperson abzuklären. (5) Sicherheits- und Durchführungshinweise der jeweiligen durchführenden Person sind zu beachten. (6) Bei auftretenden Beschwerden soll eine Übung oder Session unterbrochen und die durchführende Person informiert werden. (7) Die vorstehenden Bestimmungen führen nicht zu einer Einschränkung gesetzlicher Haftungsansprüche.</p>
<h2 id="11-individuelle-zusatzleistungen">§ 11 Individuelle Zusatzleistungen</h2>
<p>(1) Im Rahmen eines Retreats können zusätzliche Leistungen angeboten werden, die nicht im Retreat-Preis enthalten sind. Hierzu können insbesondere individuelle 1:1 Sessions gehören. (2) Ob eine solche Zusatzleistung unmittelbar durch die Veranstalterin oder eigenständig durch eine Kooperationspartnerin angeboten wird, ergibt sich aus dem jeweiligen Angebot. (3) Für individuell hinzugebuchte Leistungen können gesonderte Vertrags- und Zahlungsbedingungen gelten.</p>
<h2 id="12-stornierung-durch-die-teilnehmerin">§ 12 Stornierung durch die Teilnehmerin</h2>
<p>(1) Die Buchung eines Retreat-Platzes ist verbindlich. (2) Unabhängig von etwaigen gesetzlichen Rechten räumt die Veranstalterin der Teilnehmerin die Möglichkeit ein, ihre Teilnahme in Textform zu stornieren. (3) Maßgeblich für die Berechnung der Stornokosten ist der Zugang der Stornierung bei der Veranstalterin. Bei einer Stornierung werden folgende pauschalierte Stornokosten bezogen auf den vereinbarten Gesamtpreis berechnet: mehr als 14 Tage vor Beginn des Retreats: 30 % des vereinbarten Gesamtpreises 8 bis einschließlich 14 Tage vor Beginn des Retreats: 50 % des vereinbarten Gesamtpreises 7 Tage oder weniger vor Beginn des Retreats sowie bei Nichterscheinen: 100 % des vereinbarten Gesamtpreises (4) Bereits geleistete Zahlungen werden mit den geschuldeten Stornokosten verrechnet. Ein gegebenenfalls darüber hinaus bereits gezahlter Betrag wird zurückerstattet. (5) Der Teilnehmerin bleibt ausdrücklich der Nachweis gestattet, dass der Veranstalterin infolge der Stornierung kein Schaden oder ein wesentlich geringerer Schaden entstanden ist als die jeweils vorgesehene Pauschale. (6) Zwingende gesetzliche Rücktritts-, Kündigungs- und sonstige Rechte der Teilnehmerin bleiben unberührt.</p>
<h2 id="13-ersatzteilnehmerin">§ 13 Ersatzteilnehmerin</h2>
<p>(1) Kann eine Teilnehmerin nicht am Retreat teilnehmen, kann sie der Veranstalterin eine geeignete Ersatzteilnehmerin vorschlagen. (2) Die Übertragung der Buchung bedarf der vorherigen Zustimmung der Veranstalterin. Die Zustimmung darf nicht ohne sachlichen Grund verweigert werden. (3) Voraussetzung ist insbesondere, dass die Ersatzteilnehmerin die Teilnahmevoraussetzungen erfüllt und die für das Retreat geltenden Vertragsbedingungen akzeptiert. (4) Nachweislich aufgrund des Austauschs entstehende zusätzliche Kosten können der ursprünglichen Teilnehmerin in Rechnung gestellt werden.</p>
<h2 id="14-mindestteilnehmerinnenzahl-und-absage">§ 14 Mindestteilnehmerinnenzahl und Absage</h2>
<p>(1) Für einzelne Retreats kann eine Mindestteilnehmerinnenzahl vorgesehen werden. (2) Voraussetzung hierfür ist, dass die konkrete Mindestteilnehmerinnenzahl vor Vertragsschluss in der jeweiligen Retreat-Beschreibung oder im Buchungsprozess angegeben wird. (3) Ebenfalls wird vor Vertragsschluss angegeben, bis zu welchem Zeitpunkt die Veranstalterin das Retreat wegen Nichterreichens der Mindestteilnehmerinnenzahl absagen kann. (4) Wird die für das jeweilige Retreat kommunizierte Mindestteilnehmerinnenzahl innerhalb der angegebenen Frist nicht erreicht, ist die Veranstalterin berechtigt, das Retreat abzusagen. (5) Im Fall einer solchen Absage werden die von den Teilnehmerinnen bereits geleisteten Zahlungen für das Retreat vollständig zurückerstattet. (6) Weitere Aufwendungen der Teilnehmerin, insbesondere selbst gebuchte Reiseleistungen, werden nur ersetzt, soweit hierfür eine gesetzliche Verpflichtung besteht. (7) Muss ein Retreat aus anderen Gründen vollständig abgesagt werden, richten sich die Rechte der Teilnehmerinnen nach den gesetzlichen Vorschriften. (8) Die Veranstalterin informiert die betroffenen Teilnehmerinnen über eine Absage unverzüglich über die bei der Buchung angegebenen Kontaktdaten.</p>
<h2 id="15-aussergewoehnliche-umstaende">§ 15 Außergewöhnliche Umstände</h2>
<p>Treten außergewöhnliche, von der Veranstalterin nicht zu vertretende Umstände ein, welche die Durchführung eines Retreats unmöglich machen oder wesentlich beeinträchtigen, richten sich die Rechte und Pflichten der Parteien nach den gesetzlichen Vorschriften. Die Veranstalterin informiert die Teilnehmerinnen über entsprechende wesentliche Änderungen oder eine erforderliche Absage so früh wie möglich.</p>
<h2 id="16-widerrufsrecht">§ 16 Widerrufsrecht</h2>
<p>Die B³ Retreats werden grundsätzlich für einen konkret festgelegten Termin oder Zeitraum angeboten. Für Verträge zur Erbringung von Dienstleistungen in den gesetzlich bestimmten Bereichen, insbesondere im Zusammenhang mit Freizeitbetätigungen oder Beherbergung zu anderen Zwecken als zu Wohnzwecken, kann gemäß § 312g Abs. 2 Nr. 9 BGB bei Vorliegen der gesetzlichen Voraussetzungen das gesetzliche Widerrufsrecht bei Fernabsatzverträgen ausgeschlossen sein, wenn der Vertrag einen spezifischen Termin oder Zeitraum vorsieht. Soweit die gesetzlichen Voraussetzungen dieser Ausnahme für das konkret gebuchte Retreat erfüllt sind, besteht kein gesetzliches Widerrufsrecht. Zwingende gesetzliche Rechte der Teilnehmerin bleiben unberührt.</p>
<h2 id="17-haftung">§ 17 Haftung</h2>
<p>(1) Die Veranstalterin haftet unbeschränkt für vorsätzlich oder grob fahrlässig verursachte Schäden. (2) Die Veranstalterin haftet ebenfalls nach den gesetzlichen Vorschriften für Schäden aus der Verletzung des Lebens, des Körpers oder der Gesundheit. (3) Bei leicht fahrlässiger Verletzung wesentlicher Vertragspflichten haftet die Veranstalterin nach Maßgabe der gesetzlichen Vorschriften. Soweit gesetzlich zulässig, ist die Haftung auf den bei Vertragsschluss vorhersehbaren und vertragstypischen Schaden begrenzt. (4) Im Übrigen ist die Haftung für leicht fahrlässig verursachte Schäden ausgeschlossen, soweit dies gesetzlich zulässig ist. (5) Die vorstehenden Haftungsregelungen gelten entsprechend für gesetzliche Vertreterinnen und Vertreter sowie Erfüllungsgehilfen der Veranstalterin.</p>
<h2 id="18-persoenliche-gegenstaende">§ 18 Persönliche Gegenstände</h2>
<p>Für persönliche Gegenstände und Wertgegenstände der Teilnehmerinnen haftet die Veranstalterin ausschließlich nach Maßgabe von § 17 und den gesetzlichen Vorschriften. Teilnehmerinnen sind selbst dafür verantwortlich, ihre persönlichen Gegenstände angemessen zu sichern.</p>
<h2 id="19-verhalten-waehrend-des-retreats">§ 19 Verhalten während des Retreats</h2>
<p>(1) Die Teilnehmerinnen verpflichten sich zu einem respektvollen Umgang miteinander, mit den durchführenden Personen sowie mit der Unterkunft und dem Retreat-Ort. (2) Bei erheblichen oder wiederholten Störungen des Retreat-Ablaufs, einer Gefährdung anderer Personen oder schwerwiegenden Verstößen gegen berechtigte Anweisungen kann die Veranstalterin nach erfolgloser Abmahnung geeignete Maßnahmen ergreifen und als letztes Mittel die weitere Teilnahme untersagen. Eine vorherige Abmahnung ist entbehrlich, wenn sie aufgrund der Schwere des Verhaltens nicht zumutbar ist. (3) Die gesetzlichen Rechte der Parteien bleiben unberührt.</p>
<h2 id="20-foto-und-videoaufnahmen">§ 20 Foto- und Videoaufnahmen</h2>
<p>(1) Mit der Buchung eines Retreats erteilt eine Teilnehmerin nicht automatisch ihre Einwilligung, dass erkennbare Foto- oder Videoaufnahmen von ihr zu Werbe- oder Marketingzwecken veröffentlicht werden dürfen. (2) Soweit für entsprechende Aufnahmen und deren Veröffentlichung eine Einwilligung erforderlich ist, wird diese gesondert eingeholt. (3) Die Erteilung einer solchen Einwilligung ist freiwillig. Eine verweigerte Einwilligung hat keine Auswirkungen auf die Teilnahme am Retreat.</p>
<h2 id="21-datenschutz">§ 21 Datenschutz</h2>
<p>Informationen über die Verarbeitung personenbezogener Daten ergeben sich aus der jeweils geltenden Datenschutzerklärung der Veranstalterin. Soweit für Buchung und Zahlungsabwicklung externe Plattformen oder Zahlungsdienstleister eingesetzt werden, erfolgt die dortige Datenverarbeitung zusätzlich nach den für den jeweiligen Dienst geltenden Datenschutzbestimmungen.</p>
<h2 id="22-schlussbestimmungen">§ 22 Schlussbestimmungen</h2>
<p>(1) Es gilt das Recht der Bundesrepublik Deutschland. (2) Gegenüber Verbraucherinnen gilt diese Rechtswahl nur insoweit, als hierdurch zwingende Verbraucherschutzvorschriften des Staates ihres gewöhnlichen Aufenthalts nicht entzogen werden. (3) Gegenüber Verbraucherinnen gelten die gesetzlichen Gerichtsstandsregelungen. (4) Für Unternehmerinnen gelten ergänzend die gesetzlichen Regelungen über den Gerichtsstand. (5) Sollten einzelne Bestimmungen dieser AGB ganz oder teilweise unwirksam sein oder werden, gelten insoweit die gesetzlichen Vorschriften. Die Wirksamkeit des Vertrages im Übrigen bleibt hiervon unberührt.</p>
<p>Stand: August 2026<br>
Christina Brumm<br>
An den Nahewiesen 20<br>
55450 Langenlonsheim<br>
Deutschland<br>
E-Mail: hello@b3-retreats.de</p>',
    ],
    'recht.eyebrow' => [
        'group' => '22 Rechtstexte',
        'label' => 'Kleine Zeile über der Überschrift',
        'type' => 'text',
        'hint' => 'Steht auf allen drei Rechtsseiten über der großen Überschrift.',
        'default' => 'Rechtliches',
    ],
    'recht.zurueck' => [
        'group' => '22 Rechtstexte',
        'label' => 'Link zurück im Kopf',
        'type' => 'text',
        'hint' => 'Der einzige Navigationspunkt im Kopf der Rechtsseiten.',
        'default' => 'Zurück zur Startseite',
    ],
];
