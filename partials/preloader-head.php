<?php
/**
 * Preloader, <head> part. Inline on purpose: it must paint on the very first frame,
 * without waiting for app.css. The markup and exit logic live in partials/preloader.php.
 *
 * Without JavaScript the fl-loading class is never added, so the preloader never shows.
 * It is also skipped when only the query string changes (shop filters / sort), so
 * filtering doesn't replay the logo on every click.
 */
?>
<script>
(function () {
  var skip = false;
  try {
    var r = document.referrer && new URL(document.referrer);
    skip = !!r && r.origin === location.origin && r.pathname === location.pathname && r.search !== location.search;
  } catch (e) {}
  if (!skip) document.documentElement.classList.add('fl-loading', 'fl-hold');
})();
</script>
<style>
  #preloader { display: none; }
  .fl-loading #preloader { position: fixed; inset: 0; z-index: 9999; display: grid; place-items: center; background: #FBF8F2; transition: transform .75s cubic-bezier(.76, 0, .24, 1); will-change: transform; }
  /* curved lip on the lifting curtain */
  .fl-loading #preloader::after { content: ""; position: absolute; left: -10%; right: -10%; top: calc(100% - 7vh); height: 14vh; border-radius: 50%; background: #FBF8F2; }
  .pl-mark { position: relative; width: clamp(230px, 32vw, 420px); transition: transform .6s cubic-bezier(.76, 0, .24, 1), opacity .35s ease; }
  /* the wordmark is "inked" in from left to right with a soft edge */
  .pl-logo { display: block; width: 100%; height: auto;
    -webkit-mask: linear-gradient(100deg, #000 42%, transparent 58%) 100% 0 / 320% 100% no-repeat;
            mask: linear-gradient(100deg, #000 42%, transparent 58%) 100% 0 / 320% 100% no-repeat;
    animation: plInk .85s cubic-bezier(.45, .05, .25, 1) .05s both; }
  /* the dragonfly flies along the ink and lands on its place in the logo:
     separate X and Y easings on two wrappers give a natural curved path */
  .pl-flyx, .pl-flyy { position: absolute; inset: 0; }
  .pl-flyx { animation: plFlyX .95s cubic-bezier(.3, .6, .25, 1) both; }
  .pl-flyy { animation: plFlyY .95s ease-in-out both; }
  .pl-fly { position: absolute; left: 93.873%; top: 33.592%; width: 5.677%; height: auto; animation: plWing .11s ease-in-out 8 alternate both; }
  #preloader:not(.is-ready) .pl-logo, #preloader:not(.is-ready) .pl-flyx, #preloader:not(.is-ready) .pl-flyy, #preloader:not(.is-ready) .pl-fly { animation-play-state: paused; }
  #preloader.is-leaving { transform: translateY(calc(-100% - 8vh)); pointer-events: none; }
  #preloader.is-leaving .pl-mark { transform: translateY(-36px); opacity: 0; }
  /* hero entrance waits for the curtain, so it plays as the page is revealed */
  .fl-hold .hero-anim > *, .fl-hold .hero-img { animation-play-state: paused !important; }
  @keyframes plInk { from { -webkit-mask-position: 100% 0; mask-position: 100% 0; } to { -webkit-mask-position: 0 0; mask-position: 0 0; } }
  @keyframes plFlyX { from { transform: translateX(-96%); opacity: 0; } 18% { opacity: 1; } to { transform: none; opacity: 1; } }
  @keyframes plFlyY { 0% { transform: translateY(55%); } 48% { transform: translateY(-42%); } 100% { transform: none; } }
  @keyframes plWing { from { transform: rotate(-8deg); } to { transform: rotate(8deg); } }
  @media (prefers-reduced-motion: reduce) {
    .pl-logo, .pl-flyx, .pl-flyy, .pl-fly { animation: none; -webkit-mask: none; mask: none; }
    .fl-loading #preloader { transition: opacity .3s ease; }
    #preloader.is-leaving { transform: none; opacity: 0; }
  }
</style>
