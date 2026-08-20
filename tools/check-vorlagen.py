#!/usr/bin/env python3
"""Gegenprobe zu check-pfade.php: welcher sichtbare Text steht FEST in einer
Vorlage, statt aus dem Panel zu kommen?

check-pfade.php geht den Weg Register -> Seite und beweist, dass kein Feld
wirkungslos ist. Diese Probe geht den umgekehrten Weg, Seite -> Register, und
beantwortet die andere Haelfte derselben Frage: kommt die Kundin ueberhaupt an
alles heran, was auf der Seite steht?

Der Anlass war ein Fund, kein Verdacht. Die drei Rechtsseiten trugen den
Seitenrahmen als festen Text — darunter Preise („Shared House ab 1.549 €") und
Termine („08.–11. Oktober 2026"). Beides steht im Panel und wird dort gepflegt.
Die Rechtsseiten haetten es stillschweigend nicht mitbekommen: Preis im Panel
geaendert, Fusszeile der AGB nennt weiter den alten. Sichtbar wird so etwas
erst, wenn jemand beide Seiten nebeneinanderlegt.

ERWARTETER REST: aria-label an Bedienelementen und Landmarken („Menü öffnen",
„Vorheriges Bild", „Hinweis zu Cookies") sowie der Markenzusatz im <title>.
Das sind Bedienhilfen, kein Inhalt — sie gehoeren nicht ins Panel, sonst kann
eine Textaenderung die Zugaenglichkeit zerlegen.

    python3 tools/check-vorlagen.py
"""
import pathlib
import re
import sys
from html.parser import HTMLParser

ROOT = pathlib.Path(__file__).resolve().parent.parent
PLATZHALTER = re.compile(r"\{\{.*?\}\}", re.S)
BUCHSTABE = re.compile(r"[A-Za-zÀ-ÿ]")

# Erwartet und in Ordnung — siehe Kopf der Datei.
GEDULDET = re.compile(r"^(\| B³ Retreats)$")


class Sichtbar(HTMLParser):
    def __init__(self):
        super().__init__()
        self.fund = []

    def handle_starttag(self, tag, attrs):
        a = dict(attrs)
        for name in ("alt", "aria-label", "title", "placeholder"):
            wert = (a.get(name) or "").strip()
            if wert and BUCHSTABE.search(wert):
                self.fund.append((f"@{name}", wert))
        if a.get("name") == "description":
            wert = (a.get("content") or "").strip()
            if wert and BUCHSTABE.search(wert):
                self.fund.append(("@description", wert))

    def handle_data(self, daten):
        wert = " ".join(daten.split())
        if wert and BUCHSTABE.search(wert):
            self.fund.append(("Text", wert))


def pruefen():
    offen = 0
    for datei in sorted((ROOT / "tools" / "templates").glob("*.html")):
        roh = datei.read_text(encoding="utf-8")
        # Das Markenzeichen ist gezeichneter Text, kein Inhalt.
        ohne = re.sub(r"<svg.*?</svg>", "", roh, flags=re.S)
        # <script> und <style> liefern keinen Lesetext.
        for tag in ("script", "style"):
            ohne = re.sub(rf"<{tag}.*?</{tag}>", "", ohne, flags=re.S)
        leser = Sichtbar()
        leser.feed(PLATZHALTER.sub(" ", ohne))

        rest = [(art, w) for art, w in leser.fund
                if not GEDULDET.match(w) and not (art == "@aria-label")]
        hilfen = sum(1 for art, _ in leser.fund if art == "@aria-label")
        if rest:
            offen += len(rest)
            print(f"\n{datei.name}: {len(rest)} fester Text ausserhalb des Panels")
            for art, w in rest:
                print(f"    {art:14} {w[:90]}")
        else:
            print(f"{datei.name}: alles aus dem Panel ({hilfen} aria-label, geduldet)")

    print()
    if offen:
        print(f"{offen} Stelle(n) stehen fest in der Vorlage. Wenn das Inhalt ist,")
        print("gehoert er ins Register — sonst laeuft er dem Panel davon.")
        return 1
    print("Kein Inhalt haengt fest in einer Vorlage.")
    return 0


if __name__ == "__main__":
    raise SystemExit(pruefen())
