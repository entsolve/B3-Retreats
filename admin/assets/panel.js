/* =====================================================================
   B³ Retreats — die Oberflaeche des Panels.

   Kein Inline-Skript, keine geladene Bibliothek: die CSP des Panels laesst
   beides nicht zu (script-src 'self', siehe admin/config.php). Alles hier
   ist von Hand geschrieben und laeuft aus dieser einen Datei.

   DER GRUNDSATZ, DER ALLES ANDERE ERKLAERT: das Formularfeld bleibt das
   Formularfeld. Der sichtbare Editor, die Karten einer Wiederholung, die
   Bildauswahl — sie legen sich VOR ein <textarea> oder <input> und
   schreiben ihr Ergebnis dort hinein. Abgeschickt wird immer das
   urspruengliche Feld.

   Zwei Dinge folgen daraus:

     1. Faellt JavaScript aus, ist das Panel nicht kaputt. Dann steht dort
        wieder rohes HTML bzw. rohes JSON — unschoen, aber vollstaendig
        bedienbar. Genau so lief es vorher.
     2. Der Server muss nichts Neues glauben. index.php prueft und
        saeubert unveraendert weiter (b3_prepare_value), egal ob der Text
        getippt oder geklickt wurde.

   Was der sichtbare Editor anbietet, ist kein Zufall: es ist genau das,
   was der Sanitizer in partials/content.php durchlaesst (B3_ALLOWED_TAGS).
   Ein Knopf, dessen Ergebnis beim Speichern wieder entfernt wuerde, waere
   ein Versprechen, das die Seite nicht haelt.
   ===================================================================== */
(function () {
  'use strict';

  /* ------------------------------------------------------------------
     Kleine Helfer
     ------------------------------------------------------------------ */

  function el(tag, klasse, text) {
    var n = document.createElement(tag);
    if (klasse) n.className = klasse;
    if (text !== undefined && text !== null) n.textContent = text;
    return n;
  }

  function knopf(text, klasse, titel) {
    var b = el('button', klasse || 'knopf-leise', text);
    b.type = 'button';                      // sonst schickt er das Formular ab
    if (titel) b.title = titel;
    return b;
  }

  /** Wert unter 'a.b' aus einem Objekt lesen. */
  function wertAn(objekt, pfad) {
    var knoten = objekt;
    var teile = String(pfad).split('.');
    for (var i = 0; i < teile.length; i++) {
      if (knoten === null || typeof knoten !== 'object') return undefined;
      knoten = knoten[teile[i]];
    }
    return knoten;
  }

  /** Wert unter 'a.b' setzen, fehlende Ebenen anlegen. */
  function wertSetzen(objekt, pfad, wert) {
    var teile = String(pfad).split('.');
    var knoten = objekt;
    for (var i = 0; i < teile.length - 1; i++) {
      if (typeof knoten[teile[i]] !== 'object' || knoten[teile[i]] === null) {
        knoten[teile[i]] = {};
      }
      knoten = knoten[teile[i]];
    }
    knoten[teile[teile.length - 1]] = wert;
  }

  /** Textfelder wachsen mit dem Inhalt — 240 Felder, viele davon mehrzeilig. */
  function mitwachsen(t) {
    var wachsen = function () {
      t.style.height = 'auto';
      t.style.height = (t.scrollHeight + 2) + 'px';
    };
    t.addEventListener('input', wachsen);
    // Beim Aufbau ist das Feld womoeglich noch unsichtbar (scrollHeight 0),
    // dann rechnet sich die Hoehe beim ersten Tippen zurecht.
    wachsen();
  }

  /* ------------------------------------------------------------------
     1. Bestaetigungen
     ------------------------------------------------------------------ */

  document.addEventListener('click', function (ev) {
    var e = ev.target.closest('[data-confirm]');
    if (e && !window.confirm(e.getAttribute('data-confirm'))) ev.preventDefault();
  });

  /* ------------------------------------------------------------------
     2. Bildauswahl — ein Fenster fuer die ganze Seite
     ------------------------------------------------------------------ */

  var dialog = document.querySelector('[data-bildwahl]');
  var raster = document.querySelector('[data-bildwahl-raster]');
  var meldung = document.querySelector('[data-bildwahl-meldung]');
  var dateiEingabe = document.querySelector('[data-bild-datei]');
  var bilderMerker = null;         // einmal geladen, dann wiederverwendet
  var nimmBild = null;             // Rueckruf des Feldes, das gerade waehlt

  function melden(text, art) {
    if (!meldung) return;
    meldung.textContent = text || '';
    meldung.hidden = !text;
    meldung.className = 'bildwahl__meldung' + (art ? ' bildwahl__meldung--' + art : '');
  }

  function bilderLaden(erzwingen) {
    if (bilderMerker && !erzwingen) return Promise.resolve(bilderMerker);
    return fetch('media.php?format=json', { credentials: 'same-origin' })
      .then(function (a) { return a.json(); })
      .then(function (d) {
        if (!d.ok) throw new Error(d.fehler || 'Die Bilder liessen sich nicht laden.');
        bilderMerker = d.bilder || [];
        return bilderMerker;
      });
  }

  function rasterFuellen(bilder) {
    raster.textContent = '';
    if (!bilder.length) {
      raster.appendChild(el('p', 'hinweis', 'Es sind noch keine Bilder vorhanden.'));
      return;
    }
    bilder.forEach(function (bild) {
      var k = el('button', 'kachel');
      k.type = 'button';
      k.title = bild.path;

      var img = el('img');
      img.src = '../' + bild.path;
      img.alt = '';
      img.loading = 'lazy';
      k.appendChild(img);

      var name = bild.path.split('/').pop();
      k.appendChild(el('span', 'kachel__name', name));
      if (bild.eigen) k.appendChild(el('em', 'kachel__marke', 'hochgeladen'));

      k.addEventListener('click', function () {
        if (nimmBild) nimmBild(bild.path);
        dialogSchliessen();
      });
      raster.appendChild(k);
    });
  }

  function dialogOeffnen(rueckruf) {
    if (!dialog) return;
    nimmBild = rueckruf;
    melden('');
    raster.textContent = '';
    raster.appendChild(el('p', 'hinweis', 'Bilder werden geladen …'));
    if (typeof dialog.showModal === 'function') dialog.showModal();
    else dialog.setAttribute('open', 'open');

    bilderLaden(false).then(rasterFuellen).catch(function (f) {
      raster.textContent = '';
      melden(f.message, 'fehler');
    });
  }

  function dialogSchliessen() {
    if (!dialog) return;
    nimmBild = null;
    if (typeof dialog.close === 'function') dialog.close();
    else dialog.removeAttribute('open');
  }

  if (dialog) {
    var zu = dialog.querySelector('[data-bildwahl-schliessen]');
    if (zu) zu.addEventListener('click', dialogSchliessen);
    // Klick auf die abgedunkelte Flaeche neben dem Fenster schliesst ebenfalls.
    dialog.addEventListener('click', function (ev) {
      if (ev.target === dialog) dialogSchliessen();
    });
  }

  /** Datei waehlen und hochladen; ruft `fertig(pfad)` bei Erfolg. */
  function hochladen(fertig, sagen) {
    if (!dateiEingabe || !dialog) return;

    dateiEingabe.value = '';                 // sonst feuert change nicht erneut
    dateiEingabe.onchange = function () {
      var datei = dateiEingabe.files && dateiEingabe.files[0];
      if (!datei) return;

      sagen('„' + datei.name + '" wird hochgeladen …', null);

      var paket = new FormData();
      paket.append('bild', datei);
      paket.append('csrf', dialog.getAttribute('data-csrf') || '');

      fetch('media.php', { method: 'POST', body: paket, credentials: 'same-origin' })
        .then(function (a) { return a.json(); })
        .then(function (d) {
          if (!d.ok) throw new Error(d.fehler || 'Der Upload ist fehlgeschlagen.');
          bilderMerker = null;               // Liste ist veraltet
          sagen('„' + datei.name + '" ist hochgeladen.', 'ok');
          fertig(d.path);
        })
        .catch(function (f) { sagen(f.message, 'fehler'); });
    };
    dateiEingabe.click();
  }

  /* ------------------------------------------------------------------
     3. Bildfeld — Vorschau, Auswahl, Hochladen
     ------------------------------------------------------------------ */

  /**
   * Haengt die Bedienung an ein bestehendes Bildfeld.
   * `wurzel` enthaelt .bildfeld__pfad und optional die Vorschau.
   */
  function bildfeldVerdrahten(wurzel, beiAenderung) {
    var pfad = wurzel.querySelector('.bildfeld__pfad');
    var vorschau = wurzel.querySelector('.bildfeld__vorschau');
    var eigeneMeldung = null;

    function zeigen(wert) {
      if (!vorschau) return;
      if (wert && wert.trim() !== '') {
        vorschau.src = '../' + wert;
        vorschau.hidden = false;
      } else {
        vorschau.removeAttribute('src');
        vorschau.hidden = true;
      }
    }

    function setzen(wert) {
      pfad.value = wert;
      zeigen(wert);
      if (beiAenderung) beiAenderung(wert);
    }

    function sagen(text, art) {
      // Beim Hochladen aus einer Karte heraus gibt es kein offenes Fenster —
      // dann steht die Meldung unter dem Feld.
      if (dialog && dialog.open) { melden(text, art); return; }
      if (!eigeneMeldung) {
        eigeneMeldung = el('p', 'bildfeld__meldung');
        wurzel.appendChild(eigeneMeldung);
      }
      eigeneMeldung.textContent = text || '';
      eigeneMeldung.className = 'bildfeld__meldung' + (art ? ' bildfeld__meldung--' + art : '');
    }

    var waehlen = wurzel.querySelector('[data-bild-waehlen]');
    if (waehlen) waehlen.addEventListener('click', function () { dialogOeffnen(setzen); });

    var laden = wurzel.querySelector('[data-bild-hochladen]');
    if (laden) laden.addEventListener('click', function () { hochladen(setzen, sagen); });

    // Von Hand getippter Pfad bleibt moeglich — die Vorschau zieht mit.
    pfad.addEventListener('input', function () {
      zeigen(pfad.value);
      if (beiAenderung) beiAenderung(pfad.value);
    });
  }

  document.querySelectorAll('[data-bildfeld]').forEach(function (w) {
    bildfeldVerdrahten(w, null);
  });

  /** Baut ein Bildfeld von Grund auf — fuer Unterfelder in Karten. */
  function bildfeldBauen(wert, beiAenderung) {
    var w = el('div', 'bildfeld');

    var img = el('img', 'bildfeld__vorschau');
    img.alt = '';
    if (wert) img.src = '../' + wert; else img.hidden = true;
    w.appendChild(img);

    var steuer = el('div', 'bildfeld__steuer');
    var pfad = el('input', 'bildfeld__pfad');
    pfad.type = 'text';
    pfad.value = wert || '';
    pfad.spellcheck = false;
    steuer.appendChild(pfad);

    var knoepfe = el('div', 'bildfeld__knoepfe');
    var b1 = knopf('Bild wählen');
    b1.setAttribute('data-bild-waehlen', '');
    var b2 = knopf('Neues Bild hochladen');
    b2.setAttribute('data-bild-hochladen', '');
    knoepfe.appendChild(b1);
    knoepfe.appendChild(b2);
    steuer.appendChild(knoepfe);
    w.appendChild(steuer);

    bildfeldVerdrahten(w, beiAenderung);
    return w;
  }

  /* ------------------------------------------------------------------
     4. Sichtbarer Editor fuer HTML-Felder

     Die Knoepfe erzeugen genau die Auszeichnungen, die der Sanitizer
     stehen laesst: strong, em, u, h3, blockquote, ul/ol/li, a.
     ------------------------------------------------------------------ */

  var BEFEHLE = [
    { name: 'Fett',      befehl: 'bold',                 zeichen: 'F', titel: 'Fett (Strg+B)' },
    { name: 'Kursiv',    befehl: 'italic',               zeichen: 'K', titel: 'Kursiv (Strg+I)' },
    { name: 'Absatz',    befehl: 'formatBlock', wert: 'p',        titel: 'Gewöhnlicher Absatz' },
    { name: 'Über&shy;schrift', befehl: 'formatBlock', wert: 'h3', titel: 'Zwischenüberschrift' },
    { name: 'Liste',     befehl: 'insertUnorderedList',  titel: 'Aufzählung mit Punkten' },
    { name: '1. Liste',  befehl: 'insertOrderedList',    titel: 'Nummerierte Aufzählung' },
    { name: 'Zitat',     befehl: 'formatBlock', wert: 'blockquote', titel: 'Zitat' },
    { name: 'Link',      befehl: 'link',                 titel: 'Link einfügen' },
    { name: 'Link weg',  befehl: 'unlink',               titel: 'Link entfernen' },
    { name: 'Format weg', befehl: 'removeFormat',        titel: 'Auszeichnung entfernen' }
  ];

  // Ohne dieses Flag setzt der Browser <span style="font-weight:bold"> statt
  // <b> — und der Sanitizer wirft das style-Attribut weg. Das Ergebnis waere
  // ein Knopf, der sichtbar nichts tut, sobald man speichert.
  try { document.execCommand('styleWithCSS', false, false); } catch (e) { /* egal */ }

  /**
   * Baut einen sichtbaren Editor.
   *
   * @param startwert  HTML, das anfangs drinsteht
   * @param beiAenderung  bekommt das neue HTML — NUR wenn wirklich getippt
   *                      oder geklickt wurde.
   */
  function editorBauen(startwert, beiAenderung) {
    var box = el('div', 'editor');
    var leiste = el('div', 'editor__leiste');
    var flaeche = el('div', 'editor__flaeche');

    flaeche.contentEditable = 'true';
    flaeche.innerHTML = startwert || '';
    flaeche.setAttribute('role', 'textbox');
    flaeche.setAttribute('aria-multiline', 'true');

    /* Ein geleertes Feld MUSS als leer ankommen.

       contentEditable hinterlaesst beim Leeren immer etwas: je nach Browser
       ein <br> oder ein <p><br></p>. Das ist nicht dieselbe Zeichenkette wie
       "", und deshalb ging genau das schief, was unter dem Formular als
       Versprechen steht: „Ein Feld ganz leeren stellt den urspruenglichen
       Text wieder her." Gespeichert wurde stattdessen ein <br> — auf der
       Seite stand danach eine leere Zeile, und der alte Text war weg.

       Bilder zaehlen als Inhalt, auch wenn sie keinen Text haben. */
    function sichtbarLeer() {
      if (flaeche.querySelector('img, hr')) return false;
      // Geschuetztes Leerzeichen ist fuer den Browser Text, fuer das Auge nicht.
      return flaeche.textContent.replace(/\u00a0/g, ' ').trim() === '';
    }

    function melden() { beiAenderung(sichtbarLeer() ? '' : flaeche.innerHTML); }

    BEFEHLE.forEach(function (b) {
      var k = el('button', 'editor__knopf');
      k.type = 'button';
      k.innerHTML = b.name;
      k.title = b.titel || b.name;

      // Ohne dieses preventDefault verliert die Schreibflaeche beim Klick den
      // Fokus, und execCommand weiss nicht mehr, worauf es wirken soll.
      k.addEventListener('mousedown', function (ev) { ev.preventDefault(); });

      k.addEventListener('click', function () {
        flaeche.focus();
        if (b.befehl === 'link') {
          var adresse = window.prompt('Adresse des Links (https://… oder mailto:…)', 'https://');
          if (!adresse) return;
          document.execCommand('createLink', false, adresse);
        } else if (b.wert) {
          document.execCommand(b.befehl, false, b.wert);
        } else {
          document.execCommand(b.befehl, false, null);
        }
        melden();
      });
      leiste.appendChild(k);
    });

    flaeche.addEventListener('input', melden);

    // Einfuegen kommt als reiner Text herein. Wer aus Word oder von einer
    // Website kopiert, schleppt sonst Schriftgroessen, Farben und leere
    // <span> mit — der Sanitizer raeumt das zwar weg, aber erst beim
    // Speichern, und bis dahin sieht die Redaktion etwas anderes als die
    // Seite spaeter zeigt.
    flaeche.addEventListener('paste', function (ev) {
      ev.preventDefault();
      var text = (ev.clipboardData || window.clipboardData).getData('text/plain');
      document.execCommand('insertText', false, text);
    });

    box.appendChild(leiste);
    box.appendChild(flaeche);
    return box;
  }

  /* Die HTML-Felder der obersten Ebene. */
  document.querySelectorAll('textarea[data-editor="html"]').forEach(function (t) {
    var box = editorBauen(t.value, function (html) { t.value = html; });
    t.hidden = true;
    t.parentNode.insertBefore(box, t);
  });

  /* ------------------------------------------------------------------
     5. Wiederholungen als Karten

     Aus einer Zeile JSON werden beschriftete Felder. Das JSON bleibt im
     verborgenen textarea und wird bei jeder Aenderung neu geschrieben —
     abgeschickt wird weiterhin es.
     ------------------------------------------------------------------ */

  /** Ein einzelnes Unterfeld bauen; liefert das fertige Element. */
  function unterfeldBauen(def, wert, setzen) {
    var feld = el('div', 'eintrag__feld');
    feld.appendChild(el('label', null, def.label || def.path));

    if (def.type === 'html') {
      feld.appendChild(editorBauen(wert || '', setzen));

    } else if (def.type === 'image') {
      feld.appendChild(bildfeldBauen(wert || '', setzen));

    } else if (def.type === 'choice') {
      var aus = el('select');
      (def.choices || []).forEach(function (w) {
        var o = el('option', null, w.label);
        o.value = w.value;
        if (String(w.value) === String(wert || '')) o.selected = true;
        aus.appendChild(o);
      });
      aus.addEventListener('change', function () { setzen(aus.value); });
      feld.appendChild(aus);

    } else if (def.type === 'textarea') {
      var t = el('textarea');
      t.rows = 3;
      t.value = wert === undefined || wert === null ? '' : String(wert);
      t.addEventListener('input', function () { setzen(t.value); });
      feld.appendChild(t);
      mitwachsen(t);

    } else {
      var i = el('input');
      i.type = 'text';
      i.value = wert === undefined || wert === null ? '' : String(wert);
      i.addEventListener('input', function () { setzen(i.value); });
      feld.appendChild(i);
    }

    if (def.hint) feld.appendChild(el('p', 'hinweis', def.hint));
    return feld;
  }

  /**
   * Baut die Kartenliste fuer eine Wiederholung.
   *
   * @param eintraege  Array der Eintraege (wird an Ort und Stelle geaendert)
   * @param felder     Beschreibung der Unterfelder aus dem Register
   * @param label      Pfad des Feldes, das die Karte benennt (itemLabel)
   * @param schreiben  wird nach jeder Aenderung gerufen
   */
  function kartenBauen(eintraege, felder, label, schreiben) {
    var liste = el('div', 'eintraege');

    function neuZeichnen() {
      liste.textContent = '';

      eintraege.forEach(function (eintrag, nr) {
        var karte = el('div', 'eintrag');

        /* --- Kopf: Bezeichnung und die Knoepfe zum Ordnen --- */
        var kopf = el('div', 'eintrag__kopf');

        var name = label ? wertAn(eintrag, label) : '';
        if (typeof name !== 'string' || name.trim() === '') name = 'Eintrag ' + (nr + 1);
        // Die Bezeichnung kann HTML enthalten (z. B. eine Frage mit &nbsp;) —
        // als Text anzeigen, nicht als Auszeichnung.
        name = name.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim();
        kopf.appendChild(el('strong', 'eintrag__name', name.slice(0, 70) || ('Eintrag ' + (nr + 1))));

        var werkzeug = el('div', 'eintrag__werkzeug');

        var hoch = knopf('↑', 'knopf-winzig', 'Nach oben');
        hoch.disabled = nr === 0;
        hoch.addEventListener('click', function () {
          eintraege.splice(nr - 1, 0, eintraege.splice(nr, 1)[0]);
          schreiben(); neuZeichnen();
        });

        var runter = knopf('↓', 'knopf-winzig', 'Nach unten');
        runter.disabled = nr === eintraege.length - 1;
        runter.addEventListener('click', function () {
          eintraege.splice(nr + 1, 0, eintraege.splice(nr, 1)[0]);
          schreiben(); neuZeichnen();
        });

        var weg = knopf('Löschen', 'knopf-winzig knopf-winzig--warnung', 'Diesen Eintrag entfernen');
        weg.addEventListener('click', function () {
          if (!window.confirm('Diesen Eintrag wirklich entfernen?\n\n' + name)) return;
          eintraege.splice(nr, 1);
          schreiben(); neuZeichnen();
        });

        werkzeug.appendChild(hoch);
        werkzeug.appendChild(runter);
        werkzeug.appendChild(weg);
        kopf.appendChild(werkzeug);
        karte.appendChild(kopf);

        /* --- Die Unterfelder --- */
        felder.forEach(function (def) {
          if (def.type === 'list') {
            // Eine Wiederholung in der Wiederholung — die Absaetze bei den
            // Experiences. Tiefer geht es nicht, und tiefer braucht es nicht.
            var innen = wertAn(eintrag, def.path);
            if (!Array.isArray(innen)) { innen = []; wertSetzen(eintrag, def.path, innen); }

            var huelle = el('div', 'eintrag__feld');
            huelle.appendChild(el('label', null, def.label || def.path));
            huelle.appendChild(kartenBauen(innen, def.fields || [],
              def.itemLabel || '', schreiben));
            karte.appendChild(huelle);
            return;
          }

          karte.appendChild(unterfeldBauen(def, wertAn(eintrag, def.path), function (neu) {
            wertSetzen(eintrag, def.path, neu);
            schreiben();
            // Die Ueberschrift der Karte zieht mit, wenn das Namensfeld
            // bearbeitet wird — sonst heisst die Karte noch wie vorher.
            if (label && def.path === label) {
              var t = String(neu).replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim();
              kopf.querySelector('.eintrag__name').textContent =
                t.slice(0, 70) || ('Eintrag ' + (nr + 1));
            }
          }));
        });

        liste.appendChild(karte);
      });

      /* --- Neuer Eintrag --- */
      var mehr = knopf('+ Eintrag hinzufügen', 'knopf-leise knopf-leise--breit');
      mehr.addEventListener('click', function () {
        var frisch = {};
        felder.forEach(function (def) {
          wertSetzen(frisch, def.path, def.type === 'list' ? [] : '');
        });
        eintraege.push(frisch);
        schreiben(); neuZeichnen();
      });
      liste.appendChild(mehr);
    }

    neuZeichnen();
    return liste;
  }

  document.querySelectorAll('textarea[data-editor="liste"]').forEach(function (t) {
    var felder, eintraege;
    try {
      felder = JSON.parse(t.getAttribute('data-felder') || '[]');
    } catch (e) { felder = []; }
    try {
      eintraege = JSON.parse(t.value || '[]');
    } catch (e) { eintraege = null; }

    // Ohne Beschreibung der Unterfelder oder bei kaputtem JSON bleibt das
    // rohe Feld stehen. Lieber ein haesslicher Kasten, den man reparieren
    // kann, als eine Oberflaeche, die den Inhalt stillschweigend wegwirft.
    if (!felder.length || !Array.isArray(eintraege)) {
      t.hidden = false;
      return;
    }

    var schreiben = function () {
      t.value = JSON.stringify(eintraege, null, 2);
    };

    var karten = kartenBauen(eintraege, felder,
      t.getAttribute('data-eintrag-label') || '', schreiben);

    t.hidden = true;
    t.parentNode.insertBefore(karten, t);
  });

  /* ------------------------------------------------------------------
     6. Die uebrigen Textfelder
     ------------------------------------------------------------------ */

  document.querySelectorAll('textarea').forEach(function (t) {
    if (!t.hidden) mitwachsen(t);
  });

  /* ------------------------------------------------------------------
     6. Kalender fuer die Datumsfelder

     ABSICHTLICH SELBST GEBAUT, in zwei Punkten anders als der native
     <input type="date">:

     1. ER BLEIBT OFFEN. Der native Kalender klappt nach dem ersten Klick
        zu. Hier gehoeren zwei Tage zusammen — erster und letzter Tag des
        Retreats —, und wer den zweiten waehlt, will den ersten dabei noch
        sehen. Zugeklappt waere jede Wahl ein Blindflug.

     2. ER ZEIGT DIE SPANNE. Beide Felder eines Termins teilen sich einen
        Kalender; die Tage dazwischen sind eingefaerbt. So sieht man
        sofort, ob es vier Tage sind und ob sie auf dem gewuenschten
        Wochentag beginnen — die Angabe, die auf der Seite als
        „Donnerstag, 08.10.2026" erscheint.

     Das Textfeld bleibt daneben bestehen und bleibt das, was abgeschickt
     wird. Ohne JavaScript tippt man weiter JJJJ-MM-TT von Hand.
     ------------------------------------------------------------------ */

  var MONATE = ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli',
                'August', 'September', 'Oktober', 'November', 'Dezember'];
  var TAGE = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'];

  function iso(d) {
    return d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2)
         + '-' + ('0' + d.getDate()).slice(-2);
  }

  function ausIso(s) {
    var m = /^(\d{4})-(\d{2})-(\d{2})$/.exec((s || '').trim());
    if (!m) return null;
    var d = new Date(+m[1], +m[2] - 1, +m[3]);
    // 31. Februar faengt der Date-Konstruktor nicht ab, er rechnet weiter.
    return d.getMonth() === +m[2] - 1 ? d : null;
  }

  function kalenderBauen(felder) {
    var box = el('div', 'kalender');
    var kopf = el('div', 'kalender__kopf');
    var zurueck = el('button', 'kalender__pfeil'); zurueck.type = 'button';
    zurueck.textContent = '‹'; zurueck.title = 'Voriger Monat';
    var titel = el('span', 'kalender__titel');
    var vor = el('button', 'kalender__pfeil'); vor.type = 'button';
    vor.textContent = '›'; vor.title = 'Nächster Monat';
    kopf.appendChild(zurueck); kopf.appendChild(titel); kopf.appendChild(vor);

    var gitter = el('div', 'kalender__gitter');
    box.appendChild(kopf); box.appendChild(gitter);

    // Der gezeigte Monat richtet sich nach dem ersten gefuellten Feld.
    var start = ausIso(felder[0] && felder[0].value) || new Date();
    var zeigt = new Date(start.getFullYear(), start.getMonth(), 1);
    var aktiv = 0;   // welches Feld der naechste Klick fuellt

    function zeichnen() {
      titel.textContent = MONATE[zeigt.getMonth()] + ' ' + zeigt.getFullYear();
      gitter.textContent = '';
      TAGE.forEach(function (t) {
        var k = el('span', 'kalender__wt'); k.textContent = t; gitter.appendChild(k);
      });

      var erster = new Date(zeigt.getFullYear(), zeigt.getMonth(), 1);
      // Montag als erster Spaltentag: getDay() liefert 0 fuer Sonntag.
      var luecke = (erster.getDay() + 6) % 7;
      for (var i = 0; i < luecke; i++) gitter.appendChild(el('span', 'kalender__leer'));

      var von = ausIso(felder[0] && felder[0].value);
      var bis = felder[1] ? ausIso(felder[1].value) : null;
      var tage = new Date(zeigt.getFullYear(), zeigt.getMonth() + 1, 0).getDate();

      for (var t = 1; t <= tage; t++) {
        (function (tag) {
          var d = new Date(zeigt.getFullYear(), zeigt.getMonth(), tag);
          var knopf = el('button', 'kalender__tag');
          knopf.type = 'button';
          knopf.textContent = tag;
          if (von && +d === +von) knopf.classList.add('is-von');
          if (bis && +d === +bis) knopf.classList.add('is-bis');
          if (von && bis && d > von && d < bis) knopf.classList.add('is-drin');
          knopf.addEventListener('click', function () {
            var ziel = felder[aktiv] || felder[0];
            ziel.value = iso(d);
            // Das Panel merkt sich Aenderungen ueber input-Ereignisse.
            ziel.dispatchEvent(new Event('input', { bubbles: true }));
            // Nach dem ersten Tag auf das zweite Feld weiterruecken —
            // aber NICHT schliessen. Genau das ist der Punkt.
            if (felder.length > 1) aktiv = aktiv === 0 ? 1 : 0;
            zeichnen();
          });
          gitter.appendChild(knopf);
        })(t);
      }
    }

    zurueck.addEventListener('click', function () {
      zeigt = new Date(zeigt.getFullYear(), zeigt.getMonth() - 1, 1); zeichnen();
    });
    vor.addEventListener('click', function () {
      zeigt = new Date(zeigt.getFullYear(), zeigt.getMonth() + 1, 1); zeichnen();
    });
    felder.forEach(function (f, i) {
      f.addEventListener('focus', function () { aktiv = i; });
      f.addEventListener('input', function () {
        var d = ausIso(f.value);
        if (d) { zeigt = new Date(d.getFullYear(), d.getMonth(), 1); }
        zeichnen();
      });
    });

    zeichnen();
    return box;
  }

  var datumsfelder = [].slice.call(document.querySelectorAll('input[data-datum]'));
  if (datumsfelder.length) {
    // Alle Datumsfelder eines Formulars teilen sich EINEN Kalender: sie
    // beschreiben denselben Termin, und zwei Kalender nebeneinander
    // wuerden die Spanne auseinanderreissen.
    var letztes = datumsfelder[datumsfelder.length - 1];
    var traeger = letztes.closest('.feld') || letztes.parentNode;
    traeger.appendChild(kalenderBauen(datumsfelder));
  }

})();
