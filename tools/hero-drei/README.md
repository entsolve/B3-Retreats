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

## Endstand 17.08.2026 (abends) — die Umarmung, `montage.py`

**Gueltig sind `final-hero-tall.png` = `out/m3tall.png` und `final-hero-wide.png`
= `out/m3wide.png`.** Beides sind *Montagen*, keine reinen Generierungen: die
Frauen kommen aus der Retusche, alles andere aus dem vorher freigegebenen Bild.

Auftrag der Kundin: die drei sollen sich umarmen, und die Mittlere soll ein
seidenes Kleid tragen statt des Leinensacks („sonst wie bei einer Oma").

Der erste Durchgang (`umarmung-seide.txt`, `out/u1*`, `out/t1*`) wurde abgelehnt,
und die Kundin hat **vier** Fehler benannt — jeder einzelne ist eine Falle, die
bei einer Posen-Retusche zuverlaessig zuschnappt:

| Beanstandung | Ursache | Gegenmittel |
|---|---|---|
| Tattoo von einer Person zur anderen gewandert | der Unterarm der Rechten liegt jetzt auf dem Ruecken der Mittleren; das Modell fuellt ihn mit Tinte und die Tinte liegt optisch auf der Mittleren | `tattoo-zurueck-v2.txt` als eigener Durchgang: Mandala nur auf ihren Oberarm, Unterarm blanke Haut |
| Koerpergroesse verringert | beide Arme der Mittleren nach oben auf die Schultern der Nachbarinnen zu legen hebt ihre Schultern und nimmt ihr Hoehe | Pose umgedreht: die **Aeusseren** greifen hoch, die **Mittlere** haelt unten an den Huften. Dazu ein eigener Absatz `THE THREE HEIGHTS` im Prompt |
| Kleid von geschlossen auf offen geaendert | „Seide" allein liest das Modell als Trägerkleid | `umarmung-v2.txt` schreibt Seide **und** Deckung getrennt vor: Schultern und Ruecken bleiben bedeckt, kurze Ärmel bleiben, nur Stoff und Schnitt aendern sich |
| Gras unscharf / „wie im Maerchen" | das Modell rechnet die **ganze** Bodenflaeche neu und macht daraus eine gestochene Textur aus leuchtenden Haarlinien | `montage.py` — Prompts helfen hier nicht |

### Die Gradientenfalle

Der Vordergrund wurde zuerst mit Gradientenenergie geprueft, und die stieg
gegenueber dem Original (16,6 → 19,3). Das sah nach „schaerfer" aus und war der
Fehler: die erfundenen Haarlinien und das Punktmuster heben die Kennzahl genauso
wie echte Halme. **Die Zahl allein entscheidet nicht** — der Vordergrund muss
im Ausschnitt bei 100 % neben dem Original liegen. Erst dort sieht man die
Radierung. Nach der Montage steht die Zahl wieder bei 16,75 (quer 18,86).

### Warum montiert und nicht nachgeprompt

Das Differenzbild der beiden Fassungen zeigt es: Himmel und Baumkante sind
schwarz, die Frauen hell — und der **ganze Boden** flaechig aufgerauscht. Das
Modell laesst die Flaeche nicht in Ruhe, gleich wie oft man es ihm verbietet.
`montage.py` nimmt darum die geblurrte Differenz als Maske: wo die Retusche
wirklich etwas geaendert hat (Arme, Kleid), kommt das neue Bild, sonst das alte.

Drei Dinge, die dabei nicht offensichtlich sind:

* **Die Maske braucht eine raeumliche Sperre (`--box`).** Der Schattenstreifen im
  Feld und die Disteln am linken Rand aendern sich stark genug, um jede Schwelle
  zu reissen — und das sind genau die Stellen, die aus dem Original kommen
  sollen.
* **Die Sperre endet oberhalb der Fuesse** (`y1 = 0.70` hoch, `0.82` quer). Beine,
  Fuesse und Schatten sind in beiden Fassungen gleich; nimmt man sie aus dem
  Original, verschwinden die Flickenmuster, die eine weiter heruntergezogene
  Maske rings um die Fuesse hinterlaesst.
* **Harte Schwelle plus Aufweitung, keine weiche Rampe.** Eine Rampe quer ueber
  einen Arm laesst den Hintergrund durch die halbdurchsichtige Kante scheinen —
  die Arme bekamen davon einen Doppelrand. Jetzt wird geschwellt, um 0,7 % der
  Bildbreite aufgeweitet und nur schmal gefedert: der Maskenrand liegt im Feld
  ringsum, wo beide Bilder ohnehin gleich sind.

### Nachzufahren

```bash
S=~/Documents/entsolve/GIT/B3-Retreats/tools/hero-drei
G=~/Documents/entsolve/GIT/polarholz-3drenders/generate.py

# 1 — quer: Umarmung + Seide auf den freigegebenen Kader
python3 $G --image $S/out/final-hero-wide.png --prompt-file $S/umarmung-v2.txt \
  --ref $S/out/v2ttall_1.png --out $S/out/v2wide --n 3 --aspect 16:9 --size 2K \
  --model gemini-3-pro-image-preview
# 2 — Tattoo zurueck auf den Oberarm
python3 $G --image $S/out/v2wide_3.png --prompt-file $S/tattoo-zurueck-v2.txt \
  --out $S/out/v2twide --n 2 --aspect 16:9 --size 2K --model gemini-3-pro-image-preview
# 3 — hoch: dasselbe, mit dem Sieger von quer als Posen-Referenz, --aspect 3:4
# 4 — Montage: Frauen aus der Retusche, Rest aus dem Freigegebenen
python3 $S/montage.py $S/out/_verworfen/vor-umarmung-tall.png \
  $S/out/v3ttall_2.png $S/out/m3tall.png --box 0.20,0.74,0.26,0.70
python3 $S/montage.py $S/out/_verworfen/vor-umarmung-wide.png \
  $S/out/v2twide_1.png $S/out/m3wide.png --box 0.24,0.73,0.08,0.82
python3 tools/build-assets.py hero-wide hero-tall og-image
```

Die freigegebene Vorstufe liegt als `out/_verworfen/vor-umarmung-{wide,tall}.png`
daneben — ohne sie laesst sich die Montage nicht wiederholen.

### Die Pose, Arm fuer Arm

Sechs Arme, sechs Haende, nichts kreuzt sich: die **Linke** legt ihren rechten
Arm hoch auf den Ruecken der Mittleren; die **Rechte** legt ihren linken Arm
(mit dem Mandala am Oberarm) tiefer auf deren Kreuz; die **Mittlere** haelt beide
Nachbarinnen an der aeusseren Huefte und behaelt ihre Arme dabei unten, damit
ihre Hoehe bleibt. Die aeusseren Arme haengen frei.

Ein Zwischenstand hatte den inneren Arm der Linken haengen — sie wurde gehalten,
hielt aber nicht mit. `arm-links.txt` sollte das nachtraeglich richten und war der
falsche Weg: die Hand landete auf dem Gesaess der Mittleren, und der dritte
Generierungsdurchgang legte ein Rasterpunktmuster ueber Himmel und Haut. Statt
nachzuretuschieren wurde das Hochformat mit dem Querformat als Referenz **neu**
erzeugt (`out/v3tall_1`) — zwei Durchgaenge, nicht drei.

### Was im CSS dazu noetig war

`assets/css/style.css`, Abschnitt `01 Hero`:

* **`.hero__ph` ist jetzt links oval und laeuft rechts aus dem Bild**
  (`border-radius: 34% 0 0 34% / 50% 0 0 50%`) — dieselbe Sprache wie
  `.exp__item--flip`. Am Telefon wieder gerade, dort ist es ein Band ueber der
  Schrift und die Rundung waere eine schiefe Ecke.
* **`object-position: 62% 54%`** und beide Zahlen sind nachgemessen. Waagerecht,
  weil das Oval die linke Haelfte der Ober- und Unterkante frisst. Senkrecht,
  weil erst `.hero__ph` das 118 % hohe Parallaxenbild schneidet und dann
  `object-fit: cover` — die Gruppe steht bei 0,315–0,725, und nur Werte aus
  `[0,50 … 0,58]` halten sie ueber 14 Fenstergroessen x drei Scrollstaende
  vollstaendig im Bild. Engste Groesse ist 1920x900. Ausnahme bleibt ein extrem
  flaches Fenster (2560x900, 2,8:1); das war vorher schon so.
* **Textspalte 46fr statt 42fr**, rechtes Polster kleiner: „to be you." stand
  gequetscht unter „Be free".

Wer die Gruppe im Bild verschiebt, muss `object-position` neu messen — nicht
schaetzen. Die Rechnung steht als Kommentar an der Regel.

## Endstand 17.08.2026 — Retusche des Originals, `fix4.txt`

**Die gueltige Fassung ist eine Retusche von `final-hero-wide-v1.png`**, nicht
die Neugenerierung darunter. Die Kundin hat beides gesehen und den ersten Kader
als den richtigen bestaetigt: die Neugenerierung liefert ein anderes, schwaecheres
Bild, gebraucht wurden nur drei Korrekturen am vorhandenen.

```bash
S=~/Documents/entsolve/GIT/B3-Retreats/tools/hero-drei
python3 ~/Documents/entsolve/GIT/polarholz-3drenders/generate.py \
  --image $S/out/final-hero-wide-v1.png --prompt-file $S/fix4.txt \
  --ref $S/ref/sarah.png --out $S/out/f4 --n 3 --aspect 16:9 --size 2K \
  --model gemini-3-pro-image-preview
# Hochformat: dasselbe mit final-hero-tall-v1.png und --aspect 3:4
```

Endfassungen: `out/f4_1.png` → `final-hero-wide.png`, `out/f4tall_1.png` →
`final-hero-tall.png`.

**Was `fix4.txt` anders macht als die frueheren Versuche:** der Kader steht als
erster Absatz und wird einzeln beschrieben — Baumkante, Groesse der Frauen,
Grasstreifen unter den Fuessen, Disteln am linken Rand — mit dem Satz, dass die
Retusche gescheitert ist, wenn sich daran etwas aendert. `fix2.txt` hatte den
Kader nur pauschal geschuetzt („do not re-frame"), und genau das hat das Modell
ignoriert: die Gruppe rueckte naeher, die Fuesse fielen aus dem Bild.

**Kader nachmessen, nicht nur ansehen.** Die Verschiebung faellt im Vergleich
zweier Vollbilder kaum auf. Verlaesslich ist die Zeile, in der am linken Rand der
Himmel in die Baumkante uebergeht:

```python
a = np.asarray(Image.open(p).convert("RGB").resize((688,384))).astype(float)
c = a[:, 60:120].mean(1).mean(1)
zeile = int(np.argmax(np.abs(np.diff(c)) > 6))   # muss gleich der von v1 sein
```

Bei `f4_1` und `f4tall_1` ist die Abweichung 0. Bei den verworfenen Durchgaengen
lag sie deutlich darueber.

## Verworfen: Neuaufnahme statt Retusche (17.08.2026)

Die Kundin hat die Retusche vom 16.08. abgelehnt — zu Recht: der Kader war dabei
enger geworden und die Fuesse angeschnitten. Ersetzt durch eine **neue
Generierung von Grund auf** (`prompt-v2.txt`), die den freigegebenen Kader und
die Koerpergroessen aus `out/final-hero-wide-v1.png` als Referenz nimmt und nur
Haare und Hut aendert.

Endfassungen: `out/n1_1.png` (quer) → `final-hero-wide.png`, und daraus per
Outpaint `out/n1tall_1.png` (`extend-v2.txt`) → `final-hero-tall.png`. Das
Hochformat entsteht bewusst als Outpaint des Siegers und nicht als zweite
Generierung: so stehen in beiden Formaten garantiert dieselben drei Frauen.

Drei Beobachtungen aus diesem Durchgang:

* **`n1_2` hatte den Strohhut wieder in der Hand**, obwohl der Prompt ihn an drei
  Stellen verbietet. Das Referenzbild zeigt ihn eben noch. Bei jedem neuen
  Durchgang zuerst auf die linke Hand sehen.
* **Die Fuesse fallen als Erstes aus dem Kader.** Deshalb steht `no cropped feet`
  in der Verbotsliste und der Kader ist im Prompt eigens beschrieben.
* **Der Outpaint macht die Gruppe klein.** Im 3:4 lagen ueber ihnen zwei Drittel
  Himmel; der Job `hero-tall` schneidet darum mit `(0, 0.18, 1, 0.86)` nach.

Zum Nachfahren, falls es noch einmal gebraucht wird:

```bash
S=~/Documents/entsolve/GIT/B3-Retreats/tools/hero-drei
P=~/Documents/entsolve/GIT/B3-Retreats/tools/portraets
G=~/Documents/entsolve/GIT/polarholz-3drenders/generate.py

# 1 — Hero quer, neu erzeugt (nicht retuschiert)
python3 $G --prompt-file $S/prompt-v2.txt \
  --ref $S/ref/christina.png --ref $S/ref/sarah.png --ref $S/ref/sophie.png \
  --ref $S/ref/hero-wide.png --ref $S/out/final-hero-wide-v1.png \
  --out $S/out/n1 --n 3 --aspect 16:9 --size 2K --model gemini-3-pro-image-preview

# 2 — Christina, Kapitel 10, mit dem strengen Gesichtsblock
python3 $G --image $P/IMG_3146.jpeg --prompt-file $P/p-christina.txt \
  --out $P/out/p-christina-v3 --n 3 --aspect 4:5 --size 2K --model gemini-3-pro-image-preview

# 3 — Christina, Kapitel 05
python3 $G --image $P/IMG_3145.jpeg --prompt-file $P/e-christina.txt \
  --out $P/out/e-christina-v2 --n 3 --aspect 4:5 --size 2K --model gemini-3-pro-image-preview
```

Ueber fal statt Gemini: derselbe Prompt, `falimg.py --model nbpro`, Referenzen
in derselben Reihenfolge.

Danach pruefen (Arme zaehlen, Fuesse im Bild, kein Hut, Locken), Sieger nach
`final-hero-wide.png` kopieren und backen:
`python3 tools/build-assets.py hero-wide hero-tall og-image christina exp-christina`.

Das Hochformat braucht denselben Durchgang mit `--aspect 3:4` — quer und hoch
sind zwei Bilder, eines traegt die Korrektur nicht ins andere.

## Nachbesserung, 16.08.2026

Die Kundin hat drei Dinge angestrichen: die drei Frauen lesen sich wie drei
verschiedene Maßstäbe in einem Bild, die Haare der Mittleren stimmen nicht, und
der Strohhut in der Hand der Linken soll weg. `fix2.txt` erledigt genau diese
drei — wieder als **Retusche des freigegebenen Kaders**, mit
`ref/sarah.png` als Haarvorlage:

```bash
S=~/Documents/entsolve/GIT/B3-Retreats/tools/hero-drei
python3 ~/Documents/entsolve/GIT/polarholz-3drenders/generate.py \
  --image $S/out/final-hero-wide.png --prompt-file $S/fix2.txt \
  --ref $S/ref/sarah.png --out $S/out/v3 --n 3 --aspect 16:9 --size 2K \
  --model gemini-3-pro-image-preview
```

Endfassungen: `out/v3_1.png` (quer) und `out/v3tall_1.png` (hoch), kopiert nach
`final-hero-wide.png` / `final-hero-tall.png`. Der Stand vom 15.08. liegt
daneben als `final-hero-wide-v1.png` und `final-hero-tall-v1.png`.

Drei Beobachtungen aus diesem Durchgang:

* **Quer und hoch müssen getrennt nachgebessert werden.** Es sind zwei erzeugte
  Bilder (siehe unten), also trägt eine Korrektur am Querformat nicht ins
  Hochformat. Wer nur eines anfasst, hat den Hut am Telefon noch drin.
* **`v3_2` fiel durch**: der Hut war zwar weg, aber an der Hand der Linken hing
  ein schmaler Riemen weiter herunter — der Rest des Bandes. Beim Entfernen von
  Gegenständen immer die Stelle prüfen, an der sie die Hand berührt haben, nicht
  nur den Platz, an dem sie lagen.
* **Der Ausschnitt wandert.** Trotz „do not re-frame“ steht die Gruppe im
  Querformat jetzt näher an der Kamera als vorher, die Füße sind angeschnitten.
  Das Hochformat hat den Kader gehalten. Wenn beide Formate zusammenpassen
  müssen, ist das die Stelle zum Hinsehen.

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
