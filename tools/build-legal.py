"""Erzeugt impressum.html, datenschutz.html und agb.html.

Quelle sind zwei Klartextdateien, damit Christina die Rechtstexte pflegen kann,
ohne HTML anzufassen: tools/content/legal.md und tools/content/agb.txt.
"""
import html
import pathlib
import re

ROOT = pathlib.Path(__file__).resolve().parent.parent
SRC = ROOT / "tools" / "content"

HEAD = """<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{title} | B³ Retreats</title>
<meta name="description" content="{desc}">
<meta name="robots" content="{robots}">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs>
  <g id="b3mark">
    <path d="M90.97 15.76 A54 54 0 1 1 73.98 7.84" fill="none" stroke="currentColor" stroke-width="1.1"/>
    <path d="M94.71 18.64 A54 54 0 0 1 110.74 41.53" fill="none" stroke="currentColor" stroke-width="1.1"/>
    <circle cx="82.82" cy="11.06" r="2.6" fill="currentColor"/>
    <text x="58" y="74" text-anchor="middle" font-size="48" fill="currentColor"
          font-family="'Cormorant Garamond', Georgia, serif" font-weight="300">B<tspan font-size="25" dy="-17">3</tspan></text>
    <text x="64.8" y="93" text-anchor="middle" font-size="8.5" fill="currentColor" letter-spacing="3.6"
          font-family="Manrope, system-ui, sans-serif" font-weight="400">RETREATS</text>
  </g>
</defs></svg>

<header class="site-head site-head--solid">
  <div class="wrap site-head__in">
    <a href="/" aria-label="B³ Retreats – Startseite">
      <svg class="mark" viewBox="0 0 120 120" width="76" height="76"><use href="#b3mark"/></svg>
    </a>
    <nav class="site-nav">
      <a href="/">Zurück zur Startseite</a>
    </nav>
  </div>
</header>

<main class="legal band-ivory">
  <div class="wrap grid">
    <div class="legal__head">
      <p class="eyebrow">Rechtliches</p>
      <h1>{h1}</h1>
    </div>
    <div class="legal__body">
{body}
    </div>
  </div>
</main>

<footer class="site-foot">
  <div class="wrap site-foot__in">
    <div class="site-foot__brand">
      <svg class="mark mark--invert" viewBox="0 0 120 120" width="96" height="96"><use href="#b3mark"/></svg>
      <p class="small">Body. Mind. Soul.<br>Yoga &middot; Astro &middot; Business</p>
      <p class="small">08.&ndash;11. Oktober 2026<br>Spabrücken, Rheinland-Pfalz</p>
    </div>

    <nav class="site-foot__col" aria-label="Retreat">
      <p class="site-foot__label">Retreat</p>
      <a href="/">Startseite</a>
      <a href="/#experiences">Programm</a>
      <a href="/#ort">Der Ort</a>
      <a href="/#unterkunft">Unterkunft &amp; Preise</a>
      <a href="/#team">Über uns</a>
      <a href="/#faq">Häufige Fragen</a>
    </nav>

    <nav class="site-foot__col" aria-label="Rechtliches">
      <p class="site-foot__label">Rechtliches</p>
      <a href="impressum.html">Impressum</a>
      <a href="datenschutz.html">Datenschutz</a>
      <a href="agb.html">AGB</a>
      <a href="impressum.html#kontakt">Kontakt</a>
      <button class="linklike" type="button" data-consent-open>Cookie-Einstellungen</button>
    </nav>

    <div class="site-foot__col site-foot__book">
      <p class="site-foot__label">Buchung</p>
      <p class="small">Shared House ab 1.549 &euro; pro Person<br>Friends Special 3.950 &euro; für zwei</p>
      <a class="btn btn--invert" href="/#buchung" data-booking>Meinen Platz sichern</a>
    </div>

    <p class="small site-foot__legal">
      B³ Retreats &middot; Christina Brumm &middot; An den Nahewiesen 20, 55450 Langenlonsheim
      <span>Buchung und Zahlungsabwicklung über Tentary</span>
    </p>
  </div>
</footer>

<aside class="consent" id="consent" hidden aria-label="Hinweis zu Cookies">
  <div class="wrap consent__in">
    <div class="consent__tx">
      <p class="eyebrow">Cookies</p>
      <p>Wir setzen nur technisch notwendige Cookies. Optionale Cookies für Statistik oder Marketing
         erst mit deiner Zustimmung — mehr dazu in der <a class="link" href="datenschutz.html">Datenschutzerklärung</a>.</p>
    </div>
    <div class="consent__btns">
      <button class="btn btn--ghost" type="button" data-consent="necessary">Nur notwendige</button>
      <button class="btn btn--primary" type="button" data-consent="all">Alle akzeptieren</button>
    </div>
  </div>
</aside>

<script src="assets/js/main.js"></script>
</body>
</html>
"""

TODO = re.compile(r"\[BITTE ERGÄNZEN:(.*?)\]", re.S)


def inline(text):
    out = html.escape(text.strip())
    out = TODO.sub(lambda m: f'<span class="todo">Bitte ergänzen:{m.group(1)}</span>',
                   out.replace("[BITTE ERG&#xC4;NZEN:", "[BITTE ERGÄNZEN:"))
    return out


UML = str.maketrans({"ä": "ae", "ö": "oe", "ü": "ue", "ß": "ss",
                     "Ä": "ae", "Ö": "oe", "Ü": "ue"})


def slug(text):
    out = re.sub(r"[^a-z0-9]+", "-", text.lower().translate(UML))
    return out.strip("-")[:48].strip("-")


def join_para(lines):
    """Anschriften bestehen aus kurzen Zeilen ohne Satzzeichen — die bleiben umbrochen."""
    if len(lines) > 1 and all(len(l) <= 55 and not l.endswith((".", ":", "!", "?")) for l in lines):
        return "<br>\n      ".join(inline(l) for l in lines)
    return inline(" ".join(lines))


def blocks_to_html(lines, level=2):
    out, para, items = [], [], []

    def flush():
        if items:
            out.append("      <ul>\n" + "\n".join(f"        <li>{inline(i)}</li>" for i in items) + "\n      </ul>")
            items.clear()
        if para:
            out.append(f"      <p>{join_para(para)}</p>")
            para.clear()

    for raw in lines:
        line = raw.rstrip()
        if line.startswith("## "):
            flush()
            head = line[3:]
            out.append(f'      <h{level} id="{slug(head)}">{inline(head)}</h{level}>')
        elif line.lstrip().startswith(("- ", "• ", "•\t")):
            if para:
                flush()
            items.append(line.lstrip()[2:])
        elif not line.strip():
            flush()
        else:
            if items:
                flush()
            para.append(line.strip())
    flush()
    return "\n".join(out)


def split_md(text):
    parts, cur, name = {}, [], None
    for line in text.splitlines():
        if line.startswith("# "):
            if name:
                parts[name] = cur
            name, cur = line[2:].strip(), []
        else:
            cur.append(line)
    if name:
        parts[name] = cur
    return parts


def build_agb(text):
    """AGB kommen als Fliesstext aus dem Word-Dokument, nicht als Markdown."""
    out, para, items = [], [], []

    def flush():
        if items:
            out.append("      <ul>\n" + "\n".join(f"        <li>{inline(i)}</li>" for i in items) + "\n      </ul>")
            items.clear()
        if para:
            out.append(f"      <p>{join_para(para)}</p>")
            para.clear()

    for raw in text.splitlines():
        line = raw.strip()
        if re.match(r"^§\s*\d+", line):
            flush()
            out.append(f'      <h2 id="{slug(line)}">{inline(line)}</h2>')
        elif line.startswith("•"):
            if para:
                flush()
            items.append(line.lstrip("• \t"))
        elif not line:
            flush()
        else:
            if items:
                flush()
            para.append(line)
    flush()
    return "\n".join(out)


def drop_echo(body, h1):
    first = f'      <h2 id="{slug(h1)}">{h1}</h2>\n' 
    return body[len(first):] if body.startswith(first) else body


def write(path, title, h1, desc, body, robots="noindex, follow"):
    body = drop_echo(body, h1)
    (ROOT / path).write_text(
        HEAD.format(title=title, h1=h1, desc=desc, body=body, robots=robots), encoding="utf-8")
    print(f"{(ROOT / path).stat().st_size / 1024:6.1f} KB  {path}")


if __name__ == "__main__":
    md = split_md((SRC / "legal.md").read_text(encoding="utf-8"))

    write("impressum.html", "Impressum", "Impressum",
          "Impressum und Anbieterkennzeichnung von B³ Retreats, Christina Brumm, Langenlonsheim.",
          blocks_to_html(md["IMPRESSUM"]))

    write("datenschutz.html", "Datenschutzerklärung", "Datenschutz",
          "Informationen zur Verarbeitung personenbezogener Daten auf b3-retreats.de nach Art. 13 DSGVO.",
          blocks_to_html(md["DATENSCHUTZ"]))

    write("agb.html", "Allgemeine Geschäftsbedingungen", "AGB",
          "Allgemeine Geschäftsbedingungen für die Teilnahme an B³ Retreats.",
          build_agb((SRC / "agb.txt").read_text(encoding="utf-8")))

    notes = md.get("NOTES")
    if notes:
        (ROOT / "tools" / "content" / "OFFENE-PUNKTE.md").write_text(
            "# Offene Punkte zu den Rechtstexten\n\n" + "\n".join(notes).strip() + "\n", encoding="utf-8")
        print("        tools/content/OFFENE-PUNKTE.md")
