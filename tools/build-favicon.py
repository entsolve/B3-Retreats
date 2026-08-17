"""Erzeugt das Seitensymbol (Favicon) aus dem Markenzeichen der Seite.

Die Quelle ist NICHT eine neue Zeichnung, sondern das Markenzeichen, das im
Kopf der Seite steht (<g id="b3mark"> in tools/templates/index.html): ein
offener Ring mit einem Punkt in der Luecke, darin „B3" in der Cormorant
Garamond, darunter „RETREATS" in der Manrope.

DREI DINGE MUSSTEN SICH AENDERN, DAMIT ES ALS SYMBOL TAUGT:

  1. „RETREATS" faellt weg. Bei 16 Pixeln sind das acht Buchstaben auf elf
     Pixel Breite — ein grauer Strich, sonst nichts.
  2. Der Ring wird dicker. Im Original ist er 1,1 von 120 Einheiten stark;
     auf 16 Pixel gerechnet waeren das 0,15 Pixel, also nichts.
  3. Die Schrift wird zu Umrissen. Ein <text> im Symbol wuerde im Browser
     mit irgendeiner Schrift gezeichnet — die Cormorant Garamond steht dort
     nicht zur Verfuegung. fontTools holt darum die Umrisse aus der
     woff2-Datei und legt sie als Pfad ab; damit sieht das Symbol ueberall
     gleich aus, ohne Schrift zu laden.

Die Geometrie des Rings stammt aus dem Original: Mittelpunkt (60|60),
Radius 54, Luecke zwischen 285 und 305 Grad, Punkt bei 295 Grad. Nachgerechnet
aus den Endpunkten der beiden Bogen — sie liegen alle auf glatten Fuenf-Grad-
Schritten, das war also die Absicht des Entwurfs.

Ohne Rasterer auf diesem Rechner (kein cairo, kein Inkscape) werden die
PNG-Fassungen direkt mit Pillow gezeichnet, vierfach ueberabgetastet und
danach verkleinert — das ergibt saubere Kanten.

Aufruf:
    python3 tools/build-favicon.py
"""
import io
import math
import pathlib

from fontTools.pens.svgPathPen import SVGPathPen
from fontTools.pens.transformPen import TransformPen
from fontTools.misc.transform import Transform
from fontTools.ttLib import TTFont
from PIL import Image, ImageDraw, ImageFont

ROOT = pathlib.Path(__file__).resolve().parent.parent
FONT = ROOT / "assets" / "fonts" / "cormorant-garamond-300-latin.woff2"
OUT = ROOT / "assets" / "img"

# Marke (assets/css/tokens.css)
IVORY = "#F7F4EF"
OLIVE = "#454B40"

# --- Geometrie, aus dem Markenzeichen uebernommen -----------------------------
BOX = 120           # viewBox des Originals
MITTE = 60.0
RADIUS = 44.0       # enger als das Original (54), damit Luft zum Rand bleibt
STRICH = 3.6        # statt 1.1 — siehe Kopf der Datei
LUECKE = (285.0, 305.0)     # Grad, Uhrzeigersinn, 0 = 3 Uhr
PUNKT_WINKEL = 295.0
PUNKT_R = 4.6
ECKE = 26           # Radius der abgerundeten Grundflaeche


def glyph_pfad(zeichen, groesse, x, y):
    """Umriss eines Zeichens als SVG-Pfad, gesetzt auf (x|y) als Grundlinie."""
    font = TTFont(FONT)
    upem = font["head"].unitsPerEm
    name = font.getBestCmap()[ord(zeichen)]
    glyphset = font.getGlyphSet()

    massstab = groesse / upem
    # y in der Schrift zeigt nach oben, im SVG nach unten -> spiegeln.
    stift = SVGPathPen(glyphset)
    glyphset[name].draw(TransformPen(stift, Transform(massstab, 0, 0, -massstab, x, y)))
    breite = glyphset[name].width * massstab
    return stift.getCommands(), breite


def bogen_pfad(cx, cy, r, von, bis):
    """SVG-Pfad eines Kreisbogens von Grad `von` bis Grad `bis`, im Uhrzeigersinn."""
    spanne = (bis - von) % 360
    x1 = cx + r * math.cos(math.radians(von))
    y1 = cy + r * math.sin(math.radians(von))
    x2 = cx + r * math.cos(math.radians(bis))
    y2 = cy + r * math.sin(math.radians(bis))
    gross = 1 if spanne > 180 else 0
    return f"M{x1:.2f} {y1:.2f} A{r} {r} 0 {gross} 1 {x2:.2f} {y2:.2f}"


def schrift_setzen():
    """„B3" mittig setzen; die 3 hochgestellt wie im Markenzeichen."""
    gross, klein = 62.0, 32.0
    # Erster Durchgang nur fuer die Breiten — daraus ergibt sich der linke Rand.
    _, b_breite = glyph_pfad("B", gross, 0, 0)
    _, d_breite = glyph_pfad("3", klein, 0, 0)

    links = MITTE - (b_breite + d_breite) / 2
    grundlinie = MITTE + 21.0
    hoch = 15.0                      # Hochstellung der 3

    b_pfad, _ = glyph_pfad("B", gross, links, grundlinie)
    d_pfad, _ = glyph_pfad("3", klein, links + b_breite, grundlinie - hoch)
    return b_pfad + " " + d_pfad


def svg_bauen():
    ring = bogen_pfad(MITTE, MITTE, RADIUS, LUECKE[1], LUECKE[0])
    px = MITTE + RADIUS * math.cos(math.radians(PUNKT_WINKEL))
    py = MITTE + RADIUS * math.sin(math.radians(PUNKT_WINKEL))

    return f"""<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {BOX} {BOX}">
  <title>B³ Retreats</title>
  <rect width="{BOX}" height="{BOX}" rx="{ECKE}" ry="{ECKE}" fill="{IVORY}"/>
  <path d="{ring}" fill="none" stroke="{OLIVE}" stroke-width="{STRICH}" stroke-linecap="round"/>
  <circle cx="{px:.2f}" cy="{py:.2f}" r="{PUNKT_R}" fill="{OLIVE}"/>
  <path d="{schrift_setzen()}" fill="{OLIVE}"/>
</svg>
"""


def ttf_im_speicher():
    """Pillow liest kein woff2 — die Schrift einmal als TTF in den Speicher legen."""
    font = TTFont(FONT)
    puffer = io.BytesIO()
    font.save(puffer)
    puffer.seek(0)
    return puffer


def png_bauen(kante):
    """Dieselbe Zeichnung als PNG, vierfach ueberabgetastet."""
    s = 4
    gross = kante * s
    faktor = gross / BOX

    bild = Image.new("RGBA", (gross, gross), (0, 0, 0, 0))
    zeichnung = ImageDraw.Draw(bild)

    # Grundflaeche mit runden Ecken
    zeichnung.rounded_rectangle([0, 0, gross - 1, gross - 1],
                                radius=int(ECKE * faktor), fill=IVORY)

    # Ring mit Luecke
    r = RADIUS * faktor
    m = MITTE * faktor
    zeichnung.arc([m - r, m - r, m + r, m + r],
                  start=LUECKE[1], end=LUECKE[0], fill=OLIVE,
                  width=max(1, round(STRICH * faktor)))

    # Punkt in der Luecke
    px = m + r * math.cos(math.radians(PUNKT_WINKEL))
    py = m + r * math.sin(math.radians(PUNKT_WINKEL))
    pr = PUNKT_R * faktor
    zeichnung.ellipse([px - pr, py - pr, px + pr, py + pr], fill=OLIVE)

    # B und hochgestellte 3
    f_gross = ImageFont.truetype(ttf_im_speicher(), int(62 * faktor))
    f_klein = ImageFont.truetype(ttf_im_speicher(), int(32 * faktor))
    b_breite = zeichnung.textlength("B", font=f_gross)
    d_breite = zeichnung.textlength("3", font=f_klein)
    links = m - (b_breite + d_breite) / 2
    grundlinie = m + 21.0 * faktor

    zeichnung.text((links, grundlinie), "B", font=f_gross, fill=OLIVE, anchor="ls")
    zeichnung.text((links + b_breite, grundlinie - 15.0 * faktor), "3",
                   font=f_klein, fill=OLIVE, anchor="ls")

    return bild.resize((kante, kante), Image.LANCZOS)


def main():
    OUT.mkdir(parents=True, exist_ok=True)

    svg = OUT / "favicon.svg"
    svg.write_text(svg_bauen(), encoding="utf-8")
    print(f"{svg.relative_to(ROOT)} ({svg.stat().st_size} B)")

    # 180 fuer iOS („Zum Home-Bildschirm"), 512 fuer Android/Manifest,
    # 32 als klassische Groesse im Tab.
    for kante in (32, 180, 512):
        bild = png_bauen(kante)
        ziel = OUT / f"favicon-{kante}.png"
        bild.save(ziel, "PNG", optimize=True)
        print(f"{ziel.relative_to(ROOT)} ({ziel.stat().st_size} B)")

    # .ico fuer aeltere Browser und den Windows-Verlauf: 16 und 32 in einer Datei.
    ico = OUT / "favicon.ico"
    png_bauen(32).save(ico, "ICO", sizes=[(16, 16), (32, 32)])
    print(f"{ico.relative_to(ROOT)} ({ico.stat().st_size} B)")


if __name__ == "__main__":
    main()
