<!-- Preloader (styles: partials/preloader-head.php) -->
<div id="preloader" aria-hidden="true">
  <div class="pl-mark">
    <img class="pl-logo" src="assets/images/brand/preloader-logo.webp" alt="" width="900" height="261" fetchpriority="high">
    <div class="pl-flyx"><div class="pl-flyy"><img class="pl-fly" src="assets/images/brand/preloader-dragonfly.png" alt="" width="101" height="106"></div></div>
  </div>
</div>
<script>
/* Self-contained so the page can never stay covered, even if app.js fails to load. */
(function () {
  var root = document.documentElement;
  var el = document.getElementById('preloader');
  if (!root.classList.contains('fl-loading')) { el.remove(); return; }

  var MIN = 1000;  // the logo stays on screen for at least this long (ms)
  var MAX = 1500;  // never hold a slow page longer than this (ms)
  var started = 0, left = false;

  function start() {
    if (started) return;
    started = performance.now();
    el.classList.add('is-ready');
  }
  function leave() {
    if (left) return;
    left = true;
    el.classList.add('is-leaving');
    root.classList.remove('fl-hold');
    setTimeout(function () { root.classList.remove('fl-loading'); el.remove(); }, 800);
  }

  // Start the animation only once the logo can actually be drawn
  var logo = el.querySelector('.pl-logo');
  if (logo.decode) logo.decode().then(start, start); else start();
  setTimeout(start, 500);

  // Wait only for what is seen first: the HTML, the fonts and the first hero photo.
  // Everything else keeps loading behind the page, so a first visit isn't held back.
  var ready = new Promise(function (done) {
    function check() {
      var hero = document.querySelector('img.hero-img[fetchpriority="high"]');
      if (!hero || hero.complete) { done(); return; }
      hero.addEventListener('load', done, { once: true });
      hero.addEventListener('error', done, { once: true });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', check, { once: true });
    else check();
  });
  var fonts = (document.fonts && document.fonts.ready) || Promise.resolve();
  var cap = new Promise(function (done) { setTimeout(done, MAX); });

  Promise.race([Promise.all([ready, fonts]), cap]).then(function () {
    var shown = started ? performance.now() - started : 0;
    setTimeout(leave, Math.max(0, MIN - shown));
  });
})();
</script>
