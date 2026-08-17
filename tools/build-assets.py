"""B3 Retreats — Asset-Pipeline.

Backt den Farblook in die Dateien (EXIF -> Lichter -> WB -> Himmel -> Grün ->
Sättigung -> Matt-Lift -> S-Kurve -> Korn -> Vignette -> Schärfe).
CSS-Filter bleiben absichtlich außen vor: eine globale saturate() würde das Gold
der Felder und die Hauttöne mit dem Kobaltblau zusammen töten.
"""
import os
import numpy as np
from PIL import Image, ImageOps, ImageEnhance, ImageFilter

SRC = "/Users/llashutko/Documents/entsolve/GIT/B3-Retreats/materials- in/B3 Retreats"
# Nicht aus dem Kundenordner: das erzeugte Hero-Motiv liegt getrennt, damit
# Material der Kundin und Erzeugtes nicht durcheinandergeraten.
HERO_DIR = "/Users/llashutko/Documents/entsolve/GIT/B3-Retreats/tools/hero-drei/out/"
# Die Porträts der drei: ihre echten Fotos, aber alle in dieselbe Umgebung und
# dasselbe Licht gesetzt — Herkunft und Prompts in tools/portraets/.
PORT_DIR = "/Users/llashutko/Documents/entsolve/GIT/B3-Retreats/tools/portraets/out/"
OUT = "/Users/llashutko/Documents/entsolve/GIT/B3-Retreats/assets/img"
os.makedirs(OUT, exist_ok=True)

Image.MAX_IMAGE_PIXELS = None
rng = np.random.default_rng(20261008)  # fester Seed: reproduzierbares Korn

# --- Profile -----------------------------------------------------------------
# hi     Rolloff-Faktor über 200 (kleiner = mehr Lichter zurückgeholt)
# wb     Kanalmultiplikatoren R,G,B
# sky    Entsättigung des Himmels (1.0 = aus)
# green  Entsättigung/Drehung der Vegetation (1.0 = aus)
# red    Dämpfung von Ziegelrot/Blüten — auf Haut IMMER 1.0, sonst gibt es Flecken
# sat    globale Sättigung nach der selektiven Arbeit
# vig    Stärke der warmen Vignette
# sharp  UnsharpMask ja/nein
P = {
    "outdoor":  dict(hi=0.72, wb=(1.055, 1.005, 0.925), sky=0.42, green=0.72, red=0.68, sat=0.90, vig=0.16, sharp=False),
    "interior": dict(hi=0.62, wb=(1.045, 1.005, 0.940), sky=0.60, green=0.85, red=1.00, sat=0.90, vig=0.18, sharp=True),
    "detail":   dict(hi=0.70, wb=(1.040, 1.005, 0.945), sky=1.00, green=0.85, red=1.00, sat=0.95, vig=0.12, sharp=True),
    "portrait": dict(hi=0.86, wb=(1.025, 1.005, 0.972), sky=0.88, green=0.92, red=1.00, sat=0.95, vig=0.08, sharp=False),
    "portrait_beton": dict(hi=0.84, wb=(1.045, 1.010, 0.940), sky=0.88, green=0.92, red=1.00, sat=0.95, vig=0.08, sharp=False),
    "sunset":   dict(hi=0.85, wb=(1.020, 1.000, 0.975), sky=1.00, green=0.88, red=1.00, sat=0.95, vig=0.14, sharp=False),
    "food":     dict(hi=0.75, wb=(1.030, 1.005, 0.960), sky=1.00, green=0.90, red=1.00, sat=1.00, vig=0.12, sharp=True),
    # Landschaft MIT Menschen darin. Wie "outdoor", aber red=1.00: die 0.68 von
    # "outdoor" sind für Ziegel und Blüten gedacht und schlagen auf Haut als
    # Flecken durch. Himmel und Grün werden dafür etwas weniger hart gezogen,
    # sonst kippt der Hautton mit.
    "outdoor_haut": dict(hi=0.78, wb=(1.045, 1.005, 0.938), sky=0.52, green=0.82, red=1.00, sat=0.92, vig=0.14, sharp=False),
}


def highlights(a, k):
    """Weiß von 255 auf ~234 ziehen, damit Fliesen und Putz wieder Textur haben."""
    out = a.copy()
    m = a > 200
    out[m] = 200 + (a[m] - 200) * k
    return out


def white_balance(a, wb):
    for i, f in enumerate(wb):
        a[..., i] *= f
    return np.clip(a, 0, 255)


def rgb_to_hsv(a):
    r, g, b = a[..., 0] / 255, a[..., 1] / 255, a[..., 2] / 255
    mx, mn = np.maximum(np.maximum(r, g), b), np.minimum(np.minimum(r, g), b)
    d = mx - mn
    h = np.zeros_like(mx)
    nz = d > 1e-6
    idx = nz & (mx == r); h[idx] = ((g - b)[idx] / d[idx]) % 6
    idx = nz & (mx == g); h[idx] = ((b - r)[idx] / d[idx]) + 2
    idx = nz & (mx == b); h[idx] = ((r - g)[idx] / d[idx]) + 4
    h *= 60
    s = np.where(mx > 1e-6, d / np.maximum(mx, 1e-6), 0)
    return h, s, mx


def hsv_to_rgb(h, s, v):
    h = h % 360
    c = v * s
    x = c * (1 - np.abs((h / 60) % 2 - 1))
    m = v - c
    z = np.zeros_like(h)
    i = (h / 60).astype(int) % 6
    r = np.select([i == 0, i == 1, i == 2, i == 3, i == 4, i == 5], [c, x, z, z, x, c])
    g = np.select([i == 0, i == 1, i == 2, i == 3, i == 4, i == 5], [x, c, c, x, z, z])
    b = np.select([i == 0, i == 1, i == 2, i == 3, i == 4, i == 5], [z, z, x, c, c, x])
    return np.stack([(r + m) * 255, (g + m) * 255, (b + m) * 255], -1)


def band(x, lo, hi, feather):
    """Weiche Zugehoerigkeit zu [lo, hi].

    Harte boolesche Masken hinterlassen auf Haut scharfkantige Flecken —
    genau deshalb sind die Flanken hier ausgeblendet.
    """
    return np.clip(np.minimum((x - lo) / feather + 1, (hi - x) / feather + 1), 0, 1)


def sat_gate(s, lo=0.08, feather=0.10):
    """Fast neutrale Pixel (Haut im Schatten, Putz) aus der Maske heraushalten."""
    return np.clip((s - lo) / feather, 0, 1)


def selective(a, sky, green, red):
    """Das eigentliche Heilmittel: Kobalthimmel entsaettigen, Gruen nach Olive drehen."""
    if sky >= 1.0 and green >= 1.0 and red >= 1.0:
        return a
    h, s, v = rgb_to_hsv(a)

    if sky < 1.0:
        m = band(h, 190, 250, 22) * sat_gate(s)
        s = s * (1 - m) + s * sky * m                  # S x 0.42
        v = v * (1 - m) + np.minimum(v * 1.08, 1) * m  # V x 1.08
        h = h * (1 - m) + (h * 0.55 + 206 * 0.45) * m  # Richtung 205-208, rauchig

    if green < 1.0:
        m = band(h, 72, 152, 18) * sat_gate(s)
        s = s * (1 - m) + s * green * m
        v = v * (1 - m) + v * 0.95 * m
        h = h * (1 - m) + (h - 8) * m                  # ins Gelbliche

    if red < 1.0:
        # Rot liegt um 0 herum — um 180 drehen, dann ist das Band zusammenhaengend
        hr = (h + 180) % 360
        m = band(hr, 160, 200, 16) * sat_gate(s, 0.30, 0.16)
        s = s * (1 - m) + s * red * m                  # Ziegel und Mandevilla beruhigen

    return np.clip(hsv_to_rgb(h, s, v), 0, 255)


def matte(a):
    """Schwarzpunkt auf warmes #0E0C08, Weißpunkt auf Elfenbein #F7F3EC."""
    black, white = np.array([14, 12, 8.0]), np.array([247, 243, 236.0])
    return black + a * (white - black) / 255


def scurve(a):
    xs = np.array([0, 64, 128, 192, 255.0])
    ys = np.array([0, 58, 131, 199, 255.0])
    lut = np.interp(np.arange(256), xs, ys)
    return lut[np.clip(a, 0, 255).astype(np.uint8)]


def grain(a, sigma=3.5):
    h, w = a.shape[:2]
    n = rng.normal(0, sigma, (h, w, 1))
    weight = 1 - 0.45 * (a.mean(axis=2, keepdims=True) / 255)  # in Lichtern schwächer
    return a + n * weight


def vignette(a, strength):
    h, w = a.shape[:2]
    yy, xx = np.mgrid[0:h, 0:w]
    d = np.sqrt(((xx - w / 2) / (w / 2)) ** 2 + ((yy - h * 0.45) / (h / 2)) ** 2)
    f = np.clip((d - 0.72) / 0.45, 0, 1) ** 1.5
    mask = (1 - strength * f)[..., None]
    warm = np.array([42, 35, 24.0])
    return a * mask + warm * (1 - mask) * 0.35


def fit_aspect(box, aspect, bias=(0.5, 0.5)):
    l, t, r, b = box
    w, h = r - l, b - t
    if w / h > aspect:
        nw = h * aspect
        l += (w - nw) * bias[0]
        r = l + nw
    else:
        nh = w / aspect
        t += (h - nh) * bias[1]
        b = t + nh
    return int(l), int(t), int(r), int(b)


def build(name, src, profile, aspect, width, crop=(0, 0, 1, 1), bias=(0.5, 0.5), quality=84, rot=0):
    p = P[profile]
    # Absoluter Pfad: für Bilder, die nicht aus dem Kundenordner stammen (Hero).
    path_in = src if os.path.isabs(src) else os.path.join(SRC, src)
    im = ImageOps.exif_transpose(Image.open(path_in)).convert("RGB")
    if rot:
        # Die Schlafzimmer-Aufnahmen sind aus der Hüfte geschossen und kippen ~14°.
        im = im.rotate(rot, resample=Image.BICUBIC, expand=False)
    W, H = im.size
    box = (crop[0] * W, crop[1] * H, crop[2] * W, crop[3] * H)
    im = im.crop(fit_aspect(box, aspect, bias))

    target = (width, max(1, round(width / aspect)))
    if im.width > target[0] * 2:  # zweistufig verkleinern = sauberere Kanten
        im = im.resize((target[0] * 2, target[1] * 2), Image.LANCZOS)
    im = im.resize(target, Image.LANCZOS)

    a = np.asarray(im, dtype=np.float64)
    a = highlights(a, p["hi"])
    a = white_balance(a, p["wb"])
    a = selective(a, p["sky"], p["green"], p["red"])
    im = Image.fromarray(np.clip(a, 0, 255).astype(np.uint8))
    im = ImageEnhance.Color(im).enhance(p["sat"])

    a = np.asarray(im, dtype=np.float64)
    a = matte(a)
    a = scurve(a)
    a = grain(a)
    a = vignette(a, p["vig"])
    im = Image.fromarray(np.clip(a, 10, 248).astype(np.uint8))

    if p["sharp"]:
        im = im.filter(ImageFilter.UnsharpMask(radius=0.8, percent=40, threshold=3))

    path = os.path.join(OUT, name + ".webp")
    im.save(path, "WEBP", quality=quality, method=6)

    arr = np.asarray(im, dtype=np.float64)
    h, s, _ = rgb_to_hsv(arr)
    skym = (h >= 185) & (h <= 255) & (s > 0.10)
    sky_s = s[skym].mean() * 100 if skym.sum() > arr.size * 0.005 else float("nan")
    print(f"{os.path.getsize(path)/1024:7.1f} KB  {name+'.webp':28s} {im.size[0]}x{im.size[1]}  "
          f"min={arr.min():.0f} max={arr.max():.0f} himmel_S={sky_s:5.1f}%")


# --- Asset-Liste (Rollen aus dem Build-Brief) --------------------------------
JOBS = [
    # Hero: die drei Gastgeberinnen von hinten. Erzeugt, weil es kein echtes
    # Gruppenfoto gibt — Herkunft, Prompt und Prüfschritte in tools/hero-drei/.
    # Profil "outdoor_haut", nicht "outdoor": sonst fleckt die Haut.
    # Quer und hoch sind ZWEI Bilder, kein Ausschnitt voneinander: bei 3/4 aus
    # dem Querformat fällt je eine der äußeren Frauen aus dem Rand.
    ("hero-tall",       HERO_DIR + "final-hero-tall.png", "outdoor_haut",  3/4,  1500, (0, 0, 1, 1), (0.50, 0.50)),
    ("hero-wide",       HERO_DIR + "final-hero-wide.png", "outdoor_haut", 16/9,  1920, (0, 0, 1, 1), (0.50, 0.50)),
    ("og-image",        HERO_DIR + "final-hero-wide.png", "outdoor_haut", 1.91,  1200, (0, 0, 1, 1), (0.50, 0.20)),

    ("ablauf",          "26-08-12 09-54-42 7587.jpg", "outdoor",  3/4,  1000, (0.00, 0.35, 1.00, 1.00), (0.55, 0.60)),

    ("exp-yoga",        "26-08-12 09-58-02 7597.jpg", "detail",   4/5,  1000, (0.00, 0.08, 1.00, 1.00), (0.50, 0.55)),
    ("exp-kerzen",      "26-08-12 09-59-46 7605.jpg", "detail",   1.0,  1000, (0.05, 0.05, 0.95, 0.98), (0.50, 0.55)),
    ("exp-makro",       "26-08-12 10-00-10 7607.jpg", "detail",   4/5,  1000, (0.00, 0.10, 1.00, 1.00), (0.50, 0.62)),

    ("fries-hof",       "26-08-12 09-51-43 7580.jpg", "outdoor",  2.4,  1920, (0.00, 0.26, 1.00, 0.81), (0.50, 0.50)),

    ("ort-terrasse",    "26-08-12 09-54-48 7588.jpg", "outdoor", 16/9,  1600, (0.00, 0.22, 1.00, 1.00), (0.50, 0.55)),
    ("ort-anwesen",     "26-08-12 09-53-29 7584.jpg", "outdoor", 16/9,  1100, (0.06, 0.06, 1.00, 0.81), (0.55, 0.50)),
    ("ort-remise",      "26-08-12 10-03-48 7611.jpg", "outdoor", 16/9,  1100, (0.00, 0.05, 1.00, 0.80), (0.50, 0.50)),
    ("ort-saeule",      "26-08-12 09-55-05 7589.jpg", "outdoor",  4/5,  1000, (0.00, 0.20, 1.00, 1.00), (0.50, 0.55)),
    ("ort-sonne",       "26-08-12 09-56-38 7595.jpg", "outdoor",  4/5,  1000, (0.04, 0.14, 1.00, 0.99), (0.55, 0.60)),

    ("essen",           "3ad965f3-d4b3-484c-8ea3-b8d22fbe8d67.jpeg", "food", 1.0, 1000, (0.00, 0.12, 1.00, 1.00), (0.50, 0.45)),
    ("essen-grill",     "26-08-12 09-55-31 7592.jpg", "outdoor",  4/5,   900, (0.02, 0.42, 0.64, 1.00), (0.50, 0.50)),

    ("haus-fries",      "26-08-12 10-14-26 7627.jpg", "interior", 3/2,  1600, (0.04, 0.12, 0.99, 0.97), (0.50, 0.55)),
    ("haus-schlafen",   "26-08-12 10-11-25 7622.jpg", "interior", 4/5,   900, (0.15, 0.15, 0.90, 0.93), (0.55, 0.45), 84, 24),
    ("haus-wohnen",     "26-08-12 10-14-26 7627.jpg", "interior", 4/5,   900, (0.46, 0.10, 1.00, 1.00), (0.50, 0.50)),

    # Die drei Porträts stammen aus ihren eigenen Fotos, stehen aber jetzt alle
    # am selben Feldrand im selben Abendlicht — vorher waren es drei Welten
    # (Innenraum, Vorhang, Betontreppe) und drei Profile. Deshalb hier auch nur
    # noch ein Profil: "portrait_beton" wird nicht mehr gebraucht, der Beton ist
    # weg. Ausschnitt (0,0,1,1): die Vorlagen sind bereits 4:5 gebaut.
    ("sophie",          PORT_DIR + "p-sophie_1.png",    "portrait", 4/5,   900, (0, 0, 1, 1), (0.50, 0.50)),
    ("sarah",           PORT_DIR + "p-sarah_1.png",     "portrait", 4/5,   900, (0, 0, 1, 1), (0.50, 0.50)),
    # Christina bleibt als EINZIGE bei ihren echten Fotos. Vier Durchgaenge
    # haben ihr Gesicht jedes Mal neu gezeichnet: ihre Vorlagen sind die
    # einzigen, auf denen die Person an einem Gelaender lehnt — ohne Gelaender
    # muss das Modell beide Arme und damit halbe Koerperhaltung neu erfinden,
    # und dabei geht das Gesicht mit. Ein fremdes Gesicht unter ihrem Namen
    # waere schlimmer als ein Hintergrund, der nicht zum Feld passt.
    # Sarah und Sophie behalten ihre erzeugten Feld-Aufnahmen, dort hielt die
    # Identitaet, weil nur der Hintergrund getauscht wurde.
    ("christina",       "IMG_3146.jpeg",  "portrait", 4/5,   900, (0.02, 0.02, 0.98, 0.62), (0.42, 0.30)),

    # Dieselben drei noch einmal, als Bild zur jeweiligen Experience.
    ("exp-sarah",       PORT_DIR + "e-sarah.png",       "portrait", 4/5,  1000, (0, 0, 1, 1), (0.50, 0.50)),
    ("exp-sophie",      PORT_DIR + "e-sophie.png",      "portrait", 4/5,  1000, (0, 0, 1, 1), (0.50, 0.50)),
    ("exp-christina",   "IMG_3145.jpeg",  "portrait", 4/5,  1000, (0.10, 0.04, 1.00, 0.66), (0.55, 0.30)),

    ("buchung-bg",      "1e0a5675-f83e-4eb0-bb69-73a290f60e68.jpeg", "sunset", 16/9, 1600, (0.00, 0.00, 1.00, 0.75), (0.50, 0.45)),
    # Abschluss: die Drei in der RECHTEN Bilddrittel, links bleibt ruhig. Der
    # Verlauf in .abschluss__bg::after ist waagerecht (links .66 dunkel, ab 80 %
    # ganz frei) — ein mittig stehendes Motiv verschwände unter der Schrift.
    # Profil "sunset", nicht "outdoor_haut": es lässt den rosa Himmel stehen
    # (sky=1.00) und dämpft Rot nicht, also bleibt die Haut sauber.
    ("abschluss",       HERO_DIR + "abschluss-drei.png", "sunset", 16/9, 1920, (0, 0, 1, 1), (0.50, 0.50)),
    # --- Nachtrag: die Seite hatte zu viel Leerraum, das Material gab mehr her ---
    ("detail-kerze",    "26-08-12 10-00-12 7608.jpg", "detail",   4/5,   900, (0.00, 0.06, 1.00, 1.00), (0.50, 0.55)),
    ("bms-fries",       "26-08-12 09-54-18 7585.jpg", "outdoor",  2.6,  1920, (0.00, 0.28, 1.00, 0.94), (0.50, 0.55)),
    ("exp-struktur",    "26-08-12 10-07-43 7616.jpg", "interior", 4/5,  1000, (0.05, 0.08, 1.00, 1.00), (0.55, 0.55)),
    ("inkl-bad",        "26-08-12 10-09-06 7618.jpg", "interior", 4/5,   900, (0.06, 0.04, 1.00, 0.98), (0.50, 0.50)),
    ("fuerwen-blick",   "26-08-12 10-08-02 7617.jpg", "outdoor",  4/5,   900, (0.00, 0.22, 1.00, 1.00), (0.50, 0.60)),
    ("ort-terrasse2",   "26-08-12 10-07-10 7613.jpg", "outdoor", 16/9,  1100, (0.00, 0.10, 1.00, 0.94), (0.50, 0.55)),
    ("ort-eingang",     "26-08-12 09-51-59 7581.jpg", "outdoor",  4/5,  1000, (0.20, 0.10, 1.00, 0.96), (0.55, 0.50)),
    ("ort-haus",        "26-08-12 10-07-18 7614.jpg", "outdoor",  4/5,  1000, (0.00, 0.14, 1.00, 1.00), (0.50, 0.55)),
]

if __name__ == "__main__":
    import sys
    only = set(sys.argv[1:])          # ohne Argument: alles neu backen
    for job in JOBS:
        if not only or job[0] in only:
            build(*job)
