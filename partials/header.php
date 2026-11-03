<?php
/** @var string $page_title  @var string $page_desc */
$page_title = $page_title ?? 'Faelynn | Premium Handcrafted Clothing for Men & Women';
$page_desc  = $page_desc ?? 'Faelynn — love for nature. Premium handcrafted, breathable cotton dresses, co-ord sets, nightwear and relaxed shirts, made by Indian artisans. Free delivery & easy returns.';
$current    = fl_current_page();
// Logo preloader: browsing pages only. Shopping steps (product, cart, checkout,
// account, wishlist, track order) must open straight away.
$show_preloader = in_array($current, ['index', 'shop', 'about', 'custom-order', 'journal', 'contact', 'faq', 'policy'], true);
$nav = [
    ['label' => 'Women', 'url' => fl_category_url('women'), 'mega' => 'women'],
    ['label' => 'Men', 'url' => fl_category_url('men'), 'mega' => 'men'],
    ['label' => 'New Arrivals', 'url' => fl_url('shop', ['sort' => 'new'])],
    ['label' => 'Custom Order', 'url' => fl_url('custom-order')],
    ['label' => 'Our Story', 'url' => fl_url('about')],
    ['label' => 'Journal', 'url' => fl_url('journal')],
];
$mega = [
    'women' => ['cats' => ['dresses-women', 'co-ord-set-women', 'night-dress-women'], 'feature' => fl_pick(['haizea-oversized-co-ords', 'fae-dress'])],
    'men'   => ['cats' => ['shirt-men', 'trouser-men', 'shorts-men'], 'feature' => fl_pick(['walden-shirt', 'rio'])],
];
?><!doctype html>
<html lang="en-IN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($page_title) ?></title>
  <meta name="description" content="<?= e($page_desc) ?>">
  <meta name="theme-color" content="#1C241E">
  <meta property="og:title" content="<?= e($page_title) ?>">
  <meta property="og:description" content="<?= e($page_desc) ?>">
  <meta property="og:image" content="assets/images/brand/og-image.jpg">
  <link rel="icon" type="image/png" href="assets/images/brand/favicon.png">
  <link rel="apple-touch-icon" href="assets/images/brand/apple-touch-icon.png">
  <?php if ($show_preloader): ?>
    <?php fl_partial('preloader-head'); ?>
    <link rel="preload" as="image" href="assets/images/brand/preloader-logo.webp" fetchpriority="high">
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <?php $fl_fonts = 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Jost:wght@300;400;500;600&display=swap'; ?>
  <?php if ($show_preloader): /* the preloader covers the font swap, so fonts needn't block first paint */ ?>
    <link rel="preload" as="style" href="<?= e($fl_fonts) ?>" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= e($fl_fonts) ?>"></noscript>
  <?php else: ?>
    <link rel="stylesheet" href="<?= e($fl_fonts) ?>">
  <?php endif; ?>
  <link rel="stylesheet" href="<?= fl_asset('assets/vendor/swiper/swiper-bundle.min.css') ?>">
  <link rel="stylesheet" href="<?= fl_asset('assets/css/app.css') ?>">
</head>
<body class="<?= e($body_class ?? '') ?>" data-page="<?= e($current) ?>"<?= FL_STATIC ? ' data-static="1"' : '' ?>>
<?php if ($show_preloader) fl_partial('preloader'); ?>
<a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-forest focus:px-4 focus:py-2 focus:text-ivory">Skip to content</a>

<!-- Announcement bar -->
<div class="relative overflow-hidden bg-forest text-ivory">
  <div class="flex w-max animate-marquee py-2 hover:[animation-play-state:paused]">
    <?php for ($i = 0; $i < 2; $i++): ?>
      <ul class="flex shrink-0 items-center" <?= $i ? 'aria-hidden="true"' : '' ?>>
        <?php foreach (FL_ANNOUNCEMENTS as $msg): ?>
          <li class="flex items-center gap-10 px-5 text-[11px] font-medium uppercase tracking-[0.2em]">
            <span><?= e($msg) ?></span>
            <img src="assets/images/brand/dragonfly-light.png" alt="" class="h-3.5 w-auto opacity-70">
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endfor; ?>
  </div>
</div>

<!-- Header -->
<header id="site-header" class="sticky top-0 z-50 border-b border-line/70 bg-ivory/95 backdrop-blur supports-[backdrop-filter]:bg-ivory/85 transition-shadow">
  <div class="container flex h-16 items-center gap-4 lg:h-20">
    <button type="button" class="-ml-2 grid h-10 w-10 place-items-center rounded-full text-forest lg:hidden" data-open="mobile-nav" aria-label="Open menu">
      <?= fl_icon('menu', 'h-6 w-6') ?>
    </button>

    <a href="<?= fl_url() ?>" class="shrink-0" aria-label="Faelynn home">
      <img src="assets/images/brand/faelynn-logo.png" alt="Faelynn — love for nature" class="h-9 w-auto lg:h-11" width="1779" height="515">
    </a>

    <nav class="ml-6 hidden h-full items-stretch lg:flex xl:ml-10" aria-label="Main">
      <?php foreach ($nav as $item): ?>
        <div class="<?= isset($item['mega']) ? 'has-mega' : '' ?> group flex items-stretch">
          <a href="<?= e($item['url']) ?>" class="relative flex items-center px-3 text-[13px] font-medium uppercase tracking-[0.14em] text-forest after:absolute after:inset-x-3 after:bottom-5 after:h-px after:origin-left after:scale-x-0 after:bg-forest after:transition-transform group-hover:after:scale-x-100 xl:px-4 xl:after:inset-x-4">
            <?= e($item['label']) ?>
          </a>
          <?php if (isset($item['mega'])): $m = $mega[$item['mega']]; ?>
            <div class="mega invisible absolute inset-x-0 top-full -translate-y-2 border-t border-line bg-ivory opacity-0 shadow-soft transition duration-300">
              <div class="container grid grid-cols-12 gap-8 py-10">
                <div class="col-span-3">
                  <p class="eyebrow mb-4">Shop <?= e($item['label']) ?></p>
                  <ul class="space-y-3">
                    <li><a href="<?= e($item['url']) ?>" class="font-serif text-2xl text-forest hover:text-moss">All <?= e($item['label']) ?> <span class="text-sm text-ink-muted">(<?= fl_category_count($item['mega']) ?>)</span></a></li>
                    <?php foreach ($m['cats'] as $c): ?>
                      <li><a href="<?= fl_category_url($c) ?>" class="font-serif text-2xl text-forest hover:text-moss"><?= e(FL_CATEGORIES[$c]['label']) ?> <span class="text-sm text-ink-muted">(<?= fl_category_count($c) ?>)</span></a></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
                <div class="col-span-3 border-l border-line pl-8">
                  <p class="eyebrow mb-4">Explore</p>
                  <ul class="space-y-2.5 text-sm text-ink-soft">
                    <li><a class="hover:text-forest" href="<?= fl_url('shop', ['cat' => $item['mega'], 'sort' => 'new']) ?>">New Arrivals</a></li>
                    <li><a class="hover:text-forest" href="<?= fl_url('shop', ['cat' => $item['mega'], 'max' => 2500]) ?>">Under <?= fl_price(2500) ?></a></li>
                    <li><a class="hover:text-forest" href="<?= fl_url('custom-order') ?>">Custom Orders</a></li>
                    <li><a class="hover:text-forest" href="<?= fl_url('custom-order') ?>#size-chart">Size Guide</a></li>
                    <li><a class="hover:text-forest" href="<?= fl_url('policy', ['type' => 'wash-care']) ?>">Wash Care</a></li>
                  </ul>
                </div>
                <div class="col-span-6 grid grid-cols-2 gap-5">
                  <?php foreach ($m['feature'] as $fp): ?>
                    <a href="<?= fl_product_url($fp) ?>" class="group/f relative block overflow-hidden rounded-2xl bg-cream">
                      <img src="<?= e($fp['images'][0]['src']) ?>" alt="<?= e($fp['short_name']) ?>" class="aspect-[4/3] w-full object-cover object-top transition duration-700 group-hover/f:scale-105" loading="lazy">
                      <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-forest/70 to-transparent p-4 text-ivory">
                        <p class="font-serif text-xl"><?= e($fp['short_name']) ?></p>
                        <p class="text-xs uppercase tracking-widest"><?= fl_price($fp['price']) ?></p>
                      </div>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </nav>

    <div class="ml-auto flex items-center gap-1 sm:gap-2">
      <button type="button" data-open="search" class="hidden h-10 w-56 items-center gap-2 rounded-full border border-line bg-white px-4 text-sm text-ink-muted transition hover:border-forest/40 xl:flex">
        <?= fl_icon('search', 'h-4 w-4') ?> Search dresses, shirts…
      </button>
      <button type="button" data-open="search" class="grid h-10 w-10 place-items-center rounded-full text-forest hover:bg-cream xl:hidden" aria-label="Search">
        <?= fl_icon('search') ?>
      </button>
      <a href="<?= fl_url('wishlist') ?>" class="relative hidden h-10 w-10 place-items-center rounded-full text-forest hover:bg-cream sm:grid" aria-label="Wishlist">
        <?= fl_icon('heart') ?>
        <span data-wishlist-count class="absolute right-0.5 top-0.5 hidden h-4 min-w-[16px] place-items-center rounded-full bg-clay px-1 text-[10px] font-semibold text-white"></span>
      </a>
      <a href="<?= fl_url('account') ?>" class="hidden h-10 w-10 place-items-center rounded-full text-forest hover:bg-cream sm:grid" aria-label="Account">
        <?= fl_icon('user') ?>
      </a>
      <button type="button" data-open="cart" class="relative grid h-10 w-10 place-items-center rounded-full text-forest hover:bg-cream" aria-label="Open bag">
        <?= fl_icon('bag') ?>
        <span data-cart-count class="absolute right-0.5 top-0.5 hidden h-4 min-w-[16px] place-items-center rounded-full bg-forest px-1 text-[10px] font-semibold text-ivory"></span>
      </button>
    </div>
  </div>
</header>

<!-- Mobile navigation -->
<div class="overlay hidden" data-overlay="mobile-nav"></div>
<aside id="mobile-nav" class="drawer left-0 -translate-x-full" aria-label="Mobile menu" aria-hidden="true">
  <div class="flex items-center justify-between border-b border-line px-5 py-4">
    <img src="assets/images/brand/faelynn-logo.png" alt="Faelynn" class="h-8 w-auto">
    <button type="button" data-close class="grid h-10 w-10 place-items-center rounded-full hover:bg-cream" aria-label="Close menu"><?= fl_icon('close') ?></button>
  </div>
  <div class="flex-1 overflow-y-auto px-5 py-4">
    <?php foreach (['women', 'men'] as $g): ?>
      <details class="acc border-b border-line" <?= $g === 'women' ? 'open' : '' ?>>
        <summary><span class="font-serif text-2xl normal-case tracking-normal"><?= e(FL_CATEGORIES[$g]['label']) ?></span><span class="acc-icon"><?= fl_icon('plus', 'h-4 w-4') ?></span></summary>
        <ul class="space-y-3 pb-5 pl-1 text-ink-soft">
          <li><a href="<?= fl_category_url($g) ?>">Shop all <?= e(strtolower(FL_CATEGORIES[$g]['label'])) ?></a></li>
          <?php foreach ($mega[$g]['cats'] as $c): ?>
            <li><a href="<?= fl_category_url($c) ?>"><?= e(FL_CATEGORIES[$c]['label']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </details>
    <?php endforeach; ?>
    <ul class="divide-y divide-line">
      <?php foreach (array_slice($nav, 2) as $item): ?>
        <li><a href="<?= e($item['url']) ?>" class="block py-4 font-serif text-2xl text-forest"><?= e($item['label']) ?></a></li>
      <?php endforeach; ?>
    </ul>
    <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
      <a href="<?= fl_url('account') ?>" class="flex items-center gap-2 rounded-xl bg-cream px-4 py-3"><?= fl_icon('user', 'h-4 w-4') ?> Account</a>
      <a href="<?= fl_url('wishlist') ?>" class="flex items-center gap-2 rounded-xl bg-cream px-4 py-3"><?= fl_icon('heart', 'h-4 w-4') ?> Wishlist</a>
      <a href="<?= fl_url('track-order') ?>" class="flex items-center gap-2 rounded-xl bg-cream px-4 py-3"><?= fl_icon('truck', 'h-4 w-4') ?> Track order</a>
      <a href="<?= fl_url('contact') ?>" class="flex items-center gap-2 rounded-xl bg-cream px-4 py-3"><?= fl_icon('phone', 'h-4 w-4') ?> Contact</a>
    </div>
  </div>
  <div class="border-t border-line px-5 py-4 text-xs text-ink-muted">
    Need help? <a href="tel:<?= FL_PHONE_RAW ?>" class="font-medium text-forest"><?= FL_PHONE ?></a> · <?= FL_HOURS ?>
  </div>
</aside>

<!-- Search overlay -->
<div class="overlay hidden" data-overlay="search"></div>
<div id="search" class="fixed inset-x-0 top-0 z-[70] -translate-y-full bg-ivory shadow-2xl transition-transform duration-500 ease-[cubic-bezier(.2,.8,.2,1)]" aria-hidden="true" role="dialog" aria-label="Search">
  <div class="container py-6 lg:py-10">
    <form action="<?= fl_url('shop') ?>" method="get" class="flex items-center gap-3 border-b-2 border-forest pb-3" role="search">
      <?= fl_icon('search', 'h-6 w-6 text-forest') ?>
      <input type="search" name="q" data-search-input autocomplete="off" placeholder="Search for dresses, co-ords, shirts…" class="flex-1 border-0 bg-transparent p-0 font-serif text-2xl text-forest placeholder:text-ink-muted/70 focus:ring-0 lg:text-4xl" aria-label="Search products">
      <button type="button" data-close class="grid h-10 w-10 place-items-center rounded-full hover:bg-cream" aria-label="Close search"><?= fl_icon('close') ?></button>
    </form>
    <div class="mt-5 flex flex-wrap items-center gap-2" data-search-suggest>
      <span class="mr-1 text-xs uppercase tracking-widest text-ink-muted">Popular:</span>
      <?php foreach (['Co-ord sets' => 'co-ord', 'Night dress' => 'night', 'Embroidered' => 'embroider', 'Floral' => 'floral', 'Men’s shirts' => 'shirt'] as $label => $q): ?>
        <a href="<?= fl_url('shop', ['q' => $q]) ?>" class="rounded-full border border-line bg-white px-4 py-1.5 text-sm text-forest hover:border-forest"><?= e($label) ?></a>
      <?php endforeach; ?>
    </div>
    <div data-search-results class="mt-6 grid max-h-[60vh] grid-cols-2 gap-4 overflow-y-auto sm:grid-cols-3 lg:grid-cols-6"></div>
  </div>
</div>

<main id="main">
