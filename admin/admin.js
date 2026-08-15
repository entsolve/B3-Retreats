/* Redaktionsoberflaeche — ohne Rahmenwerk, wie der Rest des Projekts.
 *
 * Der Aufbau ist bewusst schlicht: der Server liefert Inhalt und Schema, das
 * Schema beschreibt jedes Feld, und aus dieser Beschreibung wird das Formular
 * gebaut. Kommt ein Abschnitt auf der Seite dazu, wandert er in schema.json —
 * hier ist dafuer keine Zeile noetig.
 *
 * Ein Punkt verdient Erklaerung: Werte werden in site.json HTML-fertig
 * gespeichert, also mit &nbsp; und &amp;. Zum Bearbeiten werden sie aufgeloest
 * und beim Speichern wieder maskiert — aber NUR, wenn sie sich geaendert haben.
 * Sonst wuerde ein unberuehrtes Feld die Schreibweise wechseln (die Vorlage
 * mischt &ndash; und das Zeichen selbst), und die Ausgabe waere nicht mehr
 * zeichengleich mit dem, was vorher dastand.
 */
(function () {
  'use strict';

  const $ = (sel, root) => (root || document).querySelector(sel);
  const $$ = (sel, root) => Array.from((root || document).querySelectorAll(sel));

  const S = {
    content: null,      // der bearbeitete Stand
    pristine: null,     // der Stand beim Laden, fuer den Vergleich
    schema: [],
    images: [],
    backups: [],
    legal: {},
    profiles: [],
    group: null,
    dirty: false,
    upload: null,
  };

  const GROUP_LABELS = {
    meta: 'Suchmaschine und Vorschau',
    head: 'Kopfbereich',
    nav: 'Navigation',
    hero: 'Aufmacher',
    einladung: 'Einladung',
    bms: 'Body. Mind. Soul.',
    ablauf: 'Ablauf',
    neumond: 'Neumond',
    exp: 'Experiences',
    freizeit: 'Freie Zeit',
    ort: 'Der Ort',
    kulinarik: 'Kulinarik',
    haus: 'Unterkunft und Preise',
    inkl: 'Inklusive',
    team: 'Über uns',
    fuerwen: 'Für wen',
    buchung: 'Buchung',
    faq: 'Häufige Fragen',
    abschluss: 'Abschluss',
    foot: 'Fußzeile',
    consent: 'Cookie-Hinweis',
    bar: 'Mobile Buchungsleiste',
  };

  // --- Pfade ---------------------------------------------------------------

  function get(obj, path) {
    return path.split('.').reduce((node, key) => {
      if (node == null) return undefined;
      const m = key.match(/^(.*)\[(\d+)\]$/);
      return m ? (node[m[1]] || [])[Number(m[2])] : node[key];
    }, obj);
  }

  function set(obj, path, value) {
    const parts = path.split('.');
    let node = obj;
    for (let i = 0; i < parts.length - 1; i++) {
      if (node[parts[i]] == null) node[parts[i]] = {};
      node = node[parts[i]];
    }
    node[parts[parts.length - 1]] = value;
  }

  // --- Entities ------------------------------------------------------------
  // Nur die Handvoll, die in dieser Seite wirklich vorkommt.

  const DECODE = [
    [/&nbsp;/g, ' '], [/&middot;/g, '·'], [/&ndash;/g, '–'],
    [/&mdash;/g, '—'], [/&euro;/g, '€'], [/&shy;/g, '­'],
    [/&lt;/g, '<'], [/&gt;/g, '>'], [/&quot;/g, '"'], [/&amp;/g, '&'],
  ];

  function decode(value) {
    let out = String(value == null ? '' : value);
    // &amp; zuletzt aufloesen, sonst wird aus &amp;nbsp; ein echtes Leerzeichen
    for (const [re, ch] of DECODE) out = out.replace(re, ch);
    return out;
  }

  function encode(value, type) {
    let out = String(value == null ? '' : value).replace(/&/g, '&amp;');
    if (type !== 'html') out = out.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return out.replace(/ /g, '&nbsp;');
  }

  /* Nur maskieren, wenn der Text wirklich angefasst wurde. */
  function toStored(input, stored, type) {
    return input === decode(stored) ? stored : encode(input, type);
  }

  // --- Schema --------------------------------------------------------------

  function groupOf(path) { return String(path).split('.')[0]; }

  function groups() {
    const seen = [];
    for (const field of S.schema) {
      const g = groupOf(field.path);
      if (!seen.includes(g)) seen.push(g);
    }
    return seen;
  }

  function labelFor(group) { return GROUP_LABELS[group] || group; }

  // --- Formular ------------------------------------------------------------

  function el(tag, cls, text) {
    const node = document.createElement(tag);
    if (cls) node.className = cls;
    if (text != null) node.textContent = text;
    return node;
  }

  function renderSide() {
    const side = $('#side');
    side.textContent = '';
    groups().forEach((g, i) => {
      const b = el('button');
      b.type = 'button';
      b.dataset.group = g;
      b.append(el('i', null, String(i + 1).padStart(2, '0')), document.createTextNode(labelFor(g)));
      if (g === S.group) b.classList.add('is-on');
      b.addEventListener('click', () => { S.group = g; renderSide(); renderFields(); });
      side.append(b);
    });
  }

  function renderFields() {
    const form = $('#fields');
    form.textContent = '';
    $('#group-title').textContent = labelFor(S.group);
    $('#group-hint').textContent = '';

    const fields = S.schema.filter(f => groupOf(f.path) === S.group);
    for (const field of fields) form.append(buildField(field, '', S.content));
    applyFilter();
  }

  /* base = Pfadpraefix des umgebenden Listeneintrags, scope = das Objekt dazu */
  function buildField(field, base, scope) {
    const rel = String(field.path).replace(/^\./, '');
    const full = base ? base + '.' + rel : rel;

    if (field.type === 'list') return buildList(field, full, scope, rel);

    const wrap = el('div', 'field');
    wrap.dataset.type = field.type || 'text';
    wrap.dataset.path = full;

    const id = 'f_' + full.replace(/[^a-z0-9]/gi, '_');
    const label = el('label', null, field.label || rel);
    label.htmlFor = id;
    wrap.append(label);

    const stored = get(scope, rel);
    const shown = decode(stored);

    if (field.type === 'image') {
      wrap.append(buildImage(id, full, shown, field, scope, rel));
    } else if (field.type === 'choice') {
      const select = el('select');
      select.id = id;
      for (const c of field.choices || []) select.append(new Option(c.label, c.value));
      select.value = stored == null ? '' : String(stored);
      select.addEventListener('change', () => {
        // Feste Auswahl: der Wert ist vorgegeben, hier wird nichts maskiert.
        set(scope, rel, select.value);
        markChanged(wrap, full);
        touch();
      });
      wrap.append(select);
    } else {
      const long = field.type === 'textarea' || shown.length > 90;
      const input = el(long ? 'textarea' : 'input');
      if (!long) input.type = field.type === 'number' ? 'number' : field.type === 'url' ? 'url' : 'text';
      input.id = id;
      input.value = shown;
      input.addEventListener('input', () => {
        // Zahlen bleiben Zahlen — sonst stuende in site.json plötzlich "1250"
        const numeric = field.type === 'number' && input.value !== '' && !Number.isNaN(Number(input.value));
        set(scope, rel, numeric ? Number(input.value)
                                : toStored(input.value, get(S.pristine, full), field.type));
        markChanged(wrap, full);
        touch();
      });
      wrap.append(input);
    }

    if (field.hint) wrap.append(el('span', 'hint', field.hint));
    markChanged(wrap, full);
    return wrap;
  }

  function buildImage(id, full, shown, field, scope, rel) {
    const box = el('div', 'imgfield');
    const thumb = el('img');
    thumb.alt = '';
    thumb.src = '../' + shown;
    thumb.addEventListener('error', () => { thumb.removeAttribute('src'); });

    const right = el('div');
    const input = el('input');
    input.type = 'text';
    input.id = id;
    input.value = shown;

    const pick = el('div', 'imgfield__pick');
    const select = el('select');
    select.append(new Option('— aus dem Projekt wählen —', ''));
    for (const img of S.images) select.append(new Option(img.name, img.src));

    const commit = value => {
      input.value = value;
      thumb.src = '../' + value;
      set(scope, rel, toStored(value, get(S.pristine, full), field.type));
      markChanged(box.parentNode, full);
      touch();
    };

    input.addEventListener('input', () => commit(input.value));
    select.addEventListener('change', () => { if (select.value) commit(select.value); select.value = ''; });

    pick.append(select);
    right.append(input, pick);
    box.append(thumb, right);
    return box;
  }

  function buildList(field, full, scope, rel) {
    const items = get(scope, rel) || [];
    const wrap = el('div', 'field');
    wrap.dataset.path = full;

    const label = el('label', null, field.label || rel);
    wrap.append(label);

    const list = el('div', 'list');
    const head = el('div', 'list__head');
    head.append(el('strong', null, `${items.length} Einträge`));
    const add = el('button', 'tool', 'Eintrag hinzufügen');
    add.type = 'button';
    add.addEventListener('click', () => {
      const blank = {};
      for (const f of field.fields || []) blank[String(f.path).replace(/^\./, '')] = '';
      items.push(blank);
      touch();
      renderFields();
    });
    head.append(add);
    list.append(head);

    items.forEach((item, i) => list.append(buildItem(field, full, items, item, i)));
    wrap.append(list);
    if (field.hint) wrap.append(el('span', 'hint', field.hint));
    return wrap;
  }

  function buildItem(field, full, items, item, i) {
    const node = el('div', 'item');

    const nameField = field.itemLabel ? String(field.itemLabel).replace(/^\./, '') : null;
    const title = decode(nameField ? get(item, nameField) : '') || `Eintrag ${i + 1}`;

    const bar = el('button', 'item__bar');
    bar.type = 'button';
    bar.append(el('span', 'num', String(i + 1).padStart(2, '0')),
               document.createTextNode(title.slice(0, 70)),
               el('span', 'caret', '›'));
    bar.addEventListener('click', () => node.classList.toggle('is-open'));

    const body = el('div', 'item__body');
    for (const f of field.fields || []) {
      body.append(buildField(f, `${full}[${i}]`, item));
    }

    const tools = el('div', 'item__tools');
    const mk = (text, cls, fn) => {
      const b = el('button', 'tool' + (cls ? ' ' + cls : ''), text);
      b.type = 'button';
      b.addEventListener('click', fn);
      return b;
    };
    tools.append(
      mk('↑ nach oben', '', () => { if (i > 0) { items.splice(i - 1, 0, items.splice(i, 1)[0]); touch(); renderFields(); } }),
      mk('↓ nach unten', '', () => { if (i < items.length - 1) { items.splice(i + 1, 0, items.splice(i, 1)[0]); touch(); renderFields(); } }),
      mk('Eintrag löschen', 'tool--del', () => {
        if (!confirm(`„${title}“ wirklich löschen?`)) return;
        items.splice(i, 1); touch(); renderFields();
      }),
    );
    body.append(tools);

    node.append(bar, body);
    return node;
  }

  function markChanged(wrap, full) {
    if (!wrap || !wrap.classList) return;
    const now = get(S.content, full);
    const was = get(S.pristine, full);
    wrap.classList.toggle('is-changed', JSON.stringify(now) !== JSON.stringify(was));
  }

  function applyFilter() {
    const q = $('#filter').value.trim().toLowerCase();
    for (const f of $$('#fields .field')) {
      f.classList.toggle('is-hidden', Boolean(q) && !f.textContent.toLowerCase().includes(q));
    }
  }

  // --- Zustand -------------------------------------------------------------

  function touch() {
    S.dirty = true;
    $('#save').disabled = false;
    setState('nicht gespeichert', 'dirty');
  }

  function setState(text, kind) {
    const node = $('#state');
    node.textContent = text;
    if (kind) node.dataset.kind = kind; else node.removeAttribute('data-kind');
  }

  let toastTimer = null;
  function toast(message, kind) {
    const node = $('#toast');
    node.textContent = message;
    node.dataset.kind = kind || '';
    node.hidden = false;
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { node.hidden = true; }, kind === 'error' ? 9000 : 4000);
  }

  // --- Server --------------------------------------------------------------

  async function api(path, options) {
    const res = await fetch(path, options);
    const text = await res.text();
    let data;
    try { data = JSON.parse(text); } catch { data = { ok: false, error: text.slice(0, 400) }; }
    if (!res.ok && data.ok === undefined) data.ok = false;
    return data;
  }

  async function load() {
    const data = await api('/api/state');
    if (!data.ok) { toast(data.error || 'Laden fehlgeschlagen', 'error'); return; }

    S.content = data.content;
    S.pristine = JSON.parse(JSON.stringify(data.content));
    S.schema = data.schema || [];
    S.images = data.images || [];
    S.backups = data.backups || [];
    S.legal = data.legal || {};
    S.profiles = data.profiles || [];
    S.dirty = false;
    S.group = S.group && groups().includes(S.group) ? S.group : groups()[0];

    $('#save').disabled = true;
    setState('geladen', null);

    renderSide();
    renderFields();
    renderGallery();
    renderBackups();
    renderProfiles();
    showLegal($('.chip.is-on').dataset.legal);
  }

  async function save() {
    if (!S.dirty) return;
    $('#save').disabled = true;
    setState('speichert …', null);

    const data = await api('/api/content', {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(S.content),
    });

    if (!data.ok) {
      setState('Fehler', 'error');
      $('#save').disabled = false;
      toast(data.error || data.message || 'Speichern fehlgeschlagen', 'error');
      return;
    }

    S.pristine = JSON.parse(JSON.stringify(S.content));
    S.dirty = false;
    S.backups = data.backups || S.backups;
    setState('gespeichert', 'ok');
    toast(data.message || 'Gespeichert und neu gebaut', 'ok');
    renderFields();
    renderBackups();
    reloadPreview();
  }

  // --- Vorschau ------------------------------------------------------------

  function reloadPreview() {
    const frame = $('#preview');
    // Anhang mit Zaehler, sonst zeigt der Rahmen den Stand aus dem Zwischenspeicher
    frame.src = '../index.html?v=' + Date.now();
  }

  // --- Bilder --------------------------------------------------------------

  function renderProfiles() {
    const select = $('#up-profile');
    select.textContent = '';
    const NAMES = {
      outdoor: 'Außen — Himmel wird gedämpft',
      interior: 'Innenraum',
      detail: 'Detail und Stillleben',
      portrait: 'Porträt — schont Hauttöne',
      portrait_beton: 'Porträt vor Beton',
      sunset: 'Abendlicht',
      food: 'Essen',
    };
    for (const p of S.profiles) select.append(new Option(NAMES[p] || p, p));
  }

  function renderGallery() {
    const box = $('#gallery');
    box.textContent = '';
    for (const img of S.images) {
      const fig = el('figure');
      const im = el('img');
      im.src = '../' + img.src;
      im.alt = '';
      im.loading = 'lazy';
      fig.append(im, el('figcaption', null, `${img.name} · ${Math.round(img.size / 1024)} KB`));
      box.append(fig);
    }
  }

  function setupUpload() {
    const drop = $('#drop');
    const file = $('#file');
    const go = $('#up-go');

    const take = f => {
      if (!f || !f.type.startsWith('image/')) { toast('Das ist kein Bild', 'error'); return; }
      S.upload = f;
      drop.textContent = '';
      const im = el('img');
      im.src = URL.createObjectURL(f);
      im.alt = '';
      drop.append(im);
      if (!$('#up-name').value) {
        $('#up-name').value = f.name.replace(/\.[^.]+$/, '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
      }
      go.disabled = false;
    };

    file.addEventListener('change', () => take(file.files[0]));
    drop.addEventListener('dragover', e => { e.preventDefault(); drop.classList.add('is-over'); });
    drop.addEventListener('dragleave', () => drop.classList.remove('is-over'));
    drop.addEventListener('drop', e => {
      e.preventDefault();
      drop.classList.remove('is-over');
      take(e.dataTransfer.files[0]);
    });

    go.addEventListener('click', async () => {
      if (!S.upload) return;
      go.disabled = true;
      toast('Foto wird verarbeitet — das dauert einen Moment', null);

      const data = await api('/api/upload', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/octet-stream',
          'X-Bild-Name': $('#up-name').value,
          'X-Bild-Profil': $('#up-profile').value,
          'X-Bild-Seitenverhaeltnis': $('#up-aspect').value,
          'X-Bild-Breite': $('#up-width').value,
        },
        body: S.upload,
      });

      go.disabled = false;
      if (!data.ok) { toast(data.error || 'Verarbeiten fehlgeschlagen', 'error'); return; }
      S.images = data.images;
      renderGallery();
      renderFields();
      toast(`${data.image.src} angelegt (${data.image.width}×${data.image.height})`, 'ok');
    });
  }

  // --- Rechtstexte ---------------------------------------------------------

  function showLegal(name) {
    $$('.chip').forEach(c => c.classList.toggle('is-on', c.dataset.legal === name));
    $('#legal-text').value = S.legal[name] || '';
  }

  function setupLegal() {
    $$('.chip').forEach(chip => chip.addEventListener('click', () => showLegal(chip.dataset.legal)));
    $('#legal-save').addEventListener('click', async () => {
      const name = $('.chip.is-on').dataset.legal;
      const text = $('#legal-text').value;
      const data = await api('/api/legal', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ file: name, text }),
      });
      if (!data.ok) { toast(data.error || data.message || 'Fehlgeschlagen', 'error'); return; }
      S.legal[name] = text;
      toast(data.message || 'Rechtstexte neu gebaut', 'ok');
    });
  }

  // --- Sicherungen ---------------------------------------------------------

  function renderBackups() {
    const box = $('#backups');
    box.textContent = '';
    if (!S.backups.length) { box.append(el('p', 'hint', 'Noch keine Sicherung — sie entsteht beim ersten Speichern.')); return; }
    for (const b of S.backups) {
      const row = el('div', 'backup');
      const time = el('time', null, b.when);
      row.append(time, el('span', 'name', b.name));
      const btn = el('button', 'tool', 'zurückholen');
      btn.type = 'button';
      btn.addEventListener('click', async () => {
        if (!confirm(`Stand vom ${b.when} zurückholen? Der heutige Stand wird vorher gesichert.`)) return;
        const data = await api('/api/restore', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name: b.name }),
        });
        if (!data.ok) { toast(data.error || data.message, 'error'); return; }
        S.content = data.content;
        S.pristine = JSON.parse(JSON.stringify(data.content));
        S.backups = data.backups;
        S.dirty = false;
        $('#save').disabled = true;
        renderFields();
        renderBackups();
        reloadPreview();
        toast(data.message, 'ok');
      });
      row.append(btn);
      box.append(row);
    }
  }

  // --- Rahmen --------------------------------------------------------------

  function setupTabs() {
    $$('.tab').forEach(tab => tab.addEventListener('click', () => {
      $$('.tab').forEach(t => t.classList.toggle('is-on', t === tab));
      $$('.pane').forEach(p => p.classList.toggle('is-on', p.id === 'pane-' + tab.dataset.tab));
    }));
  }

  function setupPreview() {
    $$('.preview__sizes button').forEach(b => b.addEventListener('click', () => {
      $$('.preview__sizes button').forEach(x => x.classList.toggle('is-on', x === b));
      $('#preview').style.width = b.dataset.w + 'px';
    }));
    $('#preview-reload').addEventListener('click', reloadPreview);
  }

  function setup() {
    setupTabs();
    setupPreview();
    setupUpload();
    setupLegal();

    $('#save').addEventListener('click', save);
    $('#reload').addEventListener('click', () => {
      if (S.dirty && !confirm('Nicht gespeicherte Änderungen verwerfen?')) return;
      load();
    });
    $('#filter').addEventListener('input', applyFilter);

    document.addEventListener('keydown', e => {
      if ((e.metaKey || e.ctrlKey) && e.key === 's') { e.preventDefault(); save(); }
    });

    window.addEventListener('beforeunload', e => {
      if (S.dirty) { e.preventDefault(); e.returnValue = ''; }
    });

    load();
  }

  document.addEventListener('DOMContentLoaded', setup);
}());
