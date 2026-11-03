<?php
require __DIR__ . '/inc/bootstrap.php';
$page_title = 'Faelynn | Premium Handcrafted Clothing for Men & Women';
fl_partial('header', compact('page_title'));

/** Data for the interactive "New Arrivals" showcase */
$na = [];
foreach (['women' => FL_GROUPS['new_women'], 'men' => FL_GROUPS['new_men']] as $g => $slugs) {
    foreach (fl_pick($slugs) as $p) {
        $specs = $p['quick_specs'] ?: $p['specs'];
        $na[$g][] = [
            'slug' => $p['slug'], 'name' => $p['short_name'], 'cat' => fl_category_label($p), 'price' => fl_price($p['price']),
            'url' => fl_product_url($p), 'images' => array_column($p['images'], 'src'),
            'tagline' => $p['tagline'] ?: ($p['intro'][0] ?? ''),
            'specs' => array_slice($specs, 0, 2),
        ];
    }
}
$trend = ['co-ord-set-women', 'dresses-women', 'shirt-men', 'night-dress-women', 'trouser-men'];
?>

<!-- ============ HERO ============ -->
<?php
/* Hero slides — photos: Unsplash License (credits in README). In WordPress → ACF repeater / Customizer. */
$hero = [
    [
        'img' => 'assets/images/hero/hero-women.jpg', 'pos' => 'object-[74%_center] lg:object-[center_30%]', 'theme' => 'light',
        'alt' => 'Woman relaxing in soft neutral cotton loungewear',
        'eyebrow' => 'Premium Cotton Comfortwear', 't1' => 'Soft as a', 't2' => 'slow Sunday',
        'text' => 'Breathable, skin-friendly styles crafted for everyday comfort — hand-finished by Indian artisans.',
        'cta' => ['Shop Women', fl_category_url('women')], 'cta2' => ['Explore Co-ords', fl_category_url('co-ord-set-women')],
        'feature' => 'haizea-oversized-co-ords',
    ],
    [
        'img' => 'assets/images/hero/hero-men.jpg', 'pos' => 'object-[70%_center] lg:object-center', 'theme' => 'dark',
        'alt' => 'Man in a relaxed brown cotton shirt by a lake',
        'eyebrow' => 'The Men’s Edit', 't1' => 'Easy cotton', 't2' => 'for long summers',
        'text' => 'Super-relaxed shirts with coconut-shell buttons, paired with easy elasticated trousers.',
        'cta' => ['Shop Men', fl_category_url('men')], 'cta2' => ['View Shirts', fl_category_url('shirt-men')],
        'feature' => 'ridge-coat-style-shirt',
    ],
    [
        'img' => 'assets/images/hero/hero-handcrafted.jpg', 'pos' => 'object-[56%_center] lg:object-[center_35%]', 'theme' => 'dark',
        'alt' => 'Woman in a hand-embroidered white cotton dress',
        'eyebrow' => 'Handcrafted in India', 't1' => 'Embroidered', 't2' => 'by hand',
        'text' => 'Each piece carries the care of Indian artisans — stories of nature woven into every thread.',
        'cta' => ['Shop Dresses', fl_category_url('dresses-women')], 'cta2' => ['Our Story', fl_url('about')],
        'feature' => 'fae-dress',
    ],
];
?>
<section class="relative" aria-label="Featured collections">
  <div class="swiper hero-swiper h-[80svh] min-h-[560px] lg:h-[calc(100svh-7.5rem)] lg:max-h-[820px]">
    <div class="swiper-wrapper">
      <?php foreach ($hero as $i => $s):
          $light = $s['theme'] === 'light';
          $fp = fl_product($s['feature']); ?>
        <div class="swiper-slide relative overflow-hidden <?= $light ? 'bg-ivory' : 'bg-forest' ?>">
          <img src="<?= e($s['img']) ?>" alt="<?= e($s['alt']) ?>" class="hero-img absolute inset-0 h-full w-full object-cover <?= e($s['pos']) ?>" <?= $i ? 'loading="lazy"' : 'fetchpriority="high"' ?>>
          <?php if ($light): ?>
            <div class="absolute inset-0 bg-gradient-to-t from-ivory via-ivory/75 to-transparent lg:bg-gradient-to-r lg:from-ivory/90 lg:via-ivory/40 lg:to-transparent"></div>
          <?php else: ?>
            <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/40 to-transparent lg:bg-gradient-to-r lg:from-forest/80 lg:via-forest/35 lg:to-transparent"></div>
          <?php endif; ?>
          <div class="container relative flex h-full items-end pb-28 lg:items-center lg:pb-10">
            <div class="hero-anim max-w-xl <?= $light ? 'text-forest' : 'text-ivory' ?>">
              <p class="flex items-center gap-3 text-[11px] font-medium uppercase tracking-widest2 <?= $light ? 'text-moss' : 'text-sand' ?>">
                <span class="h-px w-8 <?= $light ? 'bg-moss' : 'bg-sand' ?>"></span><?= e($s['eyebrow']) ?>
              </p>
              <<?= $i ? 'h2' : 'h1' ?> class="mt-4 font-serif text-5xl font-medium leading-[0.95] sm:text-6xl lg:text-[92px]"><?= e($s['t1']) ?><br><em class="<?= $light ? 'text-moss' : 'text-sand' ?>"><?= e($s['t2']) ?></em></<?= $i ? 'h2' : 'h1' ?>>
              <p class="mt-5 max-w-md text-base lg:text-lg <?= $light ? 'text-ink-soft' : 'text-ivory/85' ?>"><?= e($s['text']) ?></p>
              <div class="mt-8 flex flex-wrap gap-3">
                <a href="<?= e($s['cta'][1]) ?>" class="<?= $light ? 'btn-primary' : 'btn-light' ?>"><?= e($s['cta'][0]) ?> <?= fl_icon('arrow-r', 'h-4 w-4') ?></a>
                <a href="<?= e($s['cta2'][1]) ?>" class="<?= $light ? 'btn-outline' : 'btn-outline-light' ?>"><?= e($s['cta2'][0]) ?></a>
              </div>
              <?php if ($fp): ?>
                <a href="<?= fl_product_url($fp) ?>" class="group mt-8 hidden w-max items-center gap-3 rounded-full border p-1.5 pr-5 backdrop-blur-md transition sm:flex <?= $light ? 'border-line bg-white/70 hover:bg-white' : 'border-ivory/20 bg-forest/30 hover:bg-forest/50' ?>">
                  <img src="<?= e($fp['images'][0]['src']) ?>" alt="" class="h-12 w-12 rounded-full object-cover object-top" loading="lazy">
                  <span class="leading-tight">
                    <span class="block text-[10px] font-semibold uppercase tracking-widest <?= $light ? 'text-moss' : 'text-sand' ?>">Featured</span>
                    <span class="block font-serif text-lg"><?= e($fp['short_name']) ?> <span class="font-sans text-sm opacity-75">· <?= fl_price($fp['price']) ?></span></span>
                  </span>
                  <?= fl_icon('arrow-r', 'ml-1 h-4 w-4 transition group-hover:translate-x-1') ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Hero controls -->
    <div class="pointer-events-none absolute inset-x-0 bottom-6 z-10">
      <div class="container flex items-center gap-3">
        <div class="pointer-events-auto flex items-center gap-4 rounded-full bg-ivory/85 px-5 py-2.5 shadow-card backdrop-blur">
          <span class="hero-counter font-serif text-xl text-forest"><span data-hero-current>01</span><span class="mx-1.5 text-sm opacity-50">/</span><span class="text-sm opacity-50"><?= sprintf('%02d', count($hero)) ?></span></span>
          <div class="h-0.5 w-20 overflow-hidden rounded-full bg-forest/15 sm:w-32"><div data-hero-bar class="h-full w-0 rounded-full bg-forest"></div></div>
        </div>
        <div class="pointer-events-auto flex gap-2">
          <button type="button" class="hero-prev grid h-11 w-11 place-items-center rounded-full bg-ivory/85 text-forest shadow-card backdrop-blur transition hover:bg-forest hover:text-ivory" aria-label="Previous slide"><?= fl_icon('chev-l', 'h-5 w-5') ?></button>
          <button type="button" class="hero-next grid h-11 w-11 place-items-center rounded-full bg-ivory/85 text-forest shadow-card backdrop-blur transition hover:bg-forest hover:text-ivory" aria-label="Next slide"><?= fl_icon('chev-r', 'h-5 w-5') ?></button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ USP strip ============ -->
<section class="border-b border-line bg-ivory">
  <div class="container no-scrollbar flex gap-8 overflow-x-auto py-5 lg:justify-between">
    <?php foreach (FL_USPS as $u): ?>
      <div class="flex shrink-0 items-center gap-3">
        <span class="text-moss"><?= fl_icon($u['icon'], 'h-6 w-6') ?></span>
        <div><p class="text-sm font-medium text-forest"><?= e($u['title']) ?></p><p class="text-xs text-ink-muted"><?= e($u['text']) ?></p></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ MOST LOVED ============ -->
<section class="overflow-x-clip py-16 lg:py-24">
  <div class="container">
    <div class="reveal mb-10 text-center">
      <h2 class="section-title">Most <em>Loved</em></h2>
      <p class="mt-2 text-sm text-ink-muted">The pieces our customers keep coming back to</p>
    </div>
    <div class="relative">
      <div class="swiper loved-swiper !overflow-visible lg:!overflow-hidden">
        <div class="swiper-wrapper">
          <?php foreach (fl_pick(FL_GROUPS['most_loved']) as $p): $detail = $p['images'][count($p['images']) - 1]['src']; ?>
            <div class="swiper-slide">
              <a href="<?= fl_product_url($p) ?>" class="group relative block aspect-[3/4.3] overflow-hidden rounded-3xl bg-[#F1EFEC]">
                <img src="<?= e($p['images'][1]['src'] ?? $p['images'][0]['src']) ?>" alt="<?= e($p['short_name']) ?>" class="h-full w-full object-cover object-top transition duration-700 group-hover:scale-105" loading="lazy">
                <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-forest/40 to-transparent"></div>
                <div class="absolute inset-x-3 bottom-3 flex items-center gap-3 rounded-full bg-ivory/95 p-1.5 pr-4 shadow-card backdrop-blur">
                  <img src="<?= e($detail) ?>" alt="" class="h-11 w-11 shrink-0 rounded-full object-cover" loading="lazy">
                  <div class="min-w-0 flex-1">
                    <p class="truncate font-serif text-base leading-tight text-forest"><?= e($p['short_name']) ?></p>
                    <p class="text-xs text-ink-soft"><?= fl_price($p['price']) ?></p>
                  </div>
                  <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-forest text-ivory transition group-hover:translate-x-0.5"><?= fl_icon('arrow-r', 'h-4 w-4') ?></span>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <button type="button" class="loved-prev nav-btn absolute -left-5 top-[45%] z-10 hidden lg:grid" aria-label="Previous"><?= fl_icon('chev-l', 'h-5 w-5') ?></button>
      <button type="button" class="loved-next nav-btn absolute -right-5 top-[45%] z-10 hidden lg:grid" aria-label="Next"><?= fl_icon('chev-r', 'h-5 w-5') ?></button>
    </div>
  </div>
</section>

<!-- ============ ON-TREND PICKS ============ -->
<section class="overflow-hidden bg-cream/70 py-16 lg:py-24">
  <div class="reveal mb-10 text-center">
    <h2 class="section-title">On-Trend <em>Picks</em></h2>
    <p class="mt-2 text-sm text-ink-muted">Explore our most-wanted line-up</p>
  </div>
  <div class="swiper trend-swiper">
    <div class="swiper-wrapper">
      <?php foreach ($trend as $c): $cat = FL_CATEGORIES[$c]; ?>
        <div class="swiper-slide">
          <a href="<?= fl_category_url($c) ?>" class="group relative grid h-[340px] grid-cols-[1.1fr_1fr] overflow-hidden rounded-3xl <?= e($cat['tone']) ?> sm:h-[380px]">
            <div class="relative z-10 flex flex-col justify-between p-6 sm:p-9">
              <div>
                <p class="eyebrow"><?= e(FL_CATEGORIES[$cat['parent']]['label']) ?></p>
                <h3 class="mt-3 font-serif text-4xl font-medium leading-none text-forest sm:text-5xl"><?= e($cat['label']) ?></h3>
                <p class="mt-3 max-w-[16rem] text-sm text-ink-soft"><?= e($cat['blurb']) ?></p>
              </div>
              <span class="inline-flex w-max items-center gap-2 rounded-full border border-forest/70 px-5 py-2.5 text-[11px] font-semibold uppercase tracking-widest text-forest transition group-hover:bg-forest group-hover:text-ivory">Explore now <?= fl_icon('arrow-r', 'h-3.5 w-3.5') ?></span>
            </div>
            <div class="relative">
              <img src="<?= e($cat['image']) ?>" alt="<?= e($cat['label']) ?>" class="absolute inset-0 h-full w-full object-cover object-top transition duration-700 group-hover:scale-105" loading="lazy">
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="dots trend-dots mt-8 flex justify-center"></div>
  </div>
</section>

<!-- ============ MADE TO LIVE IN (lifestyle + products, tabs) ============ -->
<section class="py-16 lg:py-24" data-tabs>
  <div class="container">
    <div class="reveal mb-8 flex flex-wrap items-end justify-between gap-4">
      <div>
        <h2 class="section-title">Slow &amp; <em>Soulful</em></h2>
        <p class="mt-2 text-sm text-ink-muted">Sleep, lounge and live in the softest cotton</p>
      </div>
      <div class="seg" role="tablist">
        <button type="button" role="tab" aria-selected="true" data-tab="women">Women</button>
        <button type="button" role="tab" aria-selected="false" data-tab="men">Men</button>
      </div>
    </div>
    <?php foreach (['women' => ['img' => 'assets/images/products/haizead5.jpg', 'pos' => 'object-center', 'l1' => 'every stitch', 'l2' => 'tells a story', 'note' => 'The “Queen Bee”, hand-embroidered in cotton thread on our Haizea collection.', 'group' => 'sleep_women', 'cta' => 'women'],
                            'men'   => ['img' => 'assets/images/brand/Designer-8.jpg', 'pos' => 'object-[50%_25%]', 'l1' => 'made to', 'l2' => 'live in', 'note' => 'Relaxed silhouettes in slub cotton and twill — for travel, outings and easy evenings.', 'group' => 'sleep_men', 'cta' => 'men']] as $key => $panel): ?>
      <div data-panel="<?= $key ?>" class="grid gap-6 lg:grid-cols-12 <?= $key === 'men' ? 'hidden' : '' ?>">
        <div class="relative min-h-[440px] overflow-hidden rounded-3xl bg-forest lg:col-span-5 lg:min-h-[600px]">
          <img src="<?= e($panel['img']) ?>" alt="" class="absolute inset-0 h-full w-full object-cover <?= $panel['pos'] ?>" loading="lazy">
          <div class="absolute inset-0 bg-gradient-to-t from-forest/95 via-forest/45 to-transparent"></div>
          <div class="absolute inset-x-0 bottom-0 p-7 lg:p-10">
            <p class="font-serif text-6xl italic leading-[0.85] text-sand lg:text-7xl"><?= e($panel['l1']) ?><br><span class="text-ivory"><?= e($panel['l2']) ?></span></p>
            <p class="mt-4 max-w-xs text-sm text-ivory/80"><?= e($panel['note']) ?></p>
            <a href="<?= fl_category_url($panel['cta']) ?>" class="btn-light mt-6 !px-5 !py-2.5">Shop <?= e(FL_CATEGORIES[$panel['cta']]['label']) ?></a>
          </div>
        </div>
        <div class="relative min-w-0 lg:col-span-7 lg:self-center">
          <div class="swiper card-swiper">
            <div class="swiper-wrapper">
              <?php foreach (fl_pick(FL_GROUPS[$panel['group']]) as $p): ?>
                <div class="swiper-slide !h-auto"><?php fl_partial('product-card', ['p' => $p]); ?></div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="card-prev nav-btn" aria-label="Previous"><?= fl_icon('chev-l', 'h-5 w-5') ?></button>
            <button type="button" class="card-next nav-btn" aria-label="Next"><?= fl_icon('chev-r', 'h-5 w-5') ?></button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ NEW ARRIVALS (interactive showcase) ============ -->
<section class="bg-[#EFEBE3] py-16 lg:py-24" data-new-arrivals>
  <div class="container">
    <div class="reveal mb-10 flex flex-wrap items-end justify-between gap-4">
      <h2 class="font-sans text-4xl font-semibold uppercase tracking-[0.04em] text-forest lg:text-6xl">New <span class="font-serif font-medium normal-case italic tracking-normal text-moss">Arrivals</span></h2>
      <div class="seg" role="tablist">
        <button type="button" role="tab" aria-selected="true" data-na-tab="women">Women</button>
        <button type="button" role="tab" aria-selected="false" data-na-tab="men">Men</button>
      </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-12 lg:gap-8">
      <!-- other images -->
      <div class="order-2 lg:order-1 lg:col-span-2">
        <p class="mb-3 hidden text-xs font-medium uppercase tracking-widest text-ink-soft lg:block">Other images</p>
        <div data-na-gallery class="grid grid-cols-4 gap-2 lg:grid-cols-2 lg:gap-3"></div>
      </div>
      <!-- main image -->
      <div class="order-1 lg:order-2 lg:col-span-4">
        <a data-na-main-link href="#" class="block overflow-hidden rounded-3xl bg-[#F1EFEC]">
          <img data-na-main src="" alt="" class="aspect-[3/4] w-full object-cover object-top transition-opacity duration-300">
        </a>
      </div>
      <!-- picker + info -->
      <div class="order-3 flex min-w-0 flex-col gap-6 lg:col-span-6">
        <div class="relative">
          <div data-na-thumbs class="no-scrollbar flex snap-x gap-3 overflow-x-auto scroll-smooth pb-1"></div>
          <div class="mt-3 flex justify-end gap-2">
            <button type="button" data-na-prev class="nav-btn !h-9 !w-9" aria-label="Previous product"><?= fl_icon('chev-l', 'h-4 w-4') ?></button>
            <button type="button" data-na-next class="nav-btn !h-9 !w-9" aria-label="Next product"><?= fl_icon('chev-r', 'h-4 w-4') ?></button>
          </div>
        </div>
        <div class="rounded-3xl bg-forest p-6 text-ivory shadow-soft sm:p-8">
          <div class="flex items-start justify-between gap-4">
            <div>
              <span class="inline-block rounded-full bg-ivory/15 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest">New · <span data-na-cat></span></span>
              <h3 data-na-name class="mt-3 font-serif text-3xl leading-tight sm:text-4xl"></h3>
              <p data-na-tagline class="mt-2 max-w-md text-sm text-ivory/70"></p>
            </div>
          </div>
          <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-ivory/15 pt-6">
            <p data-na-price class="mr-auto font-serif text-3xl"></p>
            <a data-na-link href="#" class="btn-outline-light !py-2.5">Explore</a>
            <button type="button" data-na-add class="grid h-11 w-11 place-items-center rounded-full bg-ivory text-forest hover:bg-sand" aria-label="Add to bag"><?= fl_icon('bag', 'h-5 w-5') ?></button>
          </div>
          <dl data-na-specs class="mt-6 grid grid-cols-2 gap-4 text-sm"></dl>
        </div>
      </div>
    </div>
  </div>
  <script type="application/json" id="na-data"><?= json_encode($na, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?></script>
</section>

<!-- ============ BEST SELLERS ============ -->
<section class="py-16 lg:py-24" data-tabs>
  <div class="container">
    <div class="reveal mb-8 flex flex-wrap items-end justify-between gap-4">
      <div>
        <h2 class="section-title">Best <em>Sellers</em></h2>
        <p class="mt-2 text-sm text-ink-muted">Most loved styles</p>
      </div>
      <div class="seg" role="tablist">
        <button type="button" role="tab" aria-selected="true" data-tab="women">Women</button>
        <button type="button" role="tab" aria-selected="false" data-tab="men">Men</button>
      </div>
    </div>
    <?php foreach (['women' => 'best_women', 'men' => 'best_men'] as $key => $group): ?>
      <div data-panel="<?= $key ?>" class="relative <?= $key === 'men' ? 'hidden' : '' ?>">
        <div class="swiper grid-swiper">
          <div class="swiper-wrapper">
            <?php foreach (fl_pick(FL_GROUPS[$group]) as $p): ?>
              <div class="swiper-slide !h-auto"><?php fl_partial('product-card', ['p' => $p]); ?></div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="mt-8 flex items-center justify-between gap-4">
          <div class="dots grid-dots flex"></div>
          <div class="flex gap-2">
            <button type="button" class="grid-prev nav-btn" aria-label="Previous"><?= fl_icon('chev-l', 'h-5 w-5') ?></button>
            <button type="button" class="grid-next nav-btn" aria-label="Next"><?= fl_icon('chev-r', 'h-5 w-5') ?></button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ BRAND BAND ============ -->
<section class="container pb-16 lg:pb-24">
  <div class="reveal relative overflow-hidden rounded-[2rem] bg-forest">
    <img src="assets/images/products/img-9807.jpg" alt="" class="absolute inset-0 h-full w-full object-cover opacity-35 mix-blend-luminosity" loading="lazy">
    <div class="absolute inset-0 bg-gradient-to-r from-forest via-forest/70 to-forest/30"></div>
    <div class="relative grid items-center gap-10 px-6 py-16 sm:px-12 lg:grid-cols-2 lg:px-20 lg:py-24">
      <div class="text-ivory">
        <img src="assets/images/brand/faelynn-logo-light.png" alt="Faelynn — love for nature" class="h-16 w-auto sm:h-20" loading="lazy">
        <p class="mt-8 font-serif text-3xl italic leading-snug text-sand sm:text-4xl">“We don’t follow trends — we create with feeling.”</p>
        <p class="mt-5 max-w-lg text-ivory/75">Each garment carries the care of Indian artisans, whose hands weave stories of nature, memory and tradition into every thread.</p>
        <a href="<?= fl_url('about') ?>" class="btn-light mt-8">Read our story <?= fl_icon('arrow-r', 'h-4 w-4') ?></a>
      </div>
      <div class="grid grid-cols-3 gap-3 text-center text-ivory sm:gap-5">
        <?php foreach ([['100%', 'Cotton'], ['Hand', 'Embroidered'], ['Made', 'in India']] as [$a, $b]): ?>
          <div class="rounded-2xl border border-ivory/15 bg-ivory/5 px-2 py-6 backdrop-blur-sm sm:py-8">
            <p class="font-serif text-3xl sm:text-4xl"><?= e($a) ?></p>
            <p class="mt-1 text-[10px] uppercase tracking-widest text-ivory/70 sm:text-xs"><?= e($b) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <img src="assets/images/brand/dragonfly-light.png" alt="" class="absolute right-8 top-8 h-12 w-auto animate-float opacity-70" loading="lazy">
  </div>
</section>

<!-- ============ SHOP BY CATEGORY ============ -->
<section class="pb-16 lg:pb-24" data-tabs>
  <div class="container">
    <div class="reveal mb-8 flex flex-wrap items-end justify-between gap-4">
      <div>
        <h2 class="section-title">Featured <em>Categories</em></h2>
        <p class="mt-2 text-sm text-ink-muted">Find your kind of comfort</p>
      </div>
      <div class="seg" role="tablist">
        <button type="button" role="tab" aria-selected="true" data-tab="women">Women</button>
        <button type="button" role="tab" aria-selected="false" data-tab="men">Men</button>
      </div>
    </div>
    <?php foreach (['women' => ['dresses-women', 'co-ord-set-women', 'night-dress-women'], 'men' => ['shirt-men', 'trouser-men', 'shorts-men']] as $key => $cats): ?>
      <div data-panel="<?= $key ?>" class="grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-6 <?= $key === 'men' ? 'hidden' : '' ?>">
        <?php foreach ($cats as $c): $cat = FL_CATEGORIES[$c]; ?>
          <a href="<?= fl_category_url($c) ?>" class="group relative overflow-hidden rounded-3xl <?= e($cat['tone']) ?>">
            <img src="<?= e($cat['image']) ?>" alt="<?= e($cat['label']) ?>" class="aspect-[3/4] w-full object-cover object-top mix-blend-multiply transition duration-700 group-hover:scale-105" loading="lazy">
            <div class="absolute inset-x-3 bottom-3 flex items-center justify-between rounded-2xl bg-ivory/95 px-4 py-3 backdrop-blur">
              <div>
                <p class="font-serif text-xl leading-none text-forest sm:text-2xl"><?= e($cat['label']) ?></p>
                <p class="mt-1 text-[11px] uppercase tracking-widest text-ink-muted"><?= fl_category_count($c) ?> <?= fl_category_count($c) === 1 ? 'style' : 'styles' ?></p>
              </div>
              <span class="grid h-9 w-9 place-items-center rounded-full bg-forest text-ivory"><?= fl_icon('arrow-r', 'h-4 w-4') ?></span>
            </div>
          </a>
        <?php endforeach; ?>
        <?php $parent = FL_CATEGORIES[$key]; ?>
        <a href="<?= fl_category_url($key) ?>" class="group relative col-span-2 flex min-h-[220px] flex-col justify-end overflow-hidden rounded-3xl bg-forest p-6 text-ivory lg:col-span-1">
          <img src="<?= e($parent['image']) ?>" alt="" class="absolute inset-0 h-full w-full object-cover opacity-60 transition duration-700 group-hover:scale-105" loading="lazy">
          <div class="absolute inset-0 bg-gradient-to-t from-forest via-forest/40 to-transparent"></div>
          <div class="relative">
            <p class="eyebrow !text-sand">View all</p>
            <p class="mt-2 font-serif text-4xl"><?= e($parent['label']) ?></p>
            <p class="mt-2 text-sm text-ivory/75"><?= e($parent['blurb']) ?></p>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ WHY FAELYNN ============ -->
<section class="bg-sage-pale py-16 lg:py-24">
  <div class="container grid items-center gap-12 lg:grid-cols-2">
    <div class="reveal relative">
      <div class="grid grid-cols-2 gap-4">
        <img src="assets/images/products/img-9836.jpg" alt="Daisy embroidery detail on the Sybil co-ord set" class="aspect-[3/4] w-full rounded-3xl object-cover" loading="lazy">
        <img src="assets/images/products/fae4.jpg" alt="Hand embroidery on the Fae dress" class="mt-12 aspect-[3/4] w-full rounded-3xl object-cover" loading="lazy">
      </div>
      <div class="absolute -bottom-6 left-1/2 flex -translate-x-1/2 items-center gap-3 whitespace-nowrap rounded-full bg-ivory px-5 py-3 shadow-soft">
        <img src="assets/images/brand/dragonfly.png" alt="" class="h-6 w-auto"><span class="text-sm font-medium text-forest">Hand-finished in India</span>
      </div>
    </div>
    <div>
      <p class="eyebrow">Why Faelynn</p>
      <h2 class="mt-3 font-serif text-4xl font-medium leading-tight text-forest sm:text-5xl">Soft, soulful &amp; <em class="text-moss">made by hand</em></h2>
      <p class="mt-4 max-w-lg text-ink-soft">Faelynn was born from a simple wish — to slow down and find beauty in the quiet. Every piece is crafted to feel like a gentle touch against the skin.</p>
      <div class="mt-10 grid gap-6 sm:grid-cols-2">
        <?php foreach (FL_USPS as $u): ?>
          <div class="reveal flex gap-4">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-ivory text-moss shadow-card"><?= fl_icon($u['icon'], 'h-6 w-6') ?></span>
            <div><p class="font-serif text-xl text-forest"><?= e($u['title']) ?></p><p class="mt-1 text-sm text-ink-soft"><?= e($u['text']) ?></p></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ============ CUSTOM ORDER BANNER ============ -->
<section class="container py-16 lg:py-24">
  <div class="reveal relative overflow-hidden rounded-[2rem] bg-sand">
    <div class="grid items-center lg:grid-cols-2">
      <div class="relative z-10 px-6 py-12 sm:px-12 lg:py-20 lg:pl-16">
        <p class="eyebrow">Bespoke · Made to measure</p>
        <h2 class="mt-4 font-serif text-5xl font-medium leading-[0.95] text-forest lg:text-6xl">Need something<br><em class="text-clay">unique?</em></h2>
        <p class="mt-5 max-w-md text-ink-soft">We take custom orders tailored to your needs. Share your style, preferred colours, fabric and measurements — we’ll craft a made-to-measure piece that feels as magical as it looks.</p>
        <div class="mt-8 flex flex-wrap gap-3">
          <a href="<?= fl_url('custom-order') ?>" class="btn-primary">Place a custom order</a>
          <a href="https://wa.me/<?= FL_PHONE_RAW ?>?text=<?= rawurlencode('Hi Faelynn, I would like to place a custom order.') ?>" target="_blank" rel="noopener" class="btn-outline"><?= fl_social_icon('whatsapp', 'h-4 w-4') ?> WhatsApp us</a>
        </div>
      </div>
      <div class="relative h-[360px] lg:h-full lg:min-h-[520px]">
        <img src="assets/images/products/fae2.jpg" alt="Fae hand-embroidered dress" class="absolute bottom-0 right-[38%] h-[85%] w-auto rounded-t-[999px] object-cover shadow-soft lg:right-[40%]" loading="lazy">
        <img src="assets/images/products/haizea1.jpg" alt="Haizea co-ord set" class="absolute bottom-0 right-6 h-[95%] w-auto rounded-t-[999px] border-4 border-sand object-cover shadow-soft lg:right-12" loading="lazy">
        <img src="assets/images/brand/dragonfly.png" alt="" class="absolute left-[12%] top-10 h-12 w-auto animate-float opacity-70" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- ============ TESTIMONIALS ============ -->
<section class="bg-cream/70 py-16 lg:py-24">
  <div class="container">
    <div class="reveal mb-10 text-center">
      <h2 class="section-title">Loved by <em>our customers</em></h2>
      <p class="mt-2 text-sm text-ink-muted">Real words from real people</p>
    </div>
    <div class="grid gap-6 md:grid-cols-3">
      <?php foreach (FL_TESTIMONIALS as $t): ?>
        <figure class="reveal flex flex-col rounded-3xl bg-ivory p-8 shadow-card">
          <div class="flex gap-0.5 text-clay" aria-label="5 out of 5 stars"><?php for ($i = 0; $i < 5; $i++): ?><svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="m10 1.5 2.6 5.4 5.9.8-4.3 4.1 1 5.8L10 14.8l-5.2 2.8 1-5.8L1.5 7.7l5.9-.8z"/></svg><?php endfor; ?></div>
          <p class="mt-5 font-serif text-2xl text-forest"><?= e($t['title']) ?></p>
          <blockquote class="mt-3 flex-1 text-sm leading-relaxed text-ink-soft">“<?= e($t['text']) ?>”</blockquote>
          <figcaption class="mt-6 flex items-center gap-3 border-t border-line pt-5">
            <span class="grid h-10 w-10 place-items-center rounded-full bg-sage-light font-serif text-lg text-forest"><?= e(mb_substr($t['name'], 0, 1)) ?></span>
            <span><span class="block text-sm font-medium text-forest"><?= e($t['name']) ?></span><span class="block text-xs text-ink-muted"><?= e($t['role']) ?></span></span>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ JOURNAL ============ -->
<section class="py-16 lg:py-24">
  <div class="container">
    <div class="reveal mb-10 flex flex-wrap items-end justify-between gap-4">
      <div>
        <h2 class="section-title">Faelynn <em>Journal</em></h2>
        <p class="mt-2 text-sm text-ink-muted">Stories of comfort, care and craft</p>
      </div>
      <a href="<?= fl_url('journal') ?>" class="link-arrow">View all <?= fl_icon('arrow-r', 'h-4 w-4') ?></a>
    </div>
    <?php $post = FL_JOURNAL[0];
    $cards = array_merge([[ 'title' => $post['title'], 'tag' => $post['tag'], 'image' => $post['image'], 'url' => fl_url('journal', ['post' => $post['slug']]), 'excerpt' => $post['excerpt'], 'date' => $post['date'] ]], FL_STORIES); ?>
    <div class="no-scrollbar -mx-4 flex snap-x gap-5 overflow-x-auto px-4 sm:mx-0 sm:grid sm:grid-cols-2 sm:overflow-visible sm:px-0 lg:grid-cols-4">
      <?php foreach ($cards as $c): ?>
        <a href="<?= e($c['url']) ?>" class="group w-[78%] shrink-0 snap-start sm:w-auto">
          <div class="overflow-hidden rounded-3xl bg-cream"><img src="<?= e($c['image']) ?>" alt="" class="aspect-[4/3] w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy"></div>
          <p class="mt-4 text-[11px] font-medium uppercase tracking-widest text-moss"><?= e($c['tag']) ?><?= isset($c['date']) ? ' · ' . e(date('j M Y', strtotime($c['date']))) : '' ?></p>
          <h3 class="mt-2 font-serif text-xl leading-snug text-forest group-hover:text-moss"><?= e($c['title']) ?></h3>
          <p class="mt-2 line-clamp-2 text-sm text-ink-soft"><?= e($c['excerpt']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ INSTAGRAM ============ -->
<section class="pb-16 lg:pb-24">
  <div class="container">
    <div class="reveal mb-8 text-center">
      <p class="eyebrow">Follow along</p>
      <a href="<?= FL_INSTAGRAM ?>" target="_blank" rel="noopener" class="mt-2 inline-block font-serif text-3xl text-forest hover:text-moss sm:text-4xl">@faelynn.co.in</a>
    </div>
    <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:grid-cols-6">
      <?php foreach (['kendyl5.jpg', 'walden4.jpg', 'img-9832-1.jpg', 'img-9801.jpg', 'rio4.jpg', 'fae6.jpg'] as $img): ?>
        <a href="<?= FL_INSTAGRAM ?>" target="_blank" rel="noopener" class="group relative block overflow-hidden rounded-2xl" aria-label="Faelynn on Instagram">
          <img src="assets/images/products/<?= e($img) ?>" alt="" class="aspect-square w-full object-cover object-top transition duration-700 group-hover:scale-110" loading="lazy">
          <span class="absolute inset-0 grid place-items-center bg-forest/0 text-ivory opacity-0 transition group-hover:bg-forest/40 group-hover:opacity-100"><?= fl_social_icon('instagram', 'h-7 w-7') ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ ABOUT (SEO) ============ -->
<section class="border-t border-line bg-ivory py-14">
  <div class="container max-w-5xl">
    <h2 class="font-serif text-2xl text-forest sm:text-3xl">Faelynn — Premium Handcrafted Clothing for Men &amp; Women</h2>
    <div data-readmore class="relative mt-4 max-h-24 overflow-hidden text-sm leading-relaxed text-ink-soft transition-[max-height] duration-500">
      <?php foreach (array_merge(FL_STORY, [FL_WORKSHOP[1]]) as $para): ?><p class="mb-3"><?= e($para) ?></p><?php endforeach; ?>
      <p class="mb-3">Shop hand-embroidered cotton dresses, matching co-ord sets, front-open night dresses perfect for nursing mothers, and relaxed cotton shirts with easy trousers for men — with free delivery across India, easy 10-day returns and made-to-measure custom orders.</p>
      <div data-readmore-fade class="pointer-events-none absolute inset-x-0 bottom-0 h-12 bg-gradient-to-t from-ivory to-transparent"></div>
    </div>
    <button type="button" data-readmore-btn class="mt-3 text-xs font-semibold uppercase tracking-widest text-forest underline underline-offset-4">Show more</button>
  </div>
</section>

<?php fl_partial('footer'); ?>
