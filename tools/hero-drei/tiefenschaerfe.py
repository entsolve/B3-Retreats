#!/usr/bin/env python3
"""Den Vordergrund unscharf legen — Schaerfentiefe, wie sie ein echtes Objektiv hat.

WARUM NICHT PER PROMPT: der allererste Prompt (`prompt.txt`) wollte den Vordergrund
genau richtig — „a couple of blades of grass crossing the very front of the frame,
**soft and out of focus**". Spaeter hat `fix11.txt` das Gegenteil verlangt: jeder
Halm scharf, „fine seed hairs and awns catch the low sun as separate bright
lines". Genau das ist die Radierung, die die Kundin beanstandet hat — gleich
grosse Halme in eine Richtung, ueberall helle Faeden, keine Erde, keine Tiefe.

Zurueckdrehen laesst sich das nicht per Prompt: das Modell rendert die Flaeche
bei jedem Durchgang neu und schaerft sie wieder nach (gemessen: Gradientenenergie
steigt in JEDEM Durchgang, egal wie ausdruecklich der Prompt es verbietet).

Optisch ist es ohnehin kein Malproblem, sondern ein Objektivproblem. Bei 85 mm
und f/2.8 auf die Frauen scharfgestellt liegt Gras einen Meter vor der Kamera
deutlich ausserhalb der Schaerfentiefe. Diese Unschaerfe wird hier gerechnet —
deterministisch, ohne Modell, ohne neue Artefakte.

Zwei Zonen, beide mit weichem Verlauf, damit keine Kante entsteht:
  · unten  ab `--start` bis zur Unterkante, nach unten zunehmend;
  · links  der Distelstreifen am Rand, nach links zunehmend.
Die Frauen stehen mit den Fuessen bei etwa 0,73 — `--start` liegt darunter und
ruehrt sie nicht an.

  python3 tiefenschaerfe.py <bild.png> <ziel.png> [--start 0.76] [--radius 11]
      [--links 0.17] [--links-ab 0.50]
"""
import sys

import numpy as np
from PIL import Image, ImageFilter


def arg(name, standard):
    return float(sys.argv[sys.argv.index(name) + 1]) if name in sys.argv else standard


def main():
    p_in, p_out = sys.argv[1], sys.argv[2]
    start = arg("--start", 0.76)
    radius = arg("--radius", 11.0)
    links = arg("--links", 0.17)
    links_ab = arg("--links-ab", 0.50)

    im = Image.open(p_in).convert("RGB")
    w, h = im.size
    yy = np.linspace(0, 1, h)[:, None]
    xx = np.linspace(0, 1, w)[None, :]

    # Unten: 0 bei `start`, 1 an der Unterkante. Der Exponent steuert, wie schnell
    # die Unschaerfe einsetzt. Hoch drei war zu spaet: das breite Band zwischen
    # 0,76 und 0,88 blieb praktisch scharf, und genau dort steht das gezeichnete
    # Gras. 1,6 legt die halbe Unschaerfe in die Mitte des Verlaufs.
    unten = np.clip((yy - start) / max(1e-6, 1 - start), 0, 1) ** arg("--kurve", 1.6)
    # Links: nur unterhalb von `links_ab`, damit Feld und Baumkante scharf bleiben.
    seit = (np.clip((links - xx) / max(1e-6, links), 0, 1) ** 2
            * np.clip((yy - links_ab) / 0.25, 0, 1))
    g = np.clip(np.maximum(unten, seit), 0, 1)[..., None]

    a = np.asarray(im, dtype=float)
    # Zwei Stufen statt einer: die Mitte des Verlaufs bekommt echte halbe
    # Unschaerfe und nicht eine halbdurchsichtige scharfe Kopie ueber der vollen.
    b1 = np.asarray(im.filter(ImageFilter.GaussianBlur(radius * 0.4)), dtype=float)
    b2 = np.asarray(im.filter(ImageFilter.GaussianBlur(radius)), dtype=float)
    t = np.clip(g * 2, 0, 1)
    weich = a * (1 - t) + b1 * t
    t2 = np.clip(g * 2 - 1, 0, 1)
    out = weich * (1 - t2) + b2 * t2

    Image.fromarray(np.clip(out, 0, 255).round().astype("uint8")).save(p_out)
    print(f"{p_out}  {w}x{h}  unscharf ab y={start:.2f}, Radius bis {radius:.0f} px, "
          f"Flaechenanteil mit Unschaerfe {float((g > 0.05).mean()) * 100:.1f} %")


if __name__ == "__main__":
    main()
