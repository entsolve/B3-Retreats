"""Redaktionsoberflaeche fuer B3 Retreats — laeuft lokal, nie im Netz.

    python3 tools/admin-server.py            # http://127.0.0.1:8790/admin/
    python3 tools/admin-server.py --port 9000

Was der Server tut:

  * liefert die Oberflaeche unter /admin/ und die Seite selbst zur Vorschau,
  * liest und schreibt content/site.json,
  * baut nach jedem Speichern index.html neu (tools/build-site.py),
  * legt vor jedem Schreiben eine Sicherung an und kann sie zurueckholen,
  * nimmt Fotos entgegen und backt denselben Farblook ein wie build-assets.py,
  * pflegt die Rechtstexte und ruft build-legal.py auf.

Bewusst ohne Anmeldung: der Server bindet ausschliesslich an 127.0.0.1 und ist
ein Werkzeug am eigenen Rechner, kein Dienst. Er gehoert nicht auf einen Server
und schon gar nicht hinter eine oeffentliche Adresse.

Abhaengigkeiten: nur die Standardbibliothek. Pillow und NumPy werden allein fuer
den Foto-Upload gebraucht und erst dann geladen — ohne sie laeuft alles andere.
"""
import argparse
import importlib.util
import io
import json
import mimetypes
import pathlib
import shutil
import subprocess
import sys
import time
import urllib.parse
from http.server import SimpleHTTPRequestHandler, ThreadingHTTPServer

ROOT = pathlib.Path(__file__).resolve().parent.parent
TOOLS = ROOT / "tools"
CONTENT = ROOT / "content" / "site.json"
SCHEMA = ROOT / "admin" / "schema.json"
BACKUPS = ROOT / "content" / "backups"
IMG = ROOT / "assets" / "img"
LEGAL = TOOLS / "content"

LEGAL_FILES = {"legal.md": LEGAL / "legal.md", "agb.txt": LEGAL / "agb.txt"}
UPLOAD_MAX = 40 * 1024 * 1024
KEEP_BACKUPS = 40


# --- Werkzeuge nachladen -----------------------------------------------------
# Die Skripte heissen mit Bindestrich, das laesst sich nicht normal importieren.

def load_tool(stem):
    path = TOOLS / f"{stem}.py"
    spec = importlib.util.spec_from_file_location(stem.replace("-", "_"), path)
    module = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(module)
    return module


def rebuild_site():
    """Baut index.html neu und liefert (ok, Meldung)."""
    try:
        build_site = load_tool("build-site")
    except Exception as exc:  # noqa: BLE001 — die Meldung geht an die Oberflaeche
        return False, f"build-site.py laesst sich nicht laden: {exc}"

    buffer, keep = io.StringIO(), (sys.stdout, sys.stderr)
    sys.stdout = sys.stderr = buffer
    try:
        code = build_site.build()
    except Exception as exc:  # noqa: BLE001
        return False, f"Bauen fehlgeschlagen: {exc}"
    finally:
        sys.stdout, sys.stderr = keep
    return code == 0, buffer.getvalue().strip() or "index.html neu gebaut"


def rebuild_legal():
    result = subprocess.run(
        [sys.executable, str(TOOLS / "build-legal.py")],
        capture_output=True, text=True, cwd=str(ROOT),
    )
    message = (result.stdout + result.stderr).strip()
    return result.returncode == 0, message or "Rechtstexte neu gebaut"


# --- Sicherungen -------------------------------------------------------------

def make_backup():
    if not CONTENT.exists():
        return None
    BACKUPS.mkdir(parents=True, exist_ok=True)

    # Sekundengenau reicht nicht: Zurueckholen sichert erst den heutigen Stand
    # und schreibt dann — beides in derselben Sekunde. Ohne den Zaehler
    # ueberschreibt der zweite Griff den ersten, und zurueckgeholt wird genau
    # das, was man loswerden wollte.
    stamp = time.strftime("%Y%m%d-%H%M%S")
    name = f"site-{stamp}.json"
    n = 2
    while (BACKUPS / name).exists():
        name = f"site-{stamp}-{n}.json"
        n += 1

    shutil.copy2(CONTENT, BACKUPS / name)

    old = sorted(BACKUPS.glob("site-*.json"), key=lambda p: p.stat().st_mtime)
    for path in old[:-KEEP_BACKUPS]:
        path.unlink()
    return name


def list_backups():
    if not BACKUPS.exists():
        return []
    out = []
    for path in sorted(BACKUPS.glob("site-*.json"), key=lambda p: p.stat().st_mtime, reverse=True):
        stat = path.stat()
        out.append({
            "name": path.name,
            "size": stat.st_size,
            "when": time.strftime("%d.%m.%Y %H:%M:%S", time.localtime(stat.st_mtime)),
        })
    return out


# --- Bilder ------------------------------------------------------------------

def list_images():
    if not IMG.exists():
        return []
    out = []
    for path in sorted(IMG.glob("*.webp")):
        out.append({
            "src": f"assets/img/{path.name}",
            "name": path.stem,
            "size": path.stat().st_size,
        })
    return out


def grade_upload(raw, name, profile, aspect, width):
    """Backt denselben Look ein wie build-assets.py.

    Ohne diesen Schritt faellt jedes neue Foto aus der Tonalitaet: die Aufnahmen
    sind mittags entstanden, der Himmel ist kobaltblau und das Gruen giftig.
    """
    try:
        import numpy as np
        from PIL import Image, ImageEnhance, ImageFilter, ImageOps
    except ImportError:
        return None, "Fuer den Foto-Upload fehlen Pillow und NumPy (pip install pillow numpy)"

    try:
        assets = load_tool("build-assets")
    except Exception as exc:  # noqa: BLE001
        return None, f"build-assets.py laesst sich nicht laden: {exc}"

    if profile not in assets.P:
        return None, f"Unbekanntes Profil '{profile}' — erlaubt: {', '.join(assets.P)}"

    p = assets.P[profile]
    try:
        im = ImageOps.exif_transpose(Image.open(io.BytesIO(raw))).convert("RGB")
    except Exception as exc:  # noqa: BLE001
        return None, f"Bild nicht lesbar: {exc}"

    box = (0, 0, im.width, im.height)
    im = im.crop(assets.fit_aspect(box, aspect, (0.5, 0.5)))

    target = (width, max(1, round(width / aspect)))
    if im.width > target[0] * 2:
        im = im.resize((target[0] * 2, target[1] * 2), Image.LANCZOS)
    im = im.resize(target, Image.LANCZOS)

    a = np.asarray(im, dtype=np.float64)
    a = assets.highlights(a, p["hi"])
    a = assets.white_balance(a, p["wb"])
    a = assets.selective(a, p["sky"], p["green"], p["red"])
    im = Image.fromarray(np.clip(a, 0, 255).astype(np.uint8))
    im = ImageEnhance.Color(im).enhance(p["sat"])

    a = np.asarray(im, dtype=np.float64)
    a = assets.matte(a)
    a = assets.scurve(a)
    a = assets.grain(a)
    a = assets.vignette(a, p["vig"])
    im = Image.fromarray(np.clip(a, 10, 248).astype(np.uint8))

    if p["sharp"]:
        im = im.filter(ImageFilter.UnsharpMask(radius=0.8, percent=40, threshold=3))

    IMG.mkdir(parents=True, exist_ok=True)
    path = IMG / f"{name}.webp"
    im.save(path, "WEBP", quality=84, method=6)
    return {
        "src": f"assets/img/{path.name}",
        "name": path.stem,
        "size": path.stat().st_size,
        "width": im.width,
        "height": im.height,
    }, None


def safe_stem(raw):
    """Dateiname ohne Ueberraschungen: nur Kleinbuchstaben, Ziffern, Bindestrich."""
    keep = []
    for ch in (raw or "").lower().strip():
        if ch.isalnum() and ch.isascii():
            keep.append(ch)
        elif ch in " _-." and keep and keep[-1] != "-":
            keep.append("-")
    stem = "".join(keep).strip("-")
    return stem[:60] or "bild"


# --- Server ------------------------------------------------------------------

class Handler(SimpleHTTPRequestHandler):
    """Statische Dateien aus dem Projekt plus eine kleine JSON-Schnittstelle."""

    protocol_version = "HTTP/1.1"

    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory=str(ROOT), **kwargs)

    # --- Antworten ---------------------------------------------------------

    def send_json(self, payload, status=200):
        body = json.dumps(payload, ensure_ascii=False, indent=1).encode("utf-8")
        self.send_response(status)
        self.send_header("Content-Type", "application/json; charset=utf-8")
        self.send_header("Content-Length", str(len(body)))
        self.send_header("Cache-Control", "no-store")
        self.end_headers()
        self.wfile.write(body)

    def fail(self, message, status=400):
        self.send_json({"ok": False, "error": message}, status)

    def read_body(self, limit=UPLOAD_MAX):
        length = int(self.headers.get("Content-Length") or 0)
        if length > limit:
            return None
        return self.rfile.read(length)

    def read_json(self):
        raw = self.read_body(8 * 1024 * 1024)
        if raw is None:
            return None, "Anfrage zu gross"
        try:
            return json.loads(raw.decode("utf-8")), None
        except (UnicodeDecodeError, json.JSONDecodeError) as exc:
            return None, f"Kein gueltiges JSON: {exc}"

    # --- Weichen -----------------------------------------------------------

    def do_GET(self):
        path = urllib.parse.urlparse(self.path).path
        if path == "/":
            self.send_response(302)
            self.send_header("Location", "/admin/")
            self.send_header("Content-Length", "0")
            self.end_headers()
            return
        if path.startswith("/api/"):
            return self.api_get(path)
        # Die Seite selbst darf nicht aus dem Zwischenspeicher kommen, sonst
        # zeigt die Vorschau nach dem Speichern den alten Stand.
        self.send_header_no_cache = path.endswith((".html", ".json"))
        return super().do_GET()

    def end_headers(self):
        if getattr(self, "send_header_no_cache", False):
            self.send_header("Cache-Control", "no-store")
            self.send_header_no_cache = False
        super().end_headers()

    def do_PUT(self):
        path = urllib.parse.urlparse(self.path).path
        if path == "/api/content":
            return self.put_content()
        if path == "/api/legal":
            return self.put_legal()
        return self.fail("Unbekannter Endpunkt", 404)

    def do_POST(self):
        path = urllib.parse.urlparse(self.path).path
        if path == "/api/upload":
            return self.post_upload()
        if path == "/api/restore":
            return self.post_restore()
        if path == "/api/rebuild":
            ok, message = rebuild_site()
            return self.send_json({"ok": ok, "message": message})
        return self.fail("Unbekannter Endpunkt", 404)

    # --- Schnittstelle -----------------------------------------------------

    def api_get(self, path):
        if path == "/api/state":
            if not CONTENT.exists():
                return self.fail("content/site.json fehlt — zuerst tools/build-site.py einrichten", 404)
            legal = {}
            for name, file in LEGAL_FILES.items():
                legal[name] = file.read_text(encoding="utf-8") if file.exists() else ""
            return self.send_json({
                "ok": True,
                "content": json.loads(CONTENT.read_text(encoding="utf-8")),
                "schema": json.loads(SCHEMA.read_text(encoding="utf-8")) if SCHEMA.exists() else [],
                "images": list_images(),
                "backups": list_backups(),
                "legal": legal,
                "profiles": ["outdoor", "interior", "detail", "portrait", "portrait_beton", "sunset", "food"],
            })
        if path == "/api/backups":
            return self.send_json({"ok": True, "backups": list_backups()})
        return self.fail("Unbekannter Endpunkt", 404)

    def put_content(self):
        data, error = self.read_json()
        if error:
            return self.fail(error)
        if not isinstance(data, dict):
            return self.fail("Erwartet wird ein Objekt")

        backup = make_backup()
        CONTENT.parent.mkdir(parents=True, exist_ok=True)
        CONTENT.write_text(json.dumps(data, ensure_ascii=False, indent=2) + "\n", encoding="utf-8")

        ok, message = rebuild_site()
        if not ok and backup:
            # Beim Fehlschlag bleibt die Datei stehen, damit der Fehler sichtbar
            # bleibt — zurueckholen kann die Oberflaeche ueber die Sicherung.
            message += f"\nSicherung vor dieser Aenderung: {backup}"
        return self.send_json({"ok": ok, "message": message, "backup": backup,
                               "backups": list_backups()})

    def put_legal(self):
        data, error = self.read_json()
        if error:
            return self.fail(error)
        name = (data or {}).get("file")
        text = (data or {}).get("text")
        if name not in LEGAL_FILES:
            return self.fail(f"Nur {', '.join(LEGAL_FILES)} sind erlaubt")
        if not isinstance(text, str):
            return self.fail("Kein Text uebergeben")

        file = LEGAL_FILES[name]
        shutil.copy2(file, file.with_suffix(file.suffix + ".bak")) if file.exists() else None
        file.write_text(text, encoding="utf-8")
        ok, message = rebuild_legal()
        return self.send_json({"ok": ok, "message": message})

    def post_upload(self):
        raw = self.read_body()
        if raw is None:
            return self.fail("Datei zu gross (mehr als 40 MB)")
        if not raw:
            return self.fail("Leere Datei")

        name = safe_stem(self.headers.get("X-Bild-Name"))
        profile = self.headers.get("X-Bild-Profil") or "outdoor"
        try:
            aspect = float(self.headers.get("X-Bild-Seitenverhaeltnis") or 1.0)
            width = int(self.headers.get("X-Bild-Breite") or 1000)
        except ValueError:
            return self.fail("Seitenverhaeltnis oder Breite sind keine Zahlen")
        if not 0.2 <= aspect <= 5 or not 200 <= width <= 3000:
            return self.fail("Seitenverhaeltnis 0,2–5 und Breite 200–3000 px")

        image, error = grade_upload(raw, name, profile, aspect, width)
        if error:
            return self.fail(error, 500)
        return self.send_json({"ok": True, "image": image, "images": list_images()})

    def post_restore(self):
        data, error = self.read_json()
        if error:
            return self.fail(error)
        name = (data or {}).get("name", "")
        if not name.startswith("site-") or not name.endswith(".json") or "/" in name:
            return self.fail("Unbekannte Sicherung")
        path = BACKUPS / name
        if not path.exists():
            return self.fail("Sicherung gibt es nicht mehr", 404)

        make_backup()
        shutil.copy2(path, CONTENT)
        ok, message = rebuild_site()
        return self.send_json({"ok": ok, "message": f"{name} zurueckgeholt. {message}",
                               "content": json.loads(CONTENT.read_text(encoding="utf-8")),
                               "backups": list_backups()})

    def log_message(self, fmt, *args):
        if "/api/" in (args[0] if args else ""):
            super().log_message(fmt, *args)


def main():
    parser = argparse.ArgumentParser(description="Redaktionsoberflaeche fuer B3 Retreats")
    parser.add_argument("--port", type=int, default=8790)
    args = parser.parse_args()

    mimetypes.add_type("image/webp", ".webp")
    mimetypes.add_type("font/woff2", ".woff2")

    # ThreadingHTTPServer, nicht der einfache: der einfache laesst bei parallelen
    # Bildanfragen die halbe Galerie fallen (siehe README).
    server = ThreadingHTTPServer(("127.0.0.1", args.port), Handler)
    print(f"Redaktion:  http://127.0.0.1:{args.port}/admin/")
    print(f"Vorschau:   http://127.0.0.1:{args.port}/index.html")
    print("Beenden mit Strg+C")
    try:
        server.serve_forever()
    except KeyboardInterrupt:
        print("\nbeendet")


if __name__ == "__main__":
    main()
