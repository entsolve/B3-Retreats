# Hero-Bild: die drei Gastgeberinnen von hinten

Bild für die Startseite: Christina, Sarah und Sophie von hinten, am Feldrand
oberhalb des Anwesens. Ein echtes Gruppenfoto der drei gibt es in den
Kundenmaterialien nicht — nur Einzelporträts. Deshalb generiert.

## Warum überhaupt generiert

`materials- in/` durchgesehen: 62 Bilder, davon 48 Drohnen- und Objektaufnahmen
vom 12.08.2026, dazu Einzelporträts, das Logo, ein Pinterest-Screenshot und
Essensfotos. Kein einziges Bild zeigt die drei zusammen.

## Ausführen

```bash
cd ~/Documents/entsolve/GIT/polarholz-3drenders     # dort liegen generate.py und der Schlüssel
S=~/Documents/entsolve/GIT/B3-Retreats/tools/hero-drei
python3 generate.py --prompt-file $S/prompt.txt \
  --ref $S/ref/christina.png --ref $S/ref/sarah.png --ref $S/ref/sophie.png \
  --ref $S/ref/hero-wide.png \
  --out $S/out/v1 --n 3 --aspect 16:9 --size 2K \
  --model gemini-3-pro-image-preview
```

Über fal statt Gemini — gleicher Prompt, gleiche Referenzen:

```bash
python3 $S/falimg.py --prompt-file $S/prompt.txt \
  --ref $S/ref/christina.png --ref $S/ref/sarah.png --ref $S/ref/sophie.png \
  --ref $S/ref/hero-wide.png --out $S/out/v1 --n 3 --model nbpro
```

Beide laufen auf Vorkasse — eine hinterlegte Karte lädt das Guthaben nicht von
selbst auf. Fehlt Guthaben, kommt `429 prepayment credits are depleted` (Gemini)
bzw. `403 User is locked, exhausted balance` (fal, schon beim CDN-Upload).

## Ergebnis, 15.08.2026

`out/final-hero-wide.png` (2752×1536) und `out/final-hero-tall.png` (4:5,
Ausschnitt daraus — nicht neu generiert, sonst wandern Posen und Hände).
Gegenüberstellung für die Kundin: `out/vergleich.png`.

Zwei Durchgänge:

1. `prompt.txt`, drei Varianten. `v1_2` und `v1_3` fielen durch: über dem Rücken
   der mittleren Frau kreuzen sich zwei Unterarme, **die Mittlere hat selbst
   keinen einzigen sichtbaren Arm**, und eine Hand kommt auf der falschen Seite
   der Taille wieder heraus. Genau der Fehler des ChatGPT-Entwurfs, nur sauberer
   gemalt. `v1_1` hielt: sechs Arme, jeder von der Schulter bis zur Hand
   nachvollziehbar.
2. `fix.txt` als Retusche **auf `v1_1`**, nicht als neue Szene — nur Köpfe
   abgewandt und Schatten am Boden. `v2_1` zerstörte dabei die Hand auf der
   Schulter (dunkler Fleck), `v2_2` blieb heil und ist die Endfassung.

Die Lehre: bei drei Personen im Arm entscheidet sich alles an der Stelle, wo die
Arme zusammenlaufen. Immer dorthin zoomen, bevor irgendetwas weitergeht — und
Korrekturen als Retusche des freigegebenen Kaders fahren, nie als neue Szene.

## Referenzen

| Datei | wofür |
|---|---|
| `ref/christina.png` | links — dunkelblonder tiefer Dutt, Satinkleid champagner |
| `ref/sarah.png` | Mitte — langes kupferrotes Naturwelle, Leinen in Creme |
| `ref/sophie.png` | rechts — langes hellbraunes glattes Haar, Mandala am Oberarm |
| `ref/hero-wide.png` | der echte Blick vom Grundstück, Mittagslicht |
| `ref/ort-sonne.png` | Baumkante und trockene Gräser im Vordergrund |
| `ref/abend-blick.jpg` | dieselbe Kuppe zur blauen Stunde — für eine Abendfassung |

Die Porträts liefern nur Haar, Hautton, Statur und Kleidungsstil. Gesichter
bleiben aus dem Bild, die Drei stehen mit dem Rücken zur Kamera.

## Was am ChatGPT-Entwurf falsch war

Der Entwurf der Kundin (drei Frauen vor Sonnenuntergang) scheitert an vier
Stellen, und der Prompt arbeitet gezielt gegen genau diese vier:

1. **Arme.** Aus der mittleren Frau wächst ein Arm, der ihr nicht gehört; eine
   Hand liegt ohne Unterarm auf dem Rücken der linken. Deshalb steht im Prompt
   nur *ein* Körperkontakt in der ganzen Gruppe, mit vollständig sichtbarer Hand.
2. **Symmetrie.** Drei gleiche Höhen, drei gleiche Haltungen, Gruppe mittig, Sonne
   exakt in der Bildmitte. Deshalb versetzte Standpunkte, unterschiedliche
   Schulterhöhen, Gruppe links der Mitte.
3. **Licht.** Ein oranges Bad über dem ganzen Bild, Halo um jede Haarsträhne,
   und am Boden liegt kein einziger Schatten. Deshalb explizit drei lange
   Schatten zur Kamera hin und Sonnenstand bei 25°.
4. **Ort.** Eine beliebige Stock-Heide. Deshalb die echte Kuppe aus
   `ref/hero-wide.png`: abgeerntetes Feld, dunkle Eichenkante, trockene Gräser.

## Eingebaut

Seit 15.08.2026 im Hero der Startseite, statt der menschenleeren Landschaft.
`hero-wide`, `hero-tall` und `og-image` in `tools/build-assets.py` zeigen jetzt
auf diesen Ordner; neu backen mit `python3 tools/build-assets.py hero-wide
hero-tall og-image`.

Drei Dinge, die dabei nicht offensichtlich sind:

* **Profil `outdoor_haut`, nicht `outdoor`.** `outdoor` dämpft Rot auf 0.68 —
  gedacht für Ziegel und Blüten, auf Haut gibt das Flecken.
* **Quer und hoch sind zwei erzeugte Bilder, kein Ausschnitt voneinander.** Die
  Bildspalte ist am Desktop fast quadratisch und wird am Tablet schmal und hoch;
  ein 3:4-Ausschnitt aus dem Querformat schiebt je eine der äußeren Frauen aus
  dem Rand. Das Hochformat entstand als Outpaint (`extend.txt`), das die drei
  unverändert lässt und nur Himmel, Feld und Gräser ringsum ergänzt.
* **Der `alt`-Text nennt keine Namen** — im Bild stehen erzeugte Frauen, nicht
  Christina, Sarah und Sophie. Deren echte Porträts stehen weiter unten auf
  derselben Seite.

Geprüft bei 1440, 1024 und 390 px: alle drei bleiben im Ausschnitt, die
Überschrift steht neben dem Bild und nicht darauf. Die Sättigung liegt bei
38,8 % (quer) und 32,9 % (hoch) und damit mitten im Feld der übrigen 31 Bilder
(13,8 – 51,5 %) — kein Ausreißer.

## Abschluss-Sektion

`abschluss.webp` zeigt seit 15.08.2026 dieselben drei in der Dämmerung
(`out/abschluss-drei.png`, Profil `sunset`). Zwei Dinge geben hier die
Bildregie vor, und beide stehen im CSS, nicht im Geschmack:

**Die Drei stehen im rechten Drittel.** `.abschluss__bg::after` legt einen
*waagerechten* Verlauf über das Bild: links .66 deckend, bei 58 % noch .20, ab
80 % gar nichts mehr. Links steht die Schrift, rechts ist das Bild frei. Ein
mittiges Motiv verschwände unter dem Text.

**Der Kader wurde eine Blaue Stunde weiter gezogen.** Die erste Fassung
(`out/ab_1.png`) hatte oben links hellrosa Himmel, und darüber wurde die helle
Schrift unlesbar. Nachgemessen mit dem echten Verlauf, in der Textspalte,
Vordergrund `rgba(247,244,239,.88)`:

| Bild | Hintergrund | Kontrast min | Median | unter 4.5:1 | unter 3:1 |
|---|---|---|---|---|---|
| altes Abendfeld (ohne Menschen) | 61,4 | 2,13 | 10,14 | 17,3 % | 6,3 % |
| erste Fassung, rosa Himmel | 96,7 | 1,92 | 4,58 | 47,7 % | 17,9 % |
| **jetzt, tiefere Dämmerung** | **52,3** | **3,15** | **9,61** | **8,9 %** | **0,0 %** |

Repariert wurde das am Licht, nicht am Verlauf: `abschluss-fix.txt` zieht
denselben Kader eine Viertelstunde später — dunkler Himmel, fast schwarze
Baumkante über der linken Hälfte, die drei bleiben hell. Ein stärkerer
CSS-Schleier hätte stattdessen das ganze Foto zugedeckt.

### Zwei CSS-Korrekturen, die das Motiv im rechten Drittel nötig macht

**`object-position: 78% center` auf `.abschluss__bg img`.** `object-fit: cover`
schneidet mittig. Bei einem hohen, schmalen Fenster ist der sichtbare Streifen
schmaler als das Bild, und weggeschnitten wird genau der rechte Rand — also die
Gruppe. Gemessen: ab 1024 px Breite fing es an, bei 1024×1366 fehlten 53 % der
Gruppe, bei 1200×1100 immerhin 8 %. Mit 78 % frisst der Ausschnitt stattdessen
das leere Feld links auf, und die drei bleiben von 1440 px bis hinunter aufs
Handy vollständig im Bild.

**Der senkrechte Schleier gilt jetzt ab 1080 px, nicht erst ab 860 px.** Bei
1080 px springt `.abschluss__tx` schon auf `grid-column: 1 / -1`, die Schrift
läuft also über die ganze Bildbreite — der waagerechte Verlauf deckte aber
weiter nur links. In diesem Band lag der Text ungeschützt über dem hellen Teil
des Bildes. Das war vorher auch schon so, fiel nur nicht auf, solange dort ein
leeres Feld stand. Nach der Korrektur bei 1024×1366: Kontrast-Minimum 6,1 statt
2,0, kein Punkt mehr unter 3:1.

Am Telefon ist das Foto durch diesen Schleier ohnehin nur noch dunkle Fläche
hinter dem Text — die Bildregie im rechten Drittel kostet dort nichts.

Nachgeprüft im Browser bei 1440×900, 1200×1100 und 1024×1366 (Testseite mit
nur dieser Sektion, ohne Skript, damit die Scroll-Animation nichts versteckt).

### Die Umarmung

Endfassung ist `out/hug_2.png`: die drei halten sich. Erzeugt als Retusche von
`out/abd_1.png` mit `abschluss-umarmung.txt`, das die Pose **Arm für Arm**
vorschreibt — sechs Arme, sechs Hände, jede Hand an ihrem eigenen Platz. Ohne
diese Aufzählung landet das Modell zuverlässig bei zwei Unterarmen, die sich
über derselben Taille kreuzen, und bei einer Hand, die zu niemandem gehört.

Von drei Varianten hielt nur `hug_2` der Prüfung bei 100 % stand:

* `hug_1` — Köpfe aneinander gelehnt, sehr schön, aber an der Taille der
  mittleren Frau liegt eine matschige Faust, deren Arm sich nicht auflösen lässt.
* `hug_3` — dasselbe Problem, dazu verdeckt der Arm der Blonden ihren eigenen.
* `hug_2` — alle sechs Arme laufen sauber: die Rechte legt den linken Arm über
  die Schulter der Mittleren, die Blonde den rechten Arm um deren Rücken (die
  Hand darauf ist die sauberste im ganzen Satz — Knöchel, Poren, Nagelränder),
  und die Mittlere fasst beide Nachbarinnen an der äußeren Hüfte.

Weil sie enger stehen, ist die Gruppe schmaler geworden (0,628–0,834 statt
0,622–0,851). Damit bleibt sie mit `object-position: 78%` bei **jeder** geprüften
Fenstergröße vollständig im Bild, auch am Telefon, wo vorher 4 % wegfielen.
