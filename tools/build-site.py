"""Erzeugt index.html aus content/site.json und tools/templates/index.html.

Der Grundgedanke ist derselbe wie bei build-legal.py: Christina soll Inhalte
pflegen koennen, ohne HTML anzufassen. Nur ist die Startseite zu gross fuer eine
Klartextdatei — deshalb liegt sie als JSON vor und wird ueber eine Redaktions-
oberflaeche bearbeitet (tools/admin-server.py).

Die Vorlage kennt genau drei Konstrukte, mehr braucht eine Seite nicht:

    {{ pfad.zum.wert }}                      ein Wert
    {{# pfad.zur.liste }} … {{/ pfad.zur.liste }}   Wiederholung
    {{? pfad.zum.wert }} … {{/? pfad.zum.wert }}    nur wenn gefuellt

Innerhalb einer Wiederholung greift {{ .feld }} auf den aktuellen Eintrag zu.
Oeffnende und schliessende Marker stehen allein auf einer Zeile; ihre Zeile
verschwindet beim Rendern, damit die Einrueckung der Ausgabe stimmt.

Werte werden WOERTLICH eingesetzt, nicht maskiert: in site.json steht bereits
HTML-fertiger Text mit &nbsp;, &middot; und Co. Das Maskieren passiert eine Ebene
hoeher, in der Oberflaeche — nur so kommt beim Zurueckschreiben wieder exakt
dieselbe Datei heraus.

Aufruf:
    python3 tools/build-site.py            # index.html schreiben
    python3 tools/build-site.py --check    # nur pruefen, nichts schreiben
"""
import hashlib
import json
import pathlib
import re
import sys

ROOT = pathlib.Path(__file__).resolve().parent.parent
CONTENT = ROOT / "content" / "site.json"
TEMPLATE = ROOT / "tools" / "templates" / "index.html"
TARGET = ROOT / "index.html"

# Dateinamen aendern sich nie: assets/img/hero-tall.webp heisst nach jeder
# Neuberechnung wieder genauso. Der Browser hat sie dann laengst im Cache und
# zeigt weiter die alte Fassung — dieselbe Datei, anderer Inhalt. Das kostete in
# der Abstimmung eine ganze Runde ("du hast das Bild ja gar nicht geaendert").
# Deshalb bekommt jede oertliche Datei im erzeugten HTML ein ?v= mit den ersten
# acht Stellen ihres Inhalts-Hashes: gleicher Inhalt -> gleiche URL -> Cache
# greift wie vorher; geaenderter Inhalt -> neue URL -> Browser laedt neu.
VERSIONIERT = re.compile(r'(?:src|href|srcset)="(assets/[^"?]+\.(?:webp|css|js|woff2|svg|png|ico))"')

VALUE = re.compile(r"\{\{\s*([^#/?][^}]*?)\s*\}\}")
OPEN_LIST = re.compile(r"^(\s*)\{\{#\s*([^}]+?)\s*\}\}\s*$")
CLOSE_LIST = re.compile(r"^\s*\{\{/\s*([^}?][^}]*?)\s*\}\}\s*$")
OPEN_COND = re.compile(r"^(\s*)\{\{\?\s*([^}]+?)\s*\}\}\s*$")
CLOSE_COND = re.compile(r"^\s*\{\{/\?\s*([^}]+?)\s*\}\}\s*$")
INDEXED = re.compile(r"^(.+)\[(\d+)\]$")


class TemplateError(Exception):
    """Fehler in der Vorlage oder fehlender Wert — immer mit Zeilennummer."""


def lookup(path, scopes, where):
    """Loest 'a.b.c' oder '.feld' gegen den Stapel offener Gueltigkeitsbereiche auf."""
    if path.startswith("."):
        if len(scopes) < 2:
            raise TemplateError(f"{where}: '{path}' steht ausserhalb einer Wiederholung")
        node, parts = scopes[-1], path[1:].split(".")
    else:
        node, parts = scopes[0], path.split(".")

    for i, part in enumerate(parts):
        if part == "":
            continue
        missing = ".".join(parts[: i + 1])

        # 'items[2]' — ein Eintrag darf auch direkt angesprochen werden, nicht
        # jede Wiederholung auf der Seite ist gleich gebaut.
        index = INDEXED.match(part)
        if index:
            part, position = index.group(1), int(index.group(2))
            if not isinstance(node, dict) or part not in node:
                raise TemplateError(f"{where}: '{missing}' fehlt in site.json")
            node = node[part]
            if not isinstance(node, list):
                raise TemplateError(f"{where}: '{part}' ist keine Liste")
            if position >= len(node):
                raise TemplateError(f"{where}: '{part}' hat nur {len(node)} Eintraege, "
                                    f"gebraucht wird Nummer {position + 1}")
            node = node[position]
            continue

        if isinstance(node, list):
            raise TemplateError(f"{where}: '{path}' zeigt auf eine Liste, nicht auf einen Wert")
        if not isinstance(node, dict) or part not in node:
            raise TemplateError(f"{where}: '{missing}' fehlt in site.json")
        node = node[part]
    return node


def substitute(line, scopes, where):
    def one(m):
        value = lookup(m.group(1), scopes, where)
        if isinstance(value, (dict, list)):
            raise TemplateError(f"{where}: '{m.group(1)}' ist kein einfacher Wert")
        return "" if value is None else str(value)

    return VALUE.sub(one, line)


def render_block(lines, start, end, scopes, out):
    """Rendert lines[start:end]; jede Zeile ist ein (Nummer, Text)-Paar."""
    i = start
    while i < end:
        no, text = lines[i]
        where = f"Zeile {no}"

        m = OPEN_LIST.match(text)
        if m:
            path = m.group(2)
            close = find_close(lines, i, end, CLOSE_LIST, path)
            items = lookup(path, scopes, where)
            if not isinstance(items, list):
                raise TemplateError(f"{where}: '{path}' ist keine Liste")
            for item in items:
                render_block(lines, i + 1, close, scopes + [item], out)
            i = close + 1
            continue

        m = OPEN_COND.match(text)
        if m:
            path = m.group(2)
            close = find_close(lines, i, end, CLOSE_COND, path)
            if lookup(path, scopes, where):
                render_block(lines, i + 1, close, scopes, out)
            i = close + 1
            continue

        if CLOSE_LIST.match(text) or CLOSE_COND.match(text):
            raise TemplateError(f"{where}: schliessender Marker ohne oeffnenden")

        out.append(substitute(text, scopes, where))
        i += 1


def find_close(lines, open_at, end, pattern, path):
    """Sucht den passenden schliessenden Marker, Verschachtelung mitgezaehlt."""
    opener = OPEN_LIST if pattern is CLOSE_LIST else OPEN_COND
    depth = 0
    for j in range(open_at + 1, end):
        text = lines[j][1]
        m = opener.match(text)
        if m and m.group(2) == path:
            depth += 1
            continue
        m = pattern.match(text)
        if m and m.group(1) == path:
            if depth == 0:
                return j
            depth -= 1
    raise TemplateError(f"Zeile {lines[open_at][0]}: '{path}' wird nie geschlossen")


def render(template_text, content):
    lines = [(n, t) for n, t in enumerate(template_text.split("\n"), 1)]
    out = []
    render_block(lines, 0, len(lines), [content], out)
    return "\n".join(out)


# --- Preise gegenlesen -------------------------------------------------------
# Derselbe Betrag steht an sieben Stellen: zweimal in der strukturierten
# Auszeichnung fuer Google, im Preisblock, in der Fusszeile und in der mobilen
# Leiste. Wer eine davon vergisst, hat kein Schoenheitsproblem — die Auszeichnung
# meldet der Suchmaschine dann einen Preis, den es nicht gibt.

AMOUNT = re.compile(r"\d{1,3}(?:\.\d{3})+|\d+")

PRICE_FIELDS = [
    "haus.shared.price",
    "haus.friends.price",
    "foot.booking.prices",
    "bar.price",
]


def amounts(text):
    """Alle Betraege in einem Text, deutsche Tausenderpunkte mitgedacht."""
    found = set()
    for match in AMOUNT.finditer(str(text)):
        value = int(match.group(0).replace(".", ""))
        if value >= 100:  # Jahreszahlen und Uhrzeiten interessieren hier nicht
            found.add(value)
    return found


def collect(content, path):
    try:
        return lookup(path, [content], path)
    except TemplateError:
        return None


def check_prices(content):
    offers = content.get("meta", {}).get("jsonld", {}).get("offers", {})
    expected = set()
    for offer in offers.values():
        expected |= amounts(offer.get("price", ""))
    if not expected:
        return []

    warnings = []
    seen = set()

    for path in PRICE_FIELDS:
        value = collect(content, path)
        if value is None:
            continue
        here = amounts(value)
        seen |= here
        strange = here - expected
        if strange:
            warnings.append(f"{path}: {', '.join(f'{v:n}' for v in sorted(strange))} "
                            f"steht nicht in der Auszeichnung fuer Google")

    for entry in collect(content, "buchung.preise") or []:
        here = amounts(entry.get("preis", ""))
        seen |= here
        strange = here - expected
        if strange:
            warnings.append(f"buchung.preise „{entry.get('label', '?')}“: "
                            f"{', '.join(str(v) for v in sorted(strange))} "
                            f"steht nicht in der Auszeichnung fuer Google")

    for value in sorted(expected - seen):
        warnings.append(f"Der Preis {value} steht in der Auszeichnung fuer Google, "
                        f"aber in keinem sichtbaren Preisfeld")

    return warnings


def first_difference(a, b):
    """Liefert eine lesbare Beschreibung der ersten Abweichung."""
    a_lines, b_lines = a.split("\n"), b.split("\n")
    for i in range(max(len(a_lines), len(b_lines))):
        left = a_lines[i] if i < len(a_lines) else "<Datei zu Ende>"
        right = b_lines[i] if i < len(b_lines) else "<Datei zu Ende>"
        if left != right:
            return f"Zeile {i + 1}\n  erzeugt: {left!r}\n  Bestand: {right!r}"
    return None


def versionieren(html):
    """?v=<Inhalts-Hash> an jede oertliche Datei haengen (siehe VERSIONIERT).

    Fehlt eine Datei, bleibt ihre URL unveraendert — ein fehlendes Bild soll
    hier keinen Seitenbau abbrechen, das meldet build-assets.py an seiner
    Stelle deutlicher.
    """
    def ersetzen(treffer):
        pfad = ROOT / treffer.group(1)
        if not pfad.exists():
            return treffer.group(0)
        h = hashlib.sha256(pfad.read_bytes()).hexdigest()[:8]
        return treffer.group(0).replace(treffer.group(1), f"{treffer.group(1)}?v={h}")
    return VERSIONIERT.sub(ersetzen, html)


def build(check_only=False):
    for path in (CONTENT, TEMPLATE):
        if not path.exists():
            print(f"fehlt: {path.relative_to(ROOT)}", file=sys.stderr)
            return 2

    content = json.loads(CONTENT.read_text(encoding="utf-8"))
    template_text = TEMPLATE.read_text(encoding="utf-8")

    try:
        result = render(template_text, content)
    except TemplateError as exc:
        print(f"Vorlage: {exc}", file=sys.stderr)
        return 1

    result = versionieren(result)

    if check_only:
        if not TARGET.exists():
            print("index.html existiert noch nicht — nichts zu vergleichen")
            return 0
        diff = first_difference(result, TARGET.read_text(encoding="utf-8"))
        if diff:
            print("Abweichung zum Bestand:\n" + diff, file=sys.stderr)
            return 1
        print("index.html ist zeichengleich mit der Vorlage — Rueckweg stimmt")
        return 0

    TARGET.write_text(result, encoding="utf-8")
    print(f"index.html geschrieben ({len(result.splitlines())} Zeilen)")

    # Warnung, kein Abbruch: die Seite ist gebaut, sie stimmt nur womoeglich
    # nicht mit sich selbst ueberein.
    for warning in check_prices(content):
        print(f"Achtung — {warning}")
    return 0


if __name__ == "__main__":
    raise SystemExit(build(check_only="--check" in sys.argv))
