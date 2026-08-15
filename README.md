# B³ Retreats — лендинг (локальный прототип)

Статичный сайт: HTML + CSS + один файл JS. Без сборки, без npm, без внешних запросов.
Язык страницы — немецкий (`lang="de"`), целевая аудитория — DACH.

```
index.html            лендинг
impressum.html        ┐
datenschutz.html      ├ генерируются из tools/content/ — руками не править
agb.html              ┘
assets/
  css/style.css       вся вёрстка
  css/fonts.css       @font-face (генерируется)
  fonts/*.woff2       Cormorant Garamond + Manrope, локально (297 КБ)
  js/main.js          ссылка на оплату, меню, кнопка «наверх», появление блоков
  img/*.webp          23 обработанных фото (5,5 МБ)
tools/                скрипты пересборки ассетов
materials- in/        исходники заказчицы (в сайт не входят)
```

## Запуск

```bash
cd /Users/llashutko/Documents/entsolve/GIT/B3-Retreats
python3 -c "from http.server import SimpleHTTPRequestHandler, ThreadingHTTPServer; ThreadingHTTPServer(('127.0.0.1', 8788), SimpleHTTPRequestHandler).serve_forever()"
```

Открыть <http://localhost:8788/>.

Именно `ThreadingHTTPServer`, а не `python3 -m http.server`: однопоточный сервер
роняет часть параллельных запросов за картинками, и половина галереи не грузится.
Открывать `index.html` двойным кликом (`file://`) тоже можно, но шрифты в этом
режиме заблокирует CORS.

## Ссылка на оплату

Одна строка — [assets/js/main.js:11](assets/js/main.js#L11):

```js
const BOOKING_URL = 'https://tentary.com/HIER-DEINEN-LINK-EINSETZEN';
```

Подставить свою ссылку Tentary — её подхватят все 8 кнопок на странице
(`target="_blank"`, `rel="noopener noreferrer"`). Пока стоит плейсхолдер, кнопки
просто скроллят к блоку бронирования, чтобы не вести в никуда.

Нужны разные ссылки на Shared House и Friends Special — рядом в комментарии
лежит вариант с объектом; тогда на кнопке добавить `data-booking="shared"`
или `data-booking="friends"`.

## Пересборка ассетов

```bash
python3 tools/build-fonts.py    # шрифты с Google Fonts → локальные woff2 + fonts.css
python3 tools/build-assets.py   # фото из «materials- in» → assets/img/*.webp
python3 tools/build-legal.py    # tools/content/*.{md,txt} → impressum/datenschutz/agb
```

`build-assets.py` — не просто ресайз. Фото сняты в жёсткий полдень, небо
кобальтовое, вид «объявление о недвижимости». Скрипт по очереди: применяет
EXIF-поворот (26 файлов лежали боком), убирает пересветы, греет баланс белого,
выборочно гасит синеву неба (`S ×0.42`), уводит зелень в оливу, поднимает чёрную
точку до тёплой, добавляет зерно и тёплую виньетку. Грейд запекается в файлы —
глобальный CSS `saturate()` вместе с небом убил бы золото полей и телесные тона.

Правки кропов — в списке `JOBS` внизу скрипта.

## Что осталось сделать заказчице

Правовые тексты помечены плашками «Bitte ergänzen» прямо на страницах,
полный список — в [tools/content/OFFENE-PUNKTE.md](tools/content/OFFENE-PUNKTE.md).
Блокирующее до публикации:

- **E-Mail** — без неё Impressum не соответствует § 5 Abs. 1 Nr. 2 DDG. Нужна в трёх местах.
- **USt-IdNr.** или отметка о § 19 UStG (Kleinunternehmerregelung).
- **Хостер** и договор AVV — для раздела Datenschutz.
- **Роль Tentary**: Auftragsverarbeiter или Merchant of Record — от этого зависит формулировка.
- **Bildnachweise** — авторство фотографий.

## Чего на странице сознательно нет

Ни одной тени, ни `backdrop-filter`, ни `rgba(0,0,0,…)`, ни эмодзи, ни готовых
иконок. Скругления только два: окружность (медальон, фазы луны, кнопка «наверх») и
арка для портретов и интерьеров. Разделяют блоки три вещи — волосяная линия,
смена тона фона и воздух.

Внешних запросов нет вообще: шрифты локальные, карта — контурный SVG, никаких
Google Maps, ленты Instagram и виджетов. Поэтому странице не нужен cookie-баннер.

## Дефицит фотографий

Не закрывать стоком — сломается вся тональность. Не хватает:

- кадра под четвёртую Experience Кристины (блокнот, почерк, ноутбук);
- фото апартамента Friends Special — сейчас там снимок общей зоны;
- ночного кадра под блок про новолуние;
- хотя бы одного кадра людей на практике.

Портреты подписаны по догадке: `Sophie1/2.png` → Sophie (имя в файле),
рыжеволосая (`72-…`, `81-…`) → Sarah, в атласном платье (`IMG_3145/46`) → Christina.
**Проверить перед публикацией.**
