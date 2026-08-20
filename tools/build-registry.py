#!/usr/bin/env python3
"""Erzeugt partials/registry/*.php und db/seed.sql aus dem, was schon da ist.

WARUM GENERIERT UND NICHT VON HAND: das Register braucht fuer jedes der 240
Felder Beschriftung, Typ, Gruppe und Standardwert. Beschriftung, Typ und Hinweis
stehen bereits in admin/schema.json (die oertliche Oberflaeche lebt davon), die
Werte in content/site.json. Beides von Hand nach PHP zu uebertragen waere eine
Fehlerquelle ohne Gegenwert — und bei der naechsten Textaenderung waere es
wieder falsch.

    python3 tools/build-registry.py            # schreiben
    python3 tools/build-registry.py --check    # nur pruefen, nichts schreiben

Was entsteht:

  partials/registry/<abschnitt>.php   Standardwerte + Beschriftungen, ein File
                                      je Abschnitt der Seite (21 Stueck). Ohne
                                      Datenbank rendert die Seite hieraus.
  db/seed.sql                         INSERTs fuer die content-Tabelle. Optional:
                                      ohne seed greifen die Registry-Standards,
                                      MIT seed stehen die Texte auch in der
                                      Datenbank und sind im Panel sichtbar.

Der Abschnitt ergibt sich aus dem ersten Teil des Pfades: "hero.headline" ->
hero. Genau so gruppiert die oertliche Oberflaeche auch, es entsteht also keine
zweite Wahrheit.
"""
import json
import pathlib
import re
import sys

ROOT = pathlib.Path(__file__).resolve().parent.parent
SCHEMA = ROOT / "admin" / "schema.json"
CONTENT = ROOT / "content" / "site.json"
REGISTRY = ROOT / "partials" / "registry"
SEED = ROOT / "db" / "seed.sql"

# Deutsche Abschnittsnamen fuer das Panel. Dieselbe Reihenfolge und dieselben
# Namen wie in der oertlichen Oberflaeche — die Kundin soll nicht zwei
# verschiedene Gliederungen lernen muessen.
ABSCHNITTE = {
    "meta":      ("01", "Suchmaschine und Vorschau"),
    "nav":       ("02", "Navigation"),
    "hero":      ("03", "Aufmacher"),
    "einladung": ("04", "Einladung"),
    "bms":       ("05", "Body. Mind. Soul."),
    "ablauf":    ("06", "Ablauf"),
    "neumond":   ("07", "Neumond"),
    "exp":       ("08", "Experiences"),
    "freizeit":  ("09", "Freie Zeit"),
    "ort":       ("10", "Der Ort"),
    "kulinarik": ("11", "Kulinarik"),
    "haus":      ("12", "Unterkunft und Preise"),
    "inkl":      ("13", "Inklusive"),
    "team":      ("14", "Über uns"),
    "fuerwen":   ("15", "Für wen"),
    "buchung":   ("16", "Buchung"),
    "faq":       ("17", "Häufige Fragen"),
    "foot":      ("18", "Fußzeile"),
    "bar":       ("19", "Mobile Buchungsleiste"),
    "consent":   ("20", "Cookie-Hinweis"),
    "danke":     ("21", "Danke-Seite"),
    "recht":     ("22", "Rechtstexte"),
}


INDEX = re.compile(r"^(.+)\[(\d+)\]$")


def wert_an(daten, pfad):
    """Wert zu 'a.b.c' aus site.json holen; None, wenn es ihn nicht gibt.

    Beherrscht auch den Index: 'ort.mosaic[3].src'. Den braucht das Mosaik im
    Abschnitt „Der Ort" — es hat acht feste Plaetze mit je eigenem CSS-Klassen-
    namen (m1..m8), ist also keine frei wachsende Liste, sondern acht einzelne
    Felder. Ohne diesen Zweig fielen genau diese 16 Felder aus dem Register.
    """
    knoten = daten
    for teil in pfad.split("."):
        m = INDEX.match(teil)
        if m:
            name, nummer = m.group(1), int(m.group(2))
            if not isinstance(knoten, dict) or name not in knoten:
                return None
            liste = knoten[name]
            if not isinstance(liste, list) or nummer >= len(liste):
                return None
            knoten = liste[nummer]
            continue
        if not isinstance(knoten, dict) or teil not in knoten:
            return None
        knoten = knoten[teil]
    return knoten


def php_literal(wert, einzug=8):
    """Einen Wert als PHP-Literal ausgeben. Listen werden verschachtelt."""
    if isinstance(wert, list):
        if not wert:
            return "[]"
        zeilen = []
        for eintrag in wert:
            zeilen.append(" " * (einzug + 4) + php_literal(eintrag, einzug + 4) + ",")
        return "[\n" + "\n".join(zeilen) + "\n" + " " * einzug + "]"
    if isinstance(wert, dict):
        if not wert:
            return "[]"
        zeilen = []
        for k, v in wert.items():
            zeilen.append(" " * (einzug + 4) + f"{php_str(k)} => "
                          + php_literal(v, einzug + 4) + ",")
        return "[\n" + "\n".join(zeilen) + "\n" + " " * einzug + "]"
    if isinstance(wert, bool):
        return "true" if wert else "false"
    if isinstance(wert, (int, float)):
        return str(wert)
    return php_str("" if wert is None else str(wert))


def php_str(s):
    """Einfache Anfuehrungszeichen: dort wird nur \\ und ' maskiert, also
    bleiben die HTML-Entitaeten aus site.json (&nbsp;) unangetastet."""
    return "'" + str(s).replace("\\", "\\\\").replace("'", "\\'") + "'"


def sql_str(s):
    return "'" + str(s).replace("\\", "\\\\").replace("'", "''") + "'"


def bauen():
    schema = json.loads(SCHEMA.read_text(encoding="utf-8"))
    daten = json.loads(CONTENT.read_text(encoding="utf-8"))

    nach_abschnitt = {}
    fehlend = []
    seed_zeilen = []

    for feld in schema:
        pfad = feld["path"]
        praefix = pfad.split(".")[0]
        if praefix not in ABSCHNITTE:
            fehlend.append(f"unbekannter Abschnitt '{praefix}' in '{pfad}'")
            continue

        wert = wert_an(daten, pfad)
        if wert is None:
            fehlend.append(f"'{pfad}' steht in schema.json, aber nicht in site.json")
            wert = [] if feld["type"] == "list" else ""

        nummer, name = ABSCHNITTE[praefix]
        eintrag = {
            "path": pfad,
            "group": f"{nummer} {name}",
            "label": feld["label"],
            "type": feld["type"],
            "hint": feld.get("hint", ""),
            "default": wert,
        }

        # Bei Wiederholungen wandert die Beschreibung der Unterfelder mit ins
        # Register. Ohne sie bliebe im Panel nur ein Kasten mit rohem JSON —
        # und das ist genau die Sorte Feld, an der eine Redaktion aufgibt.
        # Damit baut assets/panel.js je Eintrag eine Karte mit beschrifteten
        # Feldern, statt geschweifte Klammern zu zeigen.
        if feld["type"] == "list":
            eintrag["fields"] = feld.get("fields", [])
            if feld.get("itemLabel"):
                eintrag["itemLabel"] = feld["itemLabel"]

        nach_abschnitt.setdefault(praefix, []).append(eintrag)

        # seed.sql: Listen als JSON, alles andere als Text.
        if feld["type"] == "list":
            v = json.dumps(wert, ensure_ascii=False)
            typ = "json"
        else:
            v = "" if wert is None else str(wert)
            typ = feld["type"] if feld["type"] in (
                "text", "textarea", "html", "image", "number", "url") else "text"
        seed_zeilen.append(f"({sql_str(pfad)}, {sql_str(v)}, {sql_str(typ)})")

    dateien = {}
    for praefix, felder in nach_abschnitt.items():
        nummer, name = ABSCHNITTE[praefix]
        zeilen = [
            "<?php",
            "/* B³ Retreats — Content-Register: " + name + " (Abschnitt " + nummer + ").",
            "   ERZEUGT von tools/build-registry.py aus admin/schema.json und",
            "   content/site.json. Nicht von Hand aendern: der naechste Lauf",
            "   ueberschreibt die Datei. Texte gehoeren in content/site.json,",
            "   danach `python3 tools/build-registry.py`. */",
            "return [",
        ]
        for f in felder:
            zeilen.append("    " + php_str(f["path"]) + " => [")
            zeilen.append("        'group' => " + php_str(f["group"]) + ",")
            zeilen.append("        'label' => " + php_str(f["label"]) + ",")
            zeilen.append("        'type' => " + php_str(f["type"]) + ",")
            if f["hint"]:
                zeilen.append("        'hint' => " + php_str(f["hint"]) + ",")
            if f.get("itemLabel"):
                zeilen.append("        'itemLabel' => " + php_str(f["itemLabel"]) + ",")
            if f.get("fields"):
                zeilen.append("        'fields' => " + php_literal(f["fields"], 8) + ",")
            zeilen.append("        'default' => " + php_literal(f["default"]) + ",")
            zeilen.append("    ],")
        zeilen.append("];")
        dateien[REGISTRY / f"{praefix}.php"] = "\n".join(zeilen) + "\n"

    seed = "\n".join([
        "-- B³ Retreats — Inhalte fuer die content-Tabelle.",
        "-- ERZEUGT von tools/build-registry.py aus content/site.json.",
        "--",
        "-- Optional: ohne diese Datei rendert die Seite die Standardwerte aus",
        "-- partials/registry/. MIT ihr stehen die Texte auch in der Datenbank und",
        "-- sind damit im Panel sichtbar und bearbeitbar.",
        "--",
        "-- Import: admin/setup.php legt sie auf Wunsch mit an, oder in phpMyAdmin",
        "-- nach schema.sql einlesen. Wiederholbar: bestehende Zeilen werden",
        "-- ueberschrieben (ON DUPLICATE KEY UPDATE), nichts wird geloescht.",
        "SET NAMES utf8mb4;",
        "",
        "INSERT INTO `content` (`k`, `v`, `type`) VALUES",
        ",\n".join(seed_zeilen),
        "ON DUPLICATE KEY UPDATE `v` = VALUES(`v`), `type` = VALUES(`type`);",
        "",
    ])
    dateien[SEED] = seed

    return dateien, fehlend


def main():
    nur_pruefen = "--check" in sys.argv
    dateien, fehlend = bauen()

    for hinweis in fehlend:
        print(f"Achtung — {hinweis}")

    abweichung = []
    for pfad, inhalt in dateien.items():
        alt = pfad.read_text(encoding="utf-8") if pfad.exists() else None
        if alt != inhalt:
            abweichung.append(pfad)
        if not nur_pruefen:
            pfad.parent.mkdir(parents=True, exist_ok=True)
            pfad.write_text(inhalt, encoding="utf-8")

    if nur_pruefen:
        if abweichung:
            print("Nicht aktuell:")
            for p in abweichung:
                print("  " + str(p.relative_to(ROOT)))
            return 1
        print(f"{len(dateien)} Dateien sind aktuell.")
        return 0

    felder = sum(len(json.loads(SCHEMA.read_text(encoding='utf-8'))) for _ in [0])
    print(f"{len(dateien) - 1} Register-Dateien und db/seed.sql geschrieben "
          f"({felder} Felder).")
    return 1 if fehlend else 0


if __name__ == "__main__":
    raise SystemExit(main())
