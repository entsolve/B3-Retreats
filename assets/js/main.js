/* ==========================================================================
   B³ RETREATS

   >>> HIER DIE BUCHUNGS-URL EINTRAGEN <<<
   Eine Zeile — alle Buttons der Seite übernehmen sie automatisch und öffnen
   in einem neuen Tab. Solange der Platzhalter steht, springen die Buttons
   nur zum Buchungsblock, damit nichts ins Leere führt.
   ========================================================================== */

const BOOKING_URL = 'https://tentary.com/HIER-DEINEN-LINK-EINSETZEN';

/* Zwei getrennte Tarife? Dann stattdessen so:
   const BOOKING_URL = {
     default: 'https://tentary.com/...',            // alle allgemeinen Buttons
     shared:  'https://tentary.com/...shared-house',
     friends: 'https://tentary.com/...friends-special'
   };
   und am Button data-booking="shared" bzw. data-booking="friends" setzen. */

(function () {
  'use strict';

  // --- Buchungs-Links verdrahten -------------------------------------------
  const isPlaceholder = typeof BOOKING_URL === 'string' && BOOKING_URL.indexOf('HIER-DEINEN-LINK') > -1;

  function urlFor(key) {
    if (typeof BOOKING_URL === 'string') return BOOKING_URL;
    return BOOKING_URL[key] || BOOKING_URL.default;
  }

  document.querySelectorAll('[data-booking]').forEach(function (el) {
    if (isPlaceholder) {
      el.setAttribute('href', '#buchung');
      return;
    }
    el.setAttribute('href', urlFor(el.dataset.booking));
    el.setAttribute('target', '_blank');
    el.setAttribute('rel', 'noopener noreferrer');
  });

  if (isPlaceholder) {
    console.info('[B³] BOOKING_URL ist noch ein Platzhalter — assets/js/main.js, Zeile 11.');
  }

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
    ticking = false;
  }

  window.addEventListener('scroll', function () {
    if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
  }, { passive: true });

  onScroll();

  // --- Einblenden -----------------------------------------------------------
  // Bewegungsbudget: Deckkraft + 6px, einmalig. Kein Stagger, kein Parallax.
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
  const targets = document.querySelectorAll('.rise, .ph');

  if (reduce.matches || !('IntersectionObserver' in window)) {
    targets.forEach(function (el) { el.classList.add('is-in'); });
  } else {
    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        e.target.classList.add('is-in');
        io.unobserve(e.target);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.06 });

    targets.forEach(function (el) { io.observe(el); });
  }

  // Der Hero soll nie auf den Observer warten
  document.querySelectorAll('.hero .ph, .hero .rise').forEach(function (el) { el.classList.add('is-in'); });
})();
