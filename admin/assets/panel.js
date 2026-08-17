/* B³ Redaktion — das Wenige, was das Panel an JavaScript braucht.
   Kein Inline-Skript: die CSP des Panels verbietet es (siehe config.php). */
document.addEventListener('click', function (ev) {
  var el = ev.target.closest('[data-confirm]');
  if (el && !window.confirm(el.getAttribute('data-confirm'))) ev.preventDefault();
});
/* Textfelder wachsen mit dem Inhalt — 240 Felder, viele davon mehrzeilig. */
document.querySelectorAll('textarea').forEach(function (t) {
  var wachsen = function () { t.style.height = 'auto'; t.style.height = (t.scrollHeight + 2) + 'px'; };
  t.addEventListener('input', wachsen); wachsen();
});
