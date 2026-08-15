import re, os, urllib.request

ROOT = "/Users/llashutko/Documents/entsolve/GIT/B3-Retreats"
OUT = os.path.join(ROOT, "assets/fonts")
css = open("/tmp/b3fonts.css", encoding="utf-8").read()

LATIN = "U+0000-00FF"
LATIN_EXT = "U+0100-02BA"

blocks = re.findall(r"/\* (\S+) \*/\s*(@font-face \{.*?\})", css, re.S)
out_rules = []
seen = {}
for label, rule in blocks:
    if label not in ("latin", "latin-ext"):
        continue
    fam = re.search(r"font-family: '([^']+)'", rule).group(1)
    style = re.search(r"font-style: (\w+)", rule).group(1)
    weight = re.search(r"font-weight: (\d+)", rule).group(1)
    url = re.search(r"url\((https://[^)]+)\)", rule).group(1)
    urange = re.search(r"unicode-range: ([^;]+);", rule).group(1)

    slug = fam.lower().replace(" ", "-")
    name = f"{slug}-{weight}{'-italic' if style == 'italic' else ''}-{label}.woff2"
    path = os.path.join(OUT, name)
    if not os.path.exists(path):
        req = urllib.request.Request(url, headers={"User-Agent": "Mozilla/5.0"})
        with urllib.request.urlopen(req, timeout=30) as r, open(path, "wb") as f:
            f.write(r.read())
    seen[name] = os.path.getsize(path)
    out_rules.append(
        "@font-face{\n"
        f"  font-family:'{fam}';\n"
        f"  font-style:{style};\n"
        f"  font-weight:{weight};\n"
        "  font-display:swap;\n"
        f"  src:url('../fonts/{name}') format('woff2');\n"
        f"  unicode-range:{urange};\n"
        "}"
    )

header = "/* B³ Retreats — lokal gehostete Schriften. Keine Anfragen an fonts.googleapis.com / fonts.gstatic.com. */\n"
open(os.path.join(ROOT, "assets/css/fonts.css"), "w", encoding="utf-8").write(header + "\n".join(out_rules) + "\n")

total = sum(seen.values())
for k, v in sorted(seen.items()):
    print(f"{v/1024:7.1f} KB  {k}")
print(f"{total/1024:7.1f} KB  TOTAL ({len(seen)} files)")
