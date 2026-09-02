/* ==========================================================================
   B³ RETREATS

   DIE BUCHUNGS-URL STEHT NICHT MEHR HIER, SONDERN IM PANEL:
   „16 Buchung -> Buchungslink". Sie gilt fuer alle Buchungsbuttons der Seite.
   Braucht eine Zimmerkategorie einen eigenen Link, steht er unter
   „12 Unterkunft und Preise -> Buchungslink Shared House / Friends Special";
   bleibt der leer, gilt wieder der allgemeine.

   Vorher stand die Adresse als Konstante in dieser Datei. Damit konnte die
   Kundin ausgerechnet den wichtigsten Wert der Seite nicht selbst setzen —
   sie haette JavaScript anfassen muessen.

   Die Vorlage schreibt die Adressen als data-booking-url an <body> bzw. an
   den einzelnen Button. Ist keine eingetragen, bleibt der Button auf seinem
   Ziel aus der Vorlage stehen (#buchung, auf den Rechtsseiten /#buchung) —
   so fuehrt auch der leere Zustand irgendwohin.
   ========================================================================== */

(function () {
  'use strict';

  /* Weiches Rollen NACHTRAEGLICH einschalten.

     In tokens/style.css steht `scroll-behavior: auto`, damit der Sprung zu
     einem Anker beim Laden sofort passiert. Wer ohne JavaScript absendet,
     kommt auf /?warteliste=…#warteliste zurueck; mit „smooth" faehrt der
     Browser dabei vom Seitenanfang durch die ganze Seite nach unten, und die
     Antwort auf das Formular zieht sekundenlang vorbei, statt dazustehen.

     Fuer Klicks im Menue ist weiches Rollen dagegen richtig — die schaltet
     diese Zeile wieder frei, sobald jemand die Seite anfasst. Wer weniger
     Bewegung eingestellt hat, bekommt es gar nicht erst: das regelt die
     Medienabfrage im Blatt.

     NACHTRAEGLICH heisst hier: nach der ersten Geste, nicht schon beim
     `load`. Browser springen einen Anker mehrfach an, solange noch Bilder
     nachkommen — der letzte Versuch faellt genau auf das Ende des Ladens.
     Stand „smooth" da bereits, faehrt die Seite doch wieder von oben nach
     unten. Vor der ersten Geste rollt niemand freiwillig; danach kann es
     nur noch ein Klick gewesen sein. */
  function weichesRollen() {
    document.documentElement.style.scrollBehavior = 'smooth';
    ['pointerdown', 'keydown', 'wheel', 'touchstart'].forEach(function (art) {
      window.removeEventListener(art, weichesRollen);
    });
  }
  ['pointerdown', 'keydown', 'wheel', 'touchstart'].forEach(function (art) {
    window.addEventListener(art, weichesRollen, { passive: true });
  });

  // --- Buchungs-Links verdrahten -------------------------------------------
  const allgemein = ((document.body && document.body.dataset.bookingUrl) || '').trim();
  // Wohin, wenn es nichts zu buchen gibt — gesetzt von index.php, sobald
  // kein Buchungslink hinterlegt oder alles ausgebucht ist.
  const ersatz = ((document.body && document.body.dataset.bookingErsatz) || '').trim();
  // Beschriftung fuer denselben Zustand. Leer heisst: Beschriftungen so lassen.
  const ersatzText = ((document.body && document.body.dataset.bookingErsatzText) || '').trim();

  document.querySelectorAll('[data-booking]').forEach(function (el) {
    /* UEBERNIMMT DIE WARTELISTE, GILT SIE FUER ALLE — auch dann, wenn ein
       Buchungslink hinterlegt ist.

       Hier steckte der Fehler: die Zeile stand frueher unten im Zweig „kein
       Link vorhanden". Der Schalter im Panel setzte das Ersatzziel brav,
       aber solange ein Stripe-Link existierte, kam dieser Zweig nie dran.
       Ergebnis: der Knopf hiess „In Warteliste eintragen" und oeffnete
       Stripe. Wer darauf klickt, landet auf einer Bezahlseite, die er nicht
       gesucht hat — im schlimmsten Fall zahlt er. */
    if (ersatz) {
      el.setAttribute('href', ersatz);
      el.removeAttribute('target');
      el.removeAttribute('rel');
      // textContent und nicht innerHTML: was aus dem Panel kommt, wird hier
      // als Text eingesetzt und nicht als Markup ausgewertet.
      if (ersatzText) el.textContent = ersatzText;
      return;
    }

    const url = (el.dataset.bookingUrl || '').trim() || allgemein;

    // Der Platzhalter aus frueheren Fassungen zaehlt weiterhin als „leer";
    // sonst schickt ein vergessener Rest die Kundin auf eine 404. Ohne
    // Ersatzziel bleibt der Knopf dann auf seinem Ziel aus der Vorlage.
    if (!url || url.indexOf('HIER-DEINEN-LINK') > -1) return;

    el.setAttribute('href', url);
    el.setAttribute('target', '_blank');
    el.setAttribute('rel', 'noopener noreferrer');
  });

  if (!allgemein) {
    console.info('[B³] Kein Buchungslink hinterlegt — die Knoepfe fuehren zur '
      + 'Warteliste. Link setzen im Panel, Abschnitt „16 Buchung".');
  }

  // --- Einwilligung ---------------------------------------------------------
  // Aktuell gibt es nichts einzuwilligen: die Seite lädt nichts von fremden
  // Servern und setzt keine Cookies. Gespeichert wird nur die Entscheidung.
  // Kommt später ein Statistik- oder Marketing-Dienst dazu, gehört er in
  // loadOptional() — dann trägt der Banner ohne weiteren Umbau.
  const CONSENT_KEY = 'b3-consent';
  const consent = document.getElementById('consent');

  function readConsent() {
    try { return JSON.parse(localStorage.getItem(CONSENT_KEY)); } catch (e) { return null; }
  }

  function loadOptional() {
    // Hier später Statistik-/Marketing-Skripte einhängen. Bewusst leer.
  }

  window.b3Consent = {
    get: readConsent,
    optionalAllowed: function () { const c = readConsent(); return !!(c && c.optional); },
    reopen: function () { showConsent(); }
  };

  function measureStack() {
    const c = consent && consent.classList.contains('is-on') ? consent.offsetHeight : 0;
    document.body.style.setProperty('--consent-h', c + 'px');
    const barEl = document.getElementById('bar');
    const visible = barEl && barEl.classList.contains('is-on') && getComputedStyle(barEl).display !== 'none';
    document.body.style.setProperty('--bar-h', visible ? barEl.offsetHeight + 'px' : '0px');
  }

  function showConsent() {
    if (!consent) return;
    consent.hidden = false;
    requestAnimationFrame(function () {
      consent.classList.add('is-on');
      document.body.classList.add('consent-open');
      measureStack();
    });
  }

  function decide(optional) {
    try {
      localStorage.setItem(CONSENT_KEY, JSON.stringify({ necessary: true, optional: optional, ts: Date.now() }));
    } catch (e) { /* privater Modus: dann fragen wir beim nächsten Besuch erneut */ }
    if (optional) loadOptional();
    if (!consent) return;
    consent.classList.remove('is-on');
    document.body.classList.remove('consent-open');
    measureStack();
    setTimeout(function () { consent.hidden = true; }, 450);
  }

  if (consent) {
    consent.querySelectorAll('[data-consent]').forEach(function (el) {
      el.addEventListener('click', function () { decide(el.dataset.consent === 'all'); });
    });
    if (!readConsent()) showConsent();
    else if (readConsent().optional) loadOptional();
  }

  document.querySelectorAll('[data-consent-open]').forEach(function (el) {
    el.addEventListener('click', function (e) { e.preventDefault(); showConsent(); });
  });

  // --- Menü -----------------------------------------------------------------
  const burger = document.getElementById('burger');
  const menu = document.getElementById('menu');

  function setMenu(open) {
    if (!burger || !menu) return;
    burger.setAttribute('aria-expanded', String(open));
    burger.setAttribute('aria-label', open ? 'Menü schließen' : 'Menü öffnen');
    menu.classList.toggle('is-open', open);
    document.body.classList.toggle('nav-open', open);
  }

  if (burger && menu) {
    burger.addEventListener('click', function () {
      setMenu(burger.getAttribute('aria-expanded') !== 'true');
    });

    // Sprungmarke angeklickt: Panel schließen, sonst verdeckt es das Ziel
    menu.addEventListener('click', function (e) {
      if (e.target.closest('a')) setMenu(false);
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') setMenu(false);
    });

    // Zurück am Desktop soll kein offenes Panel hängen bleiben
    window.matchMedia('(min-width: 861px)').addEventListener('change', function (e) {
      if (e.matches) setMenu(false);
    });
  }

  // --- Kopfzeile, Buchungsleiste, Pfeil nach oben ---------------------------
  const head = document.getElementById('head');
  const bar = document.getElementById('bar');
  const totop = document.getElementById('totop');
  const anchor = document.getElementById('unterkunft');
  const ticket = document.getElementById('buchung');

  let ticking = false;

  function onScroll() {
    if (head) head.classList.toggle('is-stuck', window.scrollY > window.innerHeight * 0.85);

    if (totop) totop.classList.toggle('is-on', window.scrollY > window.innerHeight * 1.5);

    // Mobile Leiste: erst ab den Preisen, wieder weg beim Buchungsblock
    if (bar && anchor && ticket) {
      const start = anchor.getBoundingClientRect().top < 0;
      const stop = ticket.getBoundingClientRect().top < window.innerHeight;
      bar.classList.toggle('is-on', start && !stop);
    }
    measureStack();
    ticking = false;
  }

  window.addEventListener('scroll', function () {
    if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
  }, { passive: true });

  onScroll();
  window.addEventListener('resize', measureStack, { passive: true });

  // ==========================================================================
  // BEWEGUNG
  // ==========================================================================

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

  // --- Zeilen für die Maske zerlegen ----------------------------------------
  // Nur reine Textelemente: Auszeichnungen im Inneren würden verloren gehen.
  const LINE_SEL = 'h1, h2, .serif-line, .member__accent, .ablauf__note, .bms__sign, .abschluss__lines p';

  function splitLines(el) {
    const raw = el.dataset.text || el.textContent.trim();
    el.dataset.text = raw;

    // Wörter einzeln setzen, damit der Browser den Umbruch selbst bestimmt
    el.textContent = '';
    const probes = raw.split(' ').map(function (word, i) {
      const w = document.createElement('span');
      w.textContent = word;
      el.appendChild(w);
      if (i < raw.split(' ').length - 1) el.appendChild(document.createTextNode(' '));
      return w;
    });

    // Nach der Oberkante gruppieren — das ergibt die tatsächlichen Zeilen
    const lines = [];
    let top = null;
    probes.forEach(function (w) {
      const t = Math.round(w.offsetTop);
      if (top === null || Math.abs(t - top) > 3) { lines.push([]); top = t; }
      lines[lines.length - 1].push(w.textContent);
    });

    el.textContent = '';
    lines.forEach(function (words, i) {
      const outer = document.createElement('span');
      outer.className = 'ln';
      outer.style.setProperty('--i', i);
      const inner = document.createElement('span');
      inner.className = 'ln__i';
      // Leerzeichen am Zeilenende: sonst liest der Screenreader „Be freeto be you“
      inner.textContent = words.join(' ') + (i < lines.length - 1 ? ' ' : '');
      outer.appendChild(inner);
      el.appendChild(outer);
    });
  }

  const lineEls = [].filter.call(document.querySelectorAll(LINE_SEL), function (el) {
    return el.children.length === 0 && el.textContent.trim().length > 0;
  });

  if (!reduce.matches) lineEls.forEach(splitLines);

  // --- Reihenweiser Versatz in Listen ---------------------------------------
  // .stagger markiert den Behälter; er bekommt später selbst .is-in.
  const STAGGER = '.feats, .inkl__list ul, .fuerwen__q, .neumond__q ul, .faq__col, .timeline';

  document.querySelectorAll(STAGGER).forEach(function (list) {
    list.classList.add('stagger');
    [].forEach.call(list.children, function (el, i) { el.style.setProperty('--i', i); });
  });

  document.querySelectorAll('.moons, .site-nav').forEach(function (list) {
    const kids = list.matches('.site-nav') ? list.querySelectorAll('a, .site-nav__meta') : list.children;
    [].forEach.call(kids, function (el, i) { el.style.setProperty('--i', i); });
  });

  /* --- Warteliste absenden, ohne die Seite zu verlassen --------------------

     Vorher lief das Formular ueber den normalen Weg: absenden, umleiten auf
     /?warteliste=…#warteliste, ganze Startseite neu laden, von oben zum Anker
     hinunter. Der Blick war schon beim Formular, und trotzdem zog erst die
     halbe Seite vorbei. Fuer die Besucherin sieht das aus wie ein Aussetzer.

     Jetzt geht die Anfrage im Hintergrund hinaus und die Antwort erscheint an
     genau der Stelle, an der eben noch das Formular stand. Kein Neuladen,
     kein Sprung, keine Nachfrage „Formular erneut senden?".

     DER ALTE WEG BLEIBT. Ohne JavaScript (oder wenn die Anfrage scheitert)
     wird das Formular ganz normal abgeschickt und warteliste.php leitet um
     wie bisher. Das Formular traegt weiterhin method und action — hier wird
     nur abgefangen, was ohnehin funktionieren wuerde. */
  const wlFormular = document.querySelector('.wl__form');

  if (wlFormular && window.fetch && window.FormData) {
    const wlKnopf = wlFormular.querySelector('button[type="submit"]');
    let laeuft = false;

    /* Meldung setzen. Text und nicht Markup: was aus dem Panel kommt, wird
       angezeigt und nicht ausgewertet — dieselbe Regel wie in der Vorlage. */
    function wlMeldung(klasse, rolle, text) {
      const p = document.createElement('p');
      // .is-in von Hand: der Beobachter fuer .rise hat diesen Absatz beim
      // Laden nicht gesehen, sonst bliebe er auf opacity 0 stehen.
      p.className = klasse + ' is-in';
      p.setAttribute('role', rolle);
      p.textContent = text;
      return p;
    }

    function wlFehlerZeigen(text) {
      const alt = wlFormular.querySelector('.wl__fehler');
      const neu = wlMeldung('wl__fehler', 'alert', text);
      if (alt) {
        alt.replaceWith(neu);
      } else {
        wlFormular.insertBefore(neu, wlFormular.firstChild);
      }
    }

    wlFormular.addEventListener('submit', function (ev) {
      // Der Browser hat required und type="email" bereits geprueft, sonst
      // waere dieses Ereignis nicht ausgeloest worden.
      if (laeuft) { ev.preventDefault(); return; }
      ev.preventDefault();
      laeuft = true;

      const beschriftung = wlKnopf ? wlKnopf.textContent : '';
      if (wlKnopf) {
        wlKnopf.disabled = true;
        wlKnopf.setAttribute('aria-busy', 'true');
        wlKnopf.textContent = 'Wird gesendet …';
      }

      function zurueckAufAnfang() {
        laeuft = false;
        if (wlKnopf) {
          wlKnopf.disabled = false;
          wlKnopf.removeAttribute('aria-busy');
          wlKnopf.textContent = beschriftung;
        }
      }

      /* Scheitert die Anfrage (Netz weg, Server antwortet nicht wie
         erwartet), wird ganz normal abgeschickt. Lieber ein Neuladen als
         ein Formular, das nichts tut: der Eintrag ist wichtiger als die
         Bequemlichkeit. */
      function altenWegGehen() {
        laeuft = false;
        if (wlKnopf) {
          wlKnopf.disabled = false;
          wlKnopf.removeAttribute('aria-busy');
          wlKnopf.textContent = beschriftung;
        }
        HTMLFormElement.prototype.submit.call(wlFormular);
      }

      fetch(wlFormular.getAttribute('action') || '/warteliste.php', {
        method: 'POST',
        body: new FormData(wlFormular),
        headers: { 'X-B3-Formular': '1' },
        credentials: 'same-origin'
      }).then(function (antwort) {
        if (!antwort.ok) throw new Error('HTTP ' + antwort.status);
        return antwort.json();
      }).then(function (daten) {
        if (!daten || !daten.text) throw new Error('leere Antwort');

        if (daten.fertig) {
          /* Eingetragen. Das Formular hat ausgedient — es noch einmal
             anzubieten laedt dazu ein, sich zweimal einzutragen. Genau so
             haelt es index.php nach dem Umleiten. */
          wlFormular.replaceWith(wlMeldung('wl__danke', 'status', daten.text));
        } else {
          wlFehlerZeigen(daten.text);
          zurueckAufAnfang();
        }
      }).catch(altenWegGehen);
    });
  }

  // --- Einblenden ------------------------------------------------------------
  const targets = document.querySelectorAll('.rise, .ph, .moons, .medallion, .stagger, ' + LINE_SEL);

  if (reduce.matches || !('IntersectionObserver' in window)) {
    targets.forEach(function (el) { el.classList.add('is-in'); });
  } else {
    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        e.target.classList.add('is-in');
        io.unobserve(e.target);
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.05 });

    targets.forEach(function (el) { io.observe(el); });
  }

  // Der Hero wartet auf niemanden
  document.querySelectorAll('.hero .ph, .hero .rise, .hero h1, .hero .medallion')
    .forEach(function (el) { el.classList.add('is-in'); });

  // Umbrüche ändern sich mit der Breite — dann neu zerlegen
  let lastW = window.innerWidth, splitTimer;
  window.addEventListener('resize', function () {
    if (reduce.matches || window.innerWidth === lastW) return;
    lastW = window.innerWidth;
    clearTimeout(splitTimer);
    splitTimer = setTimeout(function () {
      lineEls.forEach(function (el) {
        const wasIn = el.classList.contains('is-in');
        splitLines(el);
        if (wasIn) el.classList.add('is-in');
      });
    }, 220);
  }, { passive: true });

  // --- Parallaxe und Lesefortschritt ----------------------------------------
  // Eine einzige rAF-Schleife, ausschließlich Transformationen.
  const pxEls = [].slice.call(document.querySelectorAll('[data-px]')).map(function (el) {
    return { wrap: el, img: el.querySelector('img'), amp: parseFloat(el.dataset.px) || 8 };
  }).filter(function (o) { return o.img; });

  const progress = document.getElementById('progress');
  let frameQueued = false;

  function paint() {
    const vh = window.innerHeight;

    pxEls.forEach(function (o) {
      const r = o.wrap.getBoundingClientRect();
      if (r.bottom < -200 || r.top > vh + 200) return;
      // -0.5 … +0.5, während das Element durch den Ausschnitt wandert
      const p = ((vh - r.top) / (vh + r.height)) - 0.5;
      o.img.style.transform = 'translate3d(0,' + (-p * o.amp).toFixed(2) + '%,0)';
    });

    if (progress) {
      const max = document.documentElement.scrollHeight - vh;
      progress.style.transform = 'scaleX(' + (max > 0 ? Math.min(1, window.scrollY / max) : 0) + ')';
    }
    frameQueued = false;
  }

  if (!reduce.matches && (pxEls.length || progress)) {
    window.addEventListener('scroll', function () {
      if (!frameQueued) { frameQueued = true; requestAnimationFrame(paint); }
    }, { passive: true });
    window.addEventListener('resize', paint, { passive: true });
    paint();
  }

  // --- FAQ: das Schließen soll genauso weich laufen wie das Öffnen ----------
  document.querySelectorAll('.faq details').forEach(function (d) {
    const sum = d.querySelector('summary');
    if (!sum) return;
    sum.addEventListener('click', function (e) {
      if (reduce.matches || !d.open) return;   // Öffnen macht der Browser selbst
      e.preventDefault();
      d.classList.add('is-closing');
      setTimeout(function () { d.open = false; d.classList.remove('is-closing'); }, 420);
    });
  });
})();

/* --- Bildergalerie der Unterkuenfte + Lupe ---------------------------------
   Die Galerie blaettert von Haus aus per Scroll-Snap; hier kommen nur die
   Pfeile, der Zaehler und die Vergroesserung dazu. Faellt dieses Skript aus,
   bleibt sie mit dem Finger bedienbar. */
(function () {
  var lupe = document.getElementById('lupe');
  if (!lupe) return;
  var lupenBild = lupe.querySelector('img');
  var zuletzt = null;                       // Fokus zurueckgeben, wo er herkam

  function oeffnen(quelle, text) {
    zuletzt = document.activeElement;
    lupenBild.src = quelle;
    lupenBild.alt = text || '';
    lupe.hidden = false;
    document.body.style.overflow = 'hidden';
    lupe.querySelector('.lupe__zu').focus();
  }
  function schliessen() {
    lupe.hidden = true;
    lupenBild.removeAttribute('src');
    document.body.style.overflow = '';
    if (zuletzt) zuletzt.focus();
  }

  lupe.addEventListener('click', function (e) {
    if (e.target !== lupenBild) schliessen();
  });
  document.addEventListener('keydown', function (e) {
    if (!lupe.hidden && e.key === 'Escape') schliessen();
  });

  Array.prototype.forEach.call(document.querySelectorAll('[data-galerie]'), function (g) {
    var spur    = g.querySelector('.galerie__spur');
    var bilder  = g.querySelectorAll('.galerie__bild');
    var zurueck = g.querySelector('.galerie__pfeil--zurueck');
    var vor     = g.querySelector('.galerie__pfeil--vor');
    var unter  = g.querySelector('.galerie__unterschrift');
    var balken = g.querySelector('.galerie__balken');
    if (!spur || !bilder.length) return;

    if (balken) {
      for (var k = 0; k < bilder.length; k++) balken.appendChild(document.createElement('i'));
    }

    function stand() {
      // Die Breite eines Bildes plus Abstand — nicht fest verdrahtet, damit
      // die Rechnung auch nach einer Aenderung im CSS stimmt.
      var schritt = bilder[0].getBoundingClientRect().width + 10;
      return Math.round(spur.scrollLeft / schritt);
    }
    function nachfuehren() {
      var i = Math.min(bilder.length - 1, Math.max(0, stand()));
      if (unter) unter.textContent = bilder[i].querySelector('img').alt;
      if (balken) {
        Array.prototype.forEach.call(balken.children, function (strich, n) {
          strich.className = n === i ? 'ist-da' : '';
        });
      }
      zurueck.disabled = i <= 0;
      vor.disabled     = i >= bilder.length - 1;
    }
    function blaettern(richtung) {
      var schritt = bilder[0].getBoundingClientRect().width + 10;
      spur.scrollBy({ left: richtung * schritt, behavior: 'smooth' });
    }

    zurueck.addEventListener('click', function () { blaettern(-1); });
    vor.addEventListener('click', function () { blaettern(1); });
    spur.addEventListener('scroll', function () {
      window.clearTimeout(spur._t);
      spur._t = window.setTimeout(nachfuehren, 90);
    });
    Array.prototype.forEach.call(bilder, function (b) {
      b.addEventListener('click', function () {
        oeffnen(b.getAttribute('data-gross'), b.querySelector('img').alt);
      });
    });
    nachfuehren();
  });
})();
