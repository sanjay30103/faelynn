<?php
require __DIR__ . '/inc/bootstrap.php';

$p = fl_product((string) ($_GET['slug'] ?? ''));
if (!$p) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    exit;
}

$page_title = $p['name'] . ' | Faelynn';
$page_desc  = $p['tagline'] ?: ($p['intro'][0] ?? FL_TAGLINE);
$parentCat  = FL_CATEGORIES[$p['gender']];
$primaryCat = FL_CATEGORIES[$p['primary_category']] ?? null;

// Description: intro + description paragraphs, de-duplicated
$desc = [];
foreach (array_merge($p['intro'], $p['description']) as $para) {
    $key = mb_strtolower(mb_substr(preg_replace('/\s+/', ' ', $para), 0, 60));
    if (!isset($desc[$key])) {
        $desc[$key] = $para;
    }
}

// Details: full spec sheet, topped up with quick specs not already covered
$details = $p['specs'];
$keys = array_map(fn($s) => mb_strtolower($s[0]), $details);
foreach ($p['quick_specs'] as [$k, $v]) {
    $lk = mb_strtolower($k);
    $covered = in_array($lk, $keys, true)
        || ($lk === 'fabric' && in_array('material', $keys, true))
        || ($lk === 'sleeve' && array_filter($keys, fn($x) => str_starts_with($x, 'sleeve')));
    if (!$covered) {
        $details[] = [$k, $v];
    }
}

$isWomen = $p['gender'] === 'women';
$related = fl_related($p);
$policies = fl_policies();

fl_partial('header', compact('page_title', 'page_desc'));
?>

<div class="container pt-5" data-product="<?= e($p['slug']) ?>">
  <!-- Breadcrumb -->
  <nav class="mb-5 text-xs text-ink-muted" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1.5">
      <li><a href="<?= fl_url() ?>" class="hover:text-forest">Home</a></li>
      <li aria-hidden="true">/</li>
      <li><a href="<?= fl_category_url($p['gender']) ?>" class="hover:text-forest"><?= e($parentCat['label']) ?></a></li>
      <?php if ($primaryCat): ?><li aria-hidden="true">/</li><li><a href="<?= fl_category_url($p['primary_category']) ?>" class="hover:text-forest"><?= e($primaryCat['label']) ?></a></li><?php endif; ?>
      <li aria-hidden="true">/</li>
      <li class="text-forest" aria-current="page"><?= e($p['short_name']) ?></li>
    </ol>
  </nav>

  <div class="grid gap-8 lg:grid-cols-12 lg:gap-12">
    <!-- ===== Gallery ===== -->
    <div class="min-w-0 lg:col-span-6">
      <!-- Mobile slider -->
      <div class="swiper pdp-swiper -mx-4 sm:mx-0 sm:rounded-3xl lg:!hidden">
        <div class="swiper-wrapper">
          <?php foreach ($p['images'] as $i => $img): ?>
            <div class="swiper-slide bg-[#F1EFEC]"><img src="<?= e($img['src']) ?>" alt="<?= e($p['short_name'] . ' — image ' . ($i + 1)) ?>" class="aspect-[3/4] w-full object-cover object-top" <?= $i ? 'loading="lazy"' : '' ?>></div>
          <?php endforeach; ?>
        </div>
        <div class="pdp-dots dots absolute inset-x-0 bottom-4 z-10 flex justify-center"></div>
      </div>
      <!-- Desktop gallery: sticky while the details column scrolls; sized to always fit the viewport -->
      <div class="hidden gap-4 lg:sticky lg:top-28 lg:flex">
        <div class="no-scrollbar flex max-h-[calc(100svh-9rem)] w-20 shrink-0 flex-col gap-3 overflow-y-auto">
          <?php foreach ($p['images'] as $i => $img): ?>
            <button type="button" data-pdp-thumb="<?= e($img['src']) ?>" class="shrink-0 overflow-hidden rounded-xl border-2 <?= $i === 0 ? 'border-forest' : 'border-transparent' ?> bg-[#F1EFEC] transition hover:border-forest/50" aria-label="View image <?= $i + 1 ?>">
              <img src="<?= e($img['src']) ?>" alt="" class="aspect-[3/4] w-full object-cover object-top" loading="lazy">
            </button>
          <?php endforeach; ?>
        </div>
        <div data-zoom class="relative aspect-[3/4] max-w-[calc((100svh-9rem)*0.75)] flex-1 cursor-zoom-in overflow-hidden rounded-3xl bg-[#F1EFEC]">
          <img data-pdp-main src="<?= e($p['images'][0]['src']) ?>" alt="<?= e($p['short_name']) ?>" class="h-full w-full object-cover object-top transition-transform duration-200 ease-out">
          <button type="button" data-wishlist="<?= e($p['slug']) ?>" aria-pressed="false" aria-label="Add to wishlist" class="absolute right-4 top-4 grid h-11 w-11 place-items-center rounded-full bg-ivory text-forest shadow-card transition hover:scale-110 aria-pressed:text-clay"><?= fl_icon('heart', 'h-5 w-5') ?></button>
        </div>
      </div>
    </div>

    <!-- ===== Info ===== -->
    <div class="min-w-0 lg:col-span-6 xl:pl-6">
      <div>
        <p class="eyebrow"><?= e(($primaryCat['label'] ?? '') . ' · ' . $parentCat['label']) ?></p>
        <h1 class="mt-3 font-serif text-4xl font-medium leading-tight text-forest sm:text-5xl"><?= e($p['short_name']) ?></h1>
        <?php if ($p['subtitle']): ?><p class="mt-1 font-serif text-xl italic text-moss"><?= e($p['subtitle']) ?></p><?php endif; ?>
        <?php if ($p['tagline']): ?><p class="mt-4 text-ink-soft"><?= e($p['tagline']) ?></p><?php elseif ($p['intro']): ?><p class="mt-4 text-ink-soft"><?= e($p['intro'][0]) ?></p><?php endif; ?>

        <div class="mt-6 flex items-baseline gap-3 border-b border-line pb-6">
          <p class="font-serif text-4xl text-forest"><?= fl_price($p['price']) ?></p>
          <p class="text-xs text-ink-muted">MRP inclusive of all taxes</p>
        </div>

        <!-- Size -->
        <div class="mt-6" data-sizes>
          <div class="mb-3 flex items-center justify-between">
            <p class="text-xs font-medium uppercase tracking-widest text-ink-soft">Size: <span data-selected-size class="text-forest">Select</span></p>
            <?php if ($isWomen): ?>
              <button type="button" data-open="size-guide" class="inline-flex items-center gap-1.5 text-xs font-medium uppercase tracking-widest text-forest underline underline-offset-4"><?= fl_icon('ruler', 'h-4 w-4') ?> Size guide</button>
            <?php else: ?>
              <a href="https://wa.me/<?= FL_PHONE_RAW ?>?text=<?= rawurlencode('Hi Faelynn, I need help with sizing for ' . $p['short_name']) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs font-medium uppercase tracking-widest text-forest underline underline-offset-4"><?= fl_icon('ruler', 'h-4 w-4') ?> Help with size</a>
            <?php endif; ?>
          </div>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($p['sizes'] as $s): ?>
              <button type="button" class="size-pill min-w-[56px]" aria-pressed="false" data-pdp-size="<?= e($s) ?>"><?= e($s) ?></button>
            <?php endforeach; ?>
          </div>
          <p data-size-error class="mt-2 hidden text-xs text-clay">Please select a size to continue.</p>
        </div>

        <!-- Qty + CTAs -->
        <div class="mt-6 flex gap-3">
          <div class="inline-flex items-center rounded-full border border-line bg-white">
            <button type="button" data-pdp-step="-1" class="grid h-12 w-11 place-items-center text-forest" aria-label="Decrease quantity"><?= fl_icon('minus', 'h-4 w-4') ?></button>
            <span data-pdp-qty class="w-6 text-center text-sm font-medium" aria-live="polite">1</span>
            <button type="button" data-pdp-step="1" class="grid h-12 w-11 place-items-center text-forest" aria-label="Increase quantity"><?= fl_icon('plus', 'h-4 w-4') ?></button>
          </div>
          <button type="button" data-pdp-add class="btn-primary flex-1"><?= fl_icon('bag', 'h-4 w-4') ?> Add to bag</button>
          <button type="button" data-wishlist="<?= e($p['slug']) ?>" aria-pressed="false" aria-label="Add to wishlist" class="grid h-12 w-12 shrink-0 place-items-center rounded-full border border-line bg-white text-forest transition hover:border-forest aria-pressed:text-clay"><?= fl_icon('heart', 'h-5 w-5') ?></button>
        </div>
        <button type="button" data-pdp-buy class="btn-outline mt-3 w-full">Buy it now</button>

        <!-- Delivery -->
        <form data-pincode class="mt-6 rounded-2xl border border-line bg-white p-4" novalidate>
          <label for="pin" class="flex items-center gap-2 text-xs font-medium uppercase tracking-widest text-ink-soft"><?= fl_icon('pin', 'h-4 w-4') ?> Check delivery</label>
          <div class="mt-3 flex gap-2">
            <input id="pin" inputmode="numeric" maxlength="6" placeholder="Enter PIN code" class="field !py-2.5">
            <button class="rounded-xl bg-cream px-4 text-xs font-semibold uppercase tracking-widest text-forest hover:bg-sand">Check</button>
          </div>
          <p data-pincode-result class="mt-2 text-xs"></p>
        </form>

        <ul class="mt-4 grid grid-cols-3 gap-2 text-center text-[11px] text-ink-soft">
          <li class="rounded-2xl bg-cream/70 px-2 py-4"><span class="mx-auto mb-2 block w-max text-moss"><?= fl_icon('truck', 'h-5 w-5') ?></span>Free delivery</li>
          <li class="rounded-2xl bg-cream/70 px-2 py-4"><span class="mx-auto mb-2 block w-max text-moss"><?= fl_icon('return', 'h-5 w-5') ?></span>10-day returns</li>
          <li class="rounded-2xl bg-cream/70 px-2 py-4"><span class="mx-auto mb-2 block w-max text-moss"><?= fl_icon('needle', 'h-5 w-5') ?></span>Handcrafted cotton</li>
        </ul>

        <?php if ($p['highlights']): ?>
          <div class="mt-8">
            <p class="text-xs font-medium uppercase tracking-widest text-ink-soft">Why you’ll love it</p>
            <ul class="mt-3 space-y-2">
              <?php foreach ($p['highlights'] as $h): ?>
                <li class="flex gap-3 text-sm text-ink-soft"><span class="mt-0.5 text-moss"><?= fl_icon('check', 'h-4 w-4') ?></span><?= e($h) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <!-- Accordions -->
        <div class="mt-8 divide-y divide-line border-y border-line">
          <details class="acc" open>
            <summary>Description <span class="acc-icon"><?= fl_icon('plus', 'h-4 w-4') ?></span></summary>
            <div class="pb-5 text-sm leading-relaxed text-ink-soft">
              <?php foreach ($desc as $para): ?><p class="mb-3"><?= e($para) ?></p><?php endforeach; ?>
              <?php if ($p['perfect_for']): ?>
                <p class="mb-2 mt-4 text-xs font-medium uppercase tracking-widest text-forest">Perfect for</p>
                <div class="flex flex-wrap gap-2"><?php foreach ($p['perfect_for'] as $pf): ?><span class="rounded-full bg-sage-pale px-3 py-1 text-xs text-moss"><?= e($pf) ?></span><?php endforeach; ?></div>
              <?php endif; ?>
            </div>
          </details>
          <?php if ($details): ?>
            <details class="acc">
              <summary>Product details <span class="acc-icon"><?= fl_icon('plus', 'h-4 w-4') ?></span></summary>
              <dl class="grid grid-cols-[auto_1fr] gap-x-6 gap-y-3 pb-5 text-sm">
                <?php foreach ($details as [$k, $v]): ?>
                  <dt class="text-ink-muted"><?= e($k) ?></dt><dd class="text-forest"><?= e($v) ?></dd>
                <?php endforeach; ?>
                <dt class="text-ink-muted">Sizes</dt><dd class="text-forest"><?= e(implode(', ', $p['sizes'])) ?></dd>
              </dl>
            </details>
          <?php endif; ?>
          <details class="acc">
            <summary>Wash care <span class="acc-icon"><?= fl_icon('plus', 'h-4 w-4') ?></span></summary>
            <ul class="space-y-2 pb-5 text-sm text-ink-soft">
              <li>• Gentle hand wash in cool water with a mild detergent.</li>
              <li>• Squeeze gently — do not wring or twist the fabric.</li>
              <li>• Lay flat to air-dry, away from direct sunlight or heat.</li>
              <li>• Fold gently and store away from direct light.</li>
              <li><a href="<?= fl_url('policy', ['type' => 'wash-care']) ?>" class="text-moss underline underline-offset-4">Read our full care ritual</a></li>
            </ul>
          </details>
          <details class="acc">
            <summary>Shipping &amp; returns <span class="acc-icon"><?= fl_icon('plus', 'h-4 w-4') ?></span></summary>
            <div class="space-y-2 pb-5 text-sm text-ink-soft">
              <p>Orders are processed and shipped within 3–4 business days. Standard delivery usually takes 3–7 business days.</p>
              <p>Returns can be initiated within 10 days of purchase for unused items in original packaging. Custom-made pieces are not eligible for return unless defective.</p>
              <p><a href="<?= fl_url('policy', ['type' => 'shipping']) ?>" class="text-moss underline underline-offset-4">Shipping policy</a> · <a href="<?= fl_url('policy', ['type' => 'returns']) ?>" class="text-moss underline underline-offset-4">Returns policy</a></p>
            </div>
          </details>
        </div>

        <div class="mt-6 flex items-center justify-between text-xs text-ink-muted">
          <a href="https://wa.me/<?= FL_PHONE_RAW ?>?text=<?= rawurlencode('Hi Faelynn, I have a question about ' . $p['short_name']) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 hover:text-forest"><?= fl_social_icon('whatsapp', 'h-4 w-4 text-moss') ?> Ask us on WhatsApp</a>
          <button type="button" data-share class="inline-flex items-center gap-2 hover:text-forest"><?= fl_icon('arrow-r', 'h-4 w-4 -rotate-45') ?> Share</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== Story band ===== -->
<section class="mt-16 bg-sage-pale py-14 lg:mt-24">
  <div class="container grid items-center gap-8 lg:grid-cols-[auto_1fr_auto]">
    <img src="assets/images/brand/dragonfly.png" alt="" class="hidden h-14 w-auto lg:block">
    <p class="font-serif text-2xl leading-snug text-forest sm:text-3xl">“Every print, every fold, every stitch whispers a story — of nature’s inspiration and of hands that love their craft.”</p>
    <a href="<?= fl_url('about') ?>" class="btn-outline shrink-0">Our story</a>
  </div>
</section>

<!-- ===== Related ===== -->
<?php if ($related): ?>
<section class="py-16 lg:py-24">
  <div class="container">
    <div class="mb-8 flex items-end justify-between gap-4">
      <h2 class="section-title">You may <em>also like</em></h2>
      <div class="hidden gap-2 sm:flex">
        <button type="button" class="rel-prev nav-btn" aria-label="Previous"><?= fl_icon('chev-l', 'h-5 w-5') ?></button>
        <button type="button" class="rel-next nav-btn" aria-label="Next"><?= fl_icon('chev-r', 'h-5 w-5') ?></button>
      </div>
    </div>
    <div class="relative">
      <div class="swiper related-swiper">
        <div class="swiper-wrapper">
          <?php foreach ($related as $rp): ?>
            <div class="swiper-slide !h-auto"><?php fl_partial('product-card', ['p' => $rp]); ?></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== Sticky mobile add-to-bag ===== -->
<div data-sticky-bar class="fixed inset-x-0 bottom-0 z-40 translate-y-full border-t border-line bg-ivory/95 px-4 py-3 backdrop-blur transition-transform duration-300 lg:hidden">
  <div class="flex items-center gap-3">
    <img src="<?= e($p['images'][0]['src']) ?>" alt="" class="h-12 w-9 rounded-md object-cover object-top">
    <div class="min-w-0 flex-1">
      <p class="truncate font-serif text-base text-forest"><?= e($p['short_name']) ?></p>
      <p class="text-xs text-ink-soft"><?= fl_price($p['price']) ?> · Size <span data-selected-size>Select</span></p>
    </div>
    <button type="button" data-pdp-add class="btn-primary !px-5 !py-2.5">Add to bag</button>
  </div>
</div>

<?php if ($isWomen): ?>
<!-- ===== Size guide modal ===== -->
<div class="overlay hidden" data-overlay="size-guide"></div>
<div id="size-guide" class="modal rounded-3xl bg-ivory p-6 shadow-2xl sm:p-8" role="dialog" aria-label="Size guide" aria-hidden="true">
  <div class="flex items-start justify-between gap-4">
    <div>
      <p class="eyebrow">Size guide</p>
      <p class="mt-2 font-serif text-3xl text-forest">Find your fit</p>
      <p class="mt-1 text-sm text-ink-muted">Body measurements in inches. Our pieces are cut in relaxed silhouettes.</p>
    </div>
    <button type="button" data-close class="grid h-10 w-10 shrink-0 place-items-center rounded-full hover:bg-cream" aria-label="Close"><?= fl_icon('close') ?></button>
  </div>
  <div class="mt-6 overflow-x-auto rounded-2xl border border-line">
    <table class="w-full text-center text-sm">
      <thead class="bg-forest text-ivory"><tr><?php foreach (FL_SIZE_CHART['head'] as $h): ?><th class="px-3 py-3 text-xs font-medium uppercase tracking-widest"><?= e($h) ?></th><?php endforeach; ?></tr></thead>
      <tbody class="divide-y divide-line bg-white">
        <?php foreach (FL_SIZE_CHART['rows'] as $row): ?>
          <tr><?php foreach ($row as $i => $cell): ?><td class="px-3 py-3 <?= $i === 0 ? 'font-semibold text-forest' : 'text-ink-soft' ?>"><?= e($cell) ?></td><?php endforeach; ?></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p class="mt-5 text-sm text-ink-soft">Between sizes or need a perfect fit? <a href="<?= fl_url('custom-order') ?>" class="text-moss underline underline-offset-4">Place a custom order</a> made to your measurements.</p>
</div>
<?php endif; ?>

<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org', '@type' => 'Product', 'name' => $p['name'],
    'image' => array_column($p['images'], 'src'), 'description' => $page_desc, 'brand' => ['@type' => 'Brand', 'name' => 'Faelynn'],
    'offers' => ['@type' => 'Offer', 'priceCurrency' => 'INR', 'price' => $p['price'], 'availability' => 'https://schema.org/' . ($p['in_stock'] ? 'InStock' : 'OutOfStock')],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?></script>

<?php fl_partial('footer'); ?>
