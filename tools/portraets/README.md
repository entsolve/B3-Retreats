# Die Porträts der drei — ein Ort, ein Licht

Sophie, Sarah und Christina hatten je ein eigenes Foto auf der Seite geliefert:
ein Selfie im Wohnzimmer, eine Aufnahme vor hellem Vorhang und eine an einer
Betontreppe in der Stadt. Drei Fotografen, drei Tageszeiten, drei Farbwelten.
Nebeneinander im Kapitel „Die drei Frauen hinter B³“ sah das aus wie drei
Menschen, die einander nie begegnet sind.

Seit 16.08.2026 stehen alle drei am selben Feldrand oberhalb des Anwesens, im
selben späten Nachmittagslicht wie das Hero-Bild. Dazu je ein zweites Bild für
ihre Experience im Kapitel 05.

## Der Grundsatz: sie bleiben sie

Kein neu erfundener Mensch. Vorlage ist immer **ihr eigenes Foto**, und der
Prompt ist als Retusche formuliert, nicht als neue Szene: Gesicht, Haare,
Schmuck, Kleidung, Haltung und Bildausschnitt bleiben, ausgetauscht werden nur
Hintergrund und Licht. Der Block `IDENTITY — untouchable` steht in jedem Prompt
zuoberst und zählt einzeln auf, was nicht angefasst werden darf — bis hin zu
Sommersprossen, Falten und Alter. Ohne diese Aufzählung verjüngt und glättet das
Modell zuverlässig, und dann ist es nicht mehr sie.

## Ausführen

```bash
G=~/Documents/entsolve/GIT/polarholz-3drenders/generate.py   # dort liegt der Schlüssel
P=~/Documents/entsolve/GIT/B3-Retreats/tools/portraets
python3 $G --image "$P/Sophie2.png" --prompt-file $P/p-sophie.txt \
  --out $P/out/p-sophie --n 2 --aspect 4:5 --size 2K --model gemini-3-pro-image-preview
```

| Ausgabe | Vorlage (Kundenmaterial) | Prompt | steht auf der Seite als |
|---|---|---|---|
| `out/p-sophie_1.png`    | `Sophie2.png`       | `p-sophie.txt`    | `sophie.webp` — Kapitel 10 |
| `out/p-sarah_1.png`     | `81 - 261A9565.jpg` | `p-sarah.txt`     | `sarah.webp` — Kapitel 10 |
| `out/p-christina_1.png` | `IMG_3146.jpeg`     | `p-christina.txt` | `christina.webp` — Kapitel 10 |
| `out/e-sarah.png`       | `72 - 261A9399.jpg` | `e-sarah.txt`     | `exp-sarah.webp` — Kapitel 05 |
| `out/e-sophie.png`      | `Sophie1.png`       | `e-sophie.txt`    | `exp-sophie.webp` — Kapitel 05 |
| `out/e-christina.png`   | `IMG_3145.jpeg`     | `e-christina.txt` | `exp-christina.webp` — Kapitel 05 |

Backen mit dem üblichen Weg:

```bash
python3 tools/build-assets.py sophie sarah christina exp-sarah exp-sophie exp-christina
```

Profil für alle sechs ist `portrait`. `portrait_beton` wird nicht mehr gebraucht:
es war nur für Christinas Betontreppe da, und die ist weg.

## Zwei Bilder pro Person, zwei verschiedene Vorlagen

Kapitel 05 (Experience) und Kapitel 10 (Über uns) liegen auf derselben Seite.
Zweimal dasselbe Gesicht in derselben Pose fällt auf. Deshalb kommt jedes Paar
aus **zwei verschiedenen Originalfotos** derselben Frau, nicht aus zwei
Ausschnitten eines Bildes — dann unterscheiden sich Haltung, Kleidung und Blick
von selbst, ohne dass der Prompt die Person umstellen muss.

Bei Sarah passt das Paar zusätzlich zum Text: die Hände vor der Brust aus
`72 - 261A9399.jpg` stehen bei „Yin Yoga & Breathwork“, die ruhige Aufnahme mit
dem Grashalm im Kapitel „Über uns“.

## Was am Prompt entscheidend war

* **Handrail-Problem.** Christina lehnte auf beiden Vorlagen an einem
  Treppengeländer. Fällt das Geländer weg, hängen ihre Arme im Nichts. Der
  Prompt schreibt deshalb ausdrücklich vor, was die Arme stattdessen tun
  (locker herunter, Hand offen, Fingerspitzen durch die trockenen Gräser) und
  verlangt abzählbare Anatomie. Ohne diesen Absatz endet mindestens eine Hand
  als Klumpen.
* **Was in der Hand liegt, muss ersetzt werden, nicht gelöscht.** Sarah hielt
  auf `81 - 261A9565.jpg` eine Blüte. Statt sie zu entfernen, wird sie zur
  Samenrispe eines Wildgrases — dieselbe Geste, dieselbe Handhaltung, neuer Ort.
* **Relight, nicht Freisteller.** Jeder Prompt verlangt, den ganzen Körper neu
  zu beleuchten und nennt die Richtung (tiefe Sonne, 25°, von hinten rechts).
  Fehlt das, klebt die Person mit ihrem alten Innenraumlicht vor dem Feld, und
  genau das liest man sofort als Montage.
* **Die verbotene Liste.** `no beauty filter`, `no changed face`,
  `no added make-up` stehen nicht zur Zierde da. Bei den ersten Durchläufen ohne
  sie kam eine geglättete, zehn Jahre jüngere Version zurück.

## Prüfen, bevor etwas weitergeht

1. Gesicht gegen die Vorlage halten — ist es noch dieselbe Person?
2. Hände zählen und bis zur Schulter verfolgen.
3. Umriss auf Halo absuchen (Freisteller-Kante an Haar und Schulter).
4. Alle sechs nebeneinander legen: gleiche Sonne, gleiche Farbe, gleiche Höhe
   des Horizonts. Wenn eins herausfällt, ist es meist das Licht, nicht die Farbe.

## Offen

`abschluss.webp` zeigt dieselben drei in der Dämmerung und stammt noch aus dem
Stand vom 15.08.2026 — dort hat Sarah die alten glatten Wellen, nicht die
Locken. Erst anfassen, wenn es sein muss: die Umarmung dort ist Arm für Arm
geprüft (siehe `../hero-drei/README.md`), eine Neugenerierung riskiert genau
diese Stelle.
