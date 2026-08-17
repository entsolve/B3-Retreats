#!/usr/bin/env python3
"""Die Frauen aus der Retusche, alles andere aus dem freigegebenen Original.

Warum ueberhaupt: die Retusche aendert nicht nur Arme und Kleid, sie rechnet den
ganzen Boden neu — und macht daraus eine gestochene Textur aus leuchtenden
Haarlinien. Im Vordergrund sieht das nach Illustration aus, nicht nach Foto.
Prompts helfen dagegen nicht; das Modell rendert die Flaeche jedes Mal neu.

Deshalb wird nicht gemalt, sondern maskiert: die Maske ist die *geblurrte
Differenz* der beiden Bilder. Wo die Retusche wirklich etwas veraendert hat
(Arme, Kleid), ist die Differenz gross und flaechig — dort kommt das neue Bild.
Wo sie nur die Grastextur neu erfunden hat, ist die Differenz feinkoerniges
Rauschen; der Blur drueckt es unter die Schwelle, und dort bleibt das Original.
Beine, Fuesse und Schatten sind in beiden gleich und kommen darum aus dem
Original — mit dem echten Gras darum herum.

Die Differenz allein genuegt nicht: der Schattenstreifen im Feld und die Disteln
am linken Rand aendern sich stark genug, um die Schwelle zu reissen — und das
sind genau die Stellen, die aus dem Original kommen sollen. Darum wird die Maske
zusaetzlich raeumlich eingesperrt (--box), auf das Rechteck, in dem die drei
stehen. Ausserhalb davon bleibt immer das Original.

  python3 montage.py <original.png> <retusche.png> <ziel.png> \
      --box x0,x1,y0,y1 [--maske m.png]
"""
import sys

import numpy as np
from PIL import Image, ImageFilter

# Alles als Anteil der Bildbreite.
BLUR = 0.006      # verbindet die Differenz zu einer Flaeche, mittelt Gras-Rauschen weg
SCHWELLE = 14.0   # darueber gilt: hier hat die Retusche wirklich etwas geaendert
WEITEN = 0.007    # Maske aufweiten, damit ihr Rand im Hintergrund liegt
FEDER = 0.004     # schmaler Uebergang


def maske(a, b, w):
    """Binaer plus Aufweitung statt weicher Rampe.

    Eine weiche Rampe ueber die Arme hinweg laesst den Hintergrund durch die
    halbdurchsichtige Kante scheinen — die Arme bekamen davon einen Doppelrand.
    Deshalb wird hart geschwellt und die Maske anschliessend aufgeweitet: ihr
    Rand liegt dann im Feld ringsum, wo beide Bilder ohnehin gleich sind, und
    der Uebergang ist unsichtbar."""
    d = np.abs(a.astype(float) - b.astype(float)).mean(2)
    m = Image.fromarray(np.clip(d, 0, 255).astype("uint8"))
    m = m.filter(ImageFilter.GaussianBlur(radius=max(2.0, BLUR * w)))

    hart = (np.asarray(m, float) > SCHWELLE).astype("uint8") * 255
    m = Image.fromarray(hart)
    k = int(max(3, WEITEN * w)) | 1                      # MaxFilter braucht ungerade
    m = m.filter(ImageFilter.MaxFilter(k))               # aufweiten
    m = m.filter(ImageFilter.GaussianBlur(radius=max(1.5, FEDER * w)))
    return np.asarray(m, float) / 255


def kasten(h, w, x0, x1, y0, y1, feder=0.025):
    """Weich auslaufendes Rechteck als raeumliche Sperre fuer die Maske."""
    def rampe(n, a, b):
        t = np.arange(n) / n
        f = max(feder, 1e-6)
        return np.clip((t - a) / f, 0, 1) * np.clip((b - t) / f, 0, 1)
    return np.outer(rampe(h, y0, y1), rampe(w, x0, x1))


def main():
    p_orig, p_neu, p_ziel = sys.argv[1], sys.argv[2], sys.argv[3]
    orig = Image.open(p_orig).convert("RGB")
    neu = Image.open(p_neu).convert("RGB")
    if orig.size != neu.size:
        orig = orig.resize(neu.size, Image.LANCZOS)
    A, B = np.asarray(orig), np.asarray(neu)

    r = maske(A, B, neu.width)
    if "--box" in sys.argv:
        x0, x1, y0, y1 = [float(v) for v in
                          sys.argv[sys.argv.index("--box") + 1].split(",")]
        r *= kasten(neu.height, neu.width, x0, x1, y0, y1)
    out = A * (1 - r[..., None]) + B * r[..., None]
    Image.fromarray(out.round().astype("uint8")).save(p_ziel)

    anteil = float(r.mean())
    print(f"{p_ziel}  {neu.size[0]}x{neu.size[1]}  "
          f"Retusche traegt {anteil*100:.1f} % der Flaeche")
    if "--maske" in sys.argv:
        Image.fromarray((r * 255).astype("uint8")).save(
            sys.argv[sys.argv.index("--maske") + 1])


if __name__ == "__main__":
    main()
