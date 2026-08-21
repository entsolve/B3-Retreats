"""Importiert die Rechtstexte aus Klartext in den Panel-Bestand.

FRUEHER schrieb dieses Skript impressum.html, datenschutz.html und agb.html.
Das tut es nicht mehr. Die drei Seiten kommen jetzt aus dem Panel (Abschnitt
„22 Rechtstexte", Schluessel recht.<seite>.body) und werden von
tools/build-site.py gebaut wie jede andere Seite auch.

Der Grund fuer den Umbau: die Klartextquelle war als Erleichterung gedacht
(„Rechtstexte pflegen, ohne HTML anzufassen"), lag aber im Repo. Anfassen
konnte sie damit nur, wer eine Arbeitskopie und einen Python-Lauf hat — also
nicht die Kundin. Sie hatte ausgerechnet an den Seiten keinen Zugriff, fuer
deren Inhalt sie persoenlich haftet.

Was das Skript noch kann: den Klartext aus tools/content/legal.md und
tools/content/agb.txt nach HTML uebersetzen und in content/site.json
schreiben — der Weg, auf dem die Texte urspruenglich hineingekommen sind.

    python3 tools/build-legal.py --import

ACHTUNG, DER IMPORT IST VERLUSTBEHAFTET: die Klartextquellen kennen weder
Verweise noch Auszeichnung. Die AGB tragen inzwischen einen echten mailto-Link
und ein fettes „Stand: August 2026" — beides waere nach einem Import wieder
gewoehnlicher Text. Fuer eine Textrunde ist der Weg brauchbar, danach gehoert
das Feinere im Panel nachgezogen.

DAS UEBERSCHREIBT AUSSERDEM, WAS IM PANEL STEHT. Ohne --import passiert nichts, und das
ist Absicht: ein versehentlicher Lauf wuerde die Arbeit der Kundin still
zuruecksetzen. Der umgekehrte Weg — Panel-Stand zurueck in den Klartext —
existiert nicht; nach dem Import ist das Panel die Wahrheit.
"""
import html
import pathlib
import re
import sys

ROOT = pathlib.Path(__file__).resolve().parent.parent
SRC = ROOT / "tools" / "content"


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




def importieren():
    """Klartext -> HTML -> content/site.json. Ueberschreibt den Panel-Stand."""
    import json
    md = split_md((SRC / "legal.md").read_text(encoding="utf-8"))
    koerper = {
        "impressum":   blocks_to_html(md["IMPRESSUM"]),
        "datenschutz": blocks_to_html(md["DATENSCHUTZ"]),
        "agb":         build_agb((SRC / "agb.txt").read_text(encoding="utf-8")),
    }
    # Die Ueberschrift der ersten Ebene wiederholt den Seitentitel — sie stand
    # frueher im <h1> und darf im Rumpf nicht noch einmal auftauchen.
    for schluessel, h1 in (("impressum", "Impressum"), ("datenschutz", "Datenschutz"), ("agb", "AGB")):
        koerper[schluessel] = drop_echo(koerper[schluessel], h1)

    ziel = ROOT / "content" / "site.json"
    daten = json.loads(ziel.read_text(encoding="utf-8"))
    daten.setdefault("recht", {})
    for schluessel, html in koerper.items():
        entkerbt = "\n".join(z[6:] if z.startswith("      ") else z for z in html.split("\n"))
        daten["recht"].setdefault(schluessel, {})["body"] = entkerbt
        print(f"  {schluessel}: {len(entkerbt) / 1024:5.1f} KB")
    ziel.write_text(json.dumps(daten, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")
    print("content/site.json geschrieben — danach: python3 tools/build-site.py")

    notes = md.get("NOTES")
    if notes:
        (ROOT / "tools" / "content" / "OFFENE-PUNKTE.md").write_text(
            "# Offene Punkte zu den Rechtstexten\n\n" + "\n".join(notes).strip() + "\n", encoding="utf-8")
        print("        tools/content/OFFENE-PUNKTE.md")


if __name__ == "__main__":
    if "--import" in sys.argv:
        importieren()
    else:
        print(__doc__.strip())
        print()
        print("Nichts getan. Die Rechtstexte stehen im Panel, nicht mehr hier.")
