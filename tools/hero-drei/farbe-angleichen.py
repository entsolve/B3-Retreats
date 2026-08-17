#!/usr/bin/env python3
"""Den Magenta-Stich aus einem erzeugten Bild herausrechnen.

WARUM DAS NOETIG IST — und warum eine frühere Fassung dieses Skripts am Problem
vorbeigemessen hat: Farbstich hat zwei Achsen, nicht eine.

    R-B            warm  <-> kalt
    G-(R+B)/2      gruen <-> magenta

Rosa liegt auf der ZWEITEN Achse. Die erste Fassung hat nur `R-B` angeglichen,
also die Waerme — und die war beim Hero nie das Problem. Gemessen:

    echte Fotos der Kundin      G-(R+B)/2 = +0,012 … +0,023
    erzeugte Hero-Quellen       G-(R+B)/2 = -0,059 … -0,072

Fast 0,08 Unterschied: dem Bild fehlt Gruen, und genau das sieht man als rosa
Folie ueber Gras, Haut und Himmel. Der Stich sitzt in der Quelle, nicht im
Grading — `out/_verworfen/vor-umarmung-tall.png` hat ihn mit -0,070 schon.

WAS GERECHNET WIRD — drei Kanalfaktoren aus drei Bedingungen:

    1. G-(R+B)/2 = ZIEL      der Magenta-Stich verschwindet
    2. R-B bleibt gleich     die Waerme des Kaders wird NICHT angetastet
    3. R+G+B bleibt gleich   die Helligkeit bleibt, das Bild wird nicht dunkler

Das ist ein von-Kries-Abgleich mit zwei erhaltenen Groessen; er laesst sich in
einer Zeile aufloesen (siehe unten). Ergebnis beim Hochformat: Gruen +13 %,
Rot -5 %, Blau -6 %.

  python3 farbe-angleichen.py <bild.png> <ziel.png> [--ziel 0.015] [--staerke 1.0]
"""
import sys

import numpy as np
from PIL import Image

# Mittel der echten Aussenaufnahmen der Kundin (ort-sonne, bms-fries,
# fuerwen-blick, fries-hof, ort-anwesen, ort-terrasse): +0,018.
ZIEL = 0.015


def achsen(a):
    r, g, b = (a[..., i].mean() / 255 for i in range(3))
    return r, g, b


def main():
    p_in, p_out = sys.argv[1], sys.argv[2]
    ziel = float(sys.argv[sys.argv.index("--ziel") + 1]) if "--ziel" in sys.argv else ZIEL
    st = float(sys.argv[sys.argv.index("--staerke") + 1]) if "--staerke" in sys.argv else 1.0

    im = Image.open(p_in).convert("RGB")
    a = np.asarray(im, dtype=float)
    r, g, b = achsen(a)
    s, d = r + g + b, r - b
    vorher = g - (r + b) / 2

    # z = B', x = R', y = G' — Auflösung der drei Bedingungen
    z = (s - 1.5 * d - ziel) / 3
    x = z + d
    y = s - d - 2 * z

    k = np.array([x / max(r, 1e-6), y / max(g, 1e-6), z / max(b, 1e-6)])
    k = 1 + (k - 1) * st                      # Staerke < 1 = Stich nur teilweise raus

    out = np.clip(a * k, 0, 255)
    Image.fromarray(out.round().astype("uint8")).save(p_out)

    r2, g2, b2 = achsen(out)
    print(f"{p_out}  G-(R+B)/2 {vorher:+.3f} -> {g2 - (r2 + b2) / 2:+.3f} (Ziel {ziel:+.3f})   "
          f"R x{k[0]:.3f}  G x{k[1]:.3f}  B x{k[2]:.3f}   "
          f"R-B {d:+.3f} -> {r2 - b2:+.3f}   Helligkeit {a.mean():.1f} -> {out.mean():.1f}")


if __name__ == "__main__":
    main()
