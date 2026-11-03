<?php
require __DIR__ . '/inc/bootstrap.php';

$cat   = (string) ($_GET['cat'] ?? '');
$cat   = isset(FL_CATEGORIES[$cat]) ? $cat : '';
$q     = trim((string) ($_GET['q'] ?? ''));
$sizes = array_values(array_intersect((array) ($_GET['size'] ?? []), ['S', 'M', 'L', 'XL']));
$price = (string) ($_GET['price'] ?? '');
$max   = isset($_GET['max']) && is_numeric($_GET['max']) ? (int) $_GET['max'] : null;
$sort  = in_array($_GET['sort'] ?? '', ['new', 'price-asc', 'price-desc'], true) ? $_GET['sort'] : 'featured';

$priceRanges = [
    'u2500'     => ['label' => 'Under ' . fl_price(2500), 'min' => 0, 'max' => 2499],
    '2500-3500' => ['label' => fl_price(2500) . ' – ' . fl_price(3500), 'min' => 2500, 'max' => 3500],
    'o3500'     => ['label' => 'Above ' . fl_price(3500), 'min' => 3501, 'max' => PHP_INT_MAX],
];
if (!isset($priceRanges[$price])) {
    $price = '';
}

/** Full-text haystack for search */
function fl_haystack(array $p): string
{
    return mb_strtolower(implode(' ', array_merge(
        [$p['name'], $p['tagline'], $p['gender'], fl_category_label($p)],
        $p['tags'], $p['intro'], $p['highlights'], $p['perfect_for'], $p['description'],
        array_map(fn($s) => implode(' ', $s), $p['specs']),
        array_map(fn($s) => implode(' ', $s), $p['quick_specs'])
    )));
}

$base = fl_products();
if ($cat) {
    $base = array_filter($base, fn($p) => fl_in_category($p, $cat));
}
if ($q !== '') {
    $needle = mb_strtolower($q);
    $base = array_filter($base, fn($p) => str_contains(fl_haystack($p), $needle));
}
$results = $base;
if ($sizes) {
    $results = array_filter($results, fn($p) => array_intersect($sizes, $p['sizes']));
}
if ($price) {
    $r = $priceRanges[$price];
    $results = array_filter($results, fn($p) => $p['price'] >= $r['min'] && $p['price'] <= $r['max']);
}
if ($max !== null) {
    $results = array_filter($results, fn($p) => $p['price'] <= $max);
}
$results = array_values($results);
usort($results, match ($sort) {
    'new'        => fn($a, $b) => $b['id'] <=> $a['id'],
    'price-asc'  => fn($a, $b) => $a['price'] <=> $b['price'],
    'price-desc' => fn($a, $b) => $b['price'] <=> $a['price'],
    default      => fn($a, $b) => 0,
});

$catData = $cat ? FL_CATEGORIES[$cat] : null;
// Static build: each category is its own page, filters/sort/search run client-side (assets/js/app.js)
$formAction = FL_STATIC ? fl_url('shop', ['cat' => $cat ?: null]) : fl_url('shop');
if ($q !== '') {
    $heading = 'Search: “' . $q . '”';
    $blurb = count($results) . ' ' . (count($results) === 1 ? 'piece matches' : 'pieces match') . ' your search.';
} elseif ($catData) {
    $heading = $catData['parent'] ? $catData['label'] . ' for ' . FL_CATEGORIES[$catData['parent']]['label'] : $catData['label'];
    $blurb = $catData['blurb'];
} else {
    $heading = $sort === 'new' ? 'New Arrivals' : 'Shop All';
    $blurb = 'Soft, breathable, handcrafted cotton — for women and men.';
}
$heroImg = $catData['image'] ?? 'assets/images/brand/Designer-3.jpg';
$gender  = $catData ? ($catData['parent'] ?? $cat) : null;

$page_title = ($q !== '' ? 'Search results' : $heading) . ' | Faelynn';
$page_desc  = $blurb;

/** Build a URL keeping current filters, overriding some */
function fl_shop_link(array $override = []): string
{
    $params = array_merge(array_intersect_key($_GET, array_flip(['cat', 'q', 'size', 'price', 'sort', 'max'])), $override);
    return fl_url('shop', array_filter($params, fn($v) => $v !== null && $v !== '' && $v !== []));
}

$chips = [['', 'All'], ['women', 'Women'], ['dresses-women', 'Dresses'], ['co-ord-set-women', 'Co-ord Sets'], ['night-dress-women', 'Night Dresses'], ['men', 'Men'], ['shirt-men', 'Shirts'], ['trouser-men', 'Trousers'], ['shorts-men', 'Shorts']];

fl_partial('header', compact('page_title', 'page_desc'));
?>

<!-- Page hero -->
<section class="relative overflow-hidden bg-cream">
  <div class="container grid items-center gap-6 py-10 lg:grid-cols-[1fr_auto] lg:py-14">
    <div>
      <nav class="mb-4 text-xs text-ink-muted" aria-label="Breadcrumb">
        <a href="<?= fl_url() ?>" class="hover:text-forest">Home</a> <span class="mx-1">/</span>
        <?php if ($catData && $catData['parent']): ?><a href="<?= fl_category_url($catData['parent']) ?>" class="hover:text-forest"><?= e(FL_CATEGORIES[$catData['parent']]['label']) ?></a> <span class="mx-1">/</span><?php endif; ?>
        <span class="text-forest"><?= e($catData['label'] ?? ($q !== '' ? 'Search' : 'Shop')) ?></span>
      </nav>
      <h1 data-shop-heading class="font-serif text-4xl font-medium leading-tight text-forest sm:text-6xl"><?= e($heading) ?></h1>
      <p data-shop-blurb class="mt-3 max-w-xl text-ink-soft"><?= e($blurb) ?></p>
    </div>
    <div class="hidden h-40 w-72 overflow-hidden rounded-[999px_999px_24px_24px] lg:block">
      <img src="<?= e($heroImg) ?>" alt="" class="h-full w-full object-cover object-top">
    </div>
  </div>
</section>

<!-- Category chips -->
<div class="border-b border-line bg-ivory">
  <div class="container no-scrollbar flex gap-2 overflow-x-auto py-4">
    <?php foreach ($chips as [$slug, $label]): $active = $slug === $cat; ?>
      <a href="<?= $slug ? fl_url('shop', ['cat' => $slug, 'q' => $q ?: null]) : fl_url('shop', ['q' => $q ?: null]) ?>" class="shrink-0 rounded-full border px-4 py-2 text-sm transition <?= $active ? 'border-forest bg-forest text-ivory' : 'border-line bg-white text-forest hover:border-forest' ?>">
        <?= e($label) ?><?php if ($slug): ?> <span class="<?= $active ? 'text-ivory/70' : 'text-ink-muted' ?>">(<?= fl_category_count($slug) ?>)</span><?php endif; ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<div class="container grid gap-10 py-10 lg:grid-cols-[260px_1fr]">
  <!-- Filters (sidebar on desktop, drawer on mobile) -->
  <div class="overlay hidden lg:!hidden" data-overlay="filters"></div>
  <aside id="filters" class="drawer left-0 -translate-x-full lg:static lg:z-auto lg:max-w-none lg:translate-x-0 lg:bg-transparent lg:shadow-none" aria-label="Filters">
    <form method="get" action="<?= e($formAction) ?>" data-autosubmit class="flex h-full flex-col">
      <?php if ($cat && !FL_STATIC): ?><input type="hidden" name="cat" value="<?= e($cat) ?>"><?php endif; ?>
      <?php if ($q !== ''): ?><input type="hidden" name="q" value="<?= e($q) ?>"><?php endif; ?>
      <?php if ($max !== null): ?><input type="hidden" name="max" value="<?= (int) $max ?>"><?php endif; ?>
      <input type="hidden" name="sort" value="<?= e($sort === 'featured' ? '' : $sort) ?>">
      <div class="flex items-center justify-between border-b border-line px-5 py-4 lg:hidden">
        <p class="font-serif text-2xl text-forest">Filters</p>
        <button type="button" data-close class="grid h-10 w-10 place-items-center rounded-full hover:bg-cream" aria-label="Close filters"><?= fl_icon('close') ?></button>
      </div>
      <div class="flex-1 space-y-8 overflow-y-auto px-5 py-6 lg:overflow-visible lg:p-0">
        <div>
          <p class="mb-4 text-xs font-semibold uppercase tracking-widest text-forest">Category</p>
          <ul class="space-y-2.5 text-sm">
            <?php foreach (['women', 'men'] as $g): ?>
              <li>
                <a href="<?= fl_shop_link(['cat' => $g]) ?>" class="flex justify-between font-medium <?= $cat === $g ? 'text-moss' : 'text-forest hover:text-moss' ?>"><?= e(FL_CATEGORIES[$g]['label']) ?> <span class="text-ink-muted"><?= fl_category_count($g) ?></span></a>
                <ul class="mt-2 space-y-2 border-l border-line pl-4">
                  <?php foreach (FL_CATEGORIES as $slug => $c): if ($c['parent'] !== $g) continue; ?>
                    <li><a href="<?= fl_shop_link(['cat' => $slug]) ?>" class="flex justify-between <?= $cat === $slug ? 'font-medium text-moss' : 'text-ink-soft hover:text-forest' ?>"><?= e($c['label']) ?> <span class="text-ink-muted"><?= fl_category_count($slug) ?></span></a></li>
                  <?php endforeach; ?>
                </ul>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <fieldset>
          <legend class="mb-4 text-xs font-semibold uppercase tracking-widest text-forest">Size</legend>
          <div class="flex flex-wrap gap-2">
            <?php foreach (['S', 'M', 'L', 'XL'] as $s): ?>
              <label class="cursor-pointer">
                <input type="checkbox" name="size[]" value="<?= $s ?>" class="peer sr-only" <?= in_array($s, $sizes, true) ? 'checked' : '' ?>>
                <span class="grid h-11 min-w-[48px] place-items-center rounded-full border border-line bg-white px-3 text-sm font-medium text-forest transition peer-checked:border-forest peer-checked:bg-forest peer-checked:text-ivory peer-focus-visible:ring-2 peer-focus-visible:ring-moss"><?= $s ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </fieldset>
        <fieldset>
          <legend class="mb-4 text-xs font-semibold uppercase tracking-widest text-forest">Price</legend>
          <div class="space-y-3 text-sm">
            <label class="flex cursor-pointer items-center gap-3 text-ink-soft"><input type="radio" name="price" value="" class="text-forest focus:ring-moss" <?= $price === '' ? 'checked' : '' ?>> Any price</label>
            <?php foreach ($priceRanges as $key => $r): ?>
              <label class="flex cursor-pointer items-center gap-3 text-ink-soft"><input type="radio" name="price" value="<?= e($key) ?>" class="text-forest focus:ring-moss" <?= $price === $key ? 'checked' : '' ?>> <?= e($r['label']) ?></label>
            <?php endforeach; ?>
          </div>
        </fieldset>
        <div class="rounded-2xl bg-sage-pale p-5">
          <p class="font-serif text-xl text-forest">Can’t find your fit?</p>
          <p class="mt-1 text-sm text-ink-soft">We craft made-to-measure pieces.</p>
          <a href="<?= fl_url('custom-order') ?>" class="link-arrow mt-3 !text-xs">Custom order <?= fl_icon('arrow-r', 'h-3.5 w-3.5') ?></a>
        </div>
      </div>
      <div class="flex gap-3 border-t border-line p-5 lg:hidden">
        <a href="<?= fl_url('shop', ['cat' => $cat ?: null, 'q' => $q ?: null]) ?>" class="btn-outline flex-1">Clear</a>
        <button class="btn-primary flex-1">Apply</button>
      </div>
      <noscript><button class="btn-primary mt-6 hidden w-full lg:flex">Apply filters</button></noscript>
    </form>
  </aside>

  <!-- Results -->
  <div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
      <div class="flex items-center gap-3">
        <button type="button" data-open="filters" class="inline-flex items-center gap-2 rounded-full border border-line bg-white px-4 py-2 text-sm text-forest lg:hidden"><?= fl_icon('filter', 'h-4 w-4') ?> Filters</button>
        <p class="text-sm text-ink-muted"><span data-shop-count class="font-medium text-forest"><?= count($results) ?></span> <span data-shop-word><?= count($results) === 1 ? 'product' : 'products' ?></span></p>
      </div>
      <form method="get" action="<?= e($formAction) ?>" data-autosubmit class="flex items-center gap-2" data-sort-form>
        <?php foreach (['cat' => FL_STATIC ? '' : $cat, 'q' => $q, 'price' => $price] as $k => $v): if ($v !== '' && $v !== null): ?><input type="hidden" name="<?= $k ?>" value="<?= e((string) $v) ?>"><?php endif; endforeach; ?>
        <?php if ($max !== null): ?><input type="hidden" name="max" value="<?= (int) $max ?>"><?php endif; ?>
        <?php foreach ($sizes as $s): ?><input type="hidden" name="size[]" value="<?= e($s) ?>"><?php endforeach; ?>
        <label for="sort" class="text-xs uppercase tracking-widest text-ink-muted">Sort</label>
        <select id="sort" name="sort" class="rounded-full border-line bg-white py-2 pl-4 pr-9 text-sm text-forest focus:border-moss focus:ring-moss">
          <?php foreach (['featured' => 'Featured', 'new' => 'Newest', 'price-asc' => 'Price: Low to High', 'price-desc' => 'Price: High to Low'] as $k => $l): ?>
            <option value="<?= $k === 'featured' ? '' : $k ?>" <?= $sort === $k ? 'selected' : '' ?>><?= e($l) ?></option>
          <?php endforeach; ?>
        </select>
        <noscript><button class="rounded-full bg-forest px-4 py-2 text-xs text-ivory">Go</button></noscript>
      </form>
    </div>

    <?php if ($sizes || $price || $max !== null || $q !== ''): ?>
      <div class="mb-6 flex flex-wrap items-center gap-2 text-sm">
        <?php if ($q !== ''): ?><a href="<?= fl_shop_link(['q' => null]) ?>" class="inline-flex items-center gap-1.5 rounded-full bg-cream px-3 py-1.5 text-forest">“<?= e($q) ?>” <?= fl_icon('close', 'h-3.5 w-3.5') ?></a><?php endif; ?>
        <?php foreach ($sizes as $s): ?><a href="<?= fl_shop_link(['size' => array_values(array_diff($sizes, [$s]))]) ?>" class="inline-flex items-center gap-1.5 rounded-full bg-cream px-3 py-1.5 text-forest">Size <?= e($s) ?> <?= fl_icon('close', 'h-3.5 w-3.5') ?></a><?php endforeach; ?>
        <?php if ($price): ?><a href="<?= fl_shop_link(['price' => null]) ?>" class="inline-flex items-center gap-1.5 rounded-full bg-cream px-3 py-1.5 text-forest"><?= e($priceRanges[$price]['label']) ?> <?= fl_icon('close', 'h-3.5 w-3.5') ?></a><?php endif; ?>
        <?php if ($max !== null): ?><a href="<?= fl_shop_link(['max' => null]) ?>" class="inline-flex items-center gap-1.5 rounded-full bg-cream px-3 py-1.5 text-forest">Up to <?= fl_price($max) ?> <?= fl_icon('close', 'h-3.5 w-3.5') ?></a><?php endif; ?>
        <a href="<?= fl_url('shop', ['cat' => $cat ?: null]) ?>" class="ml-1 text-xs uppercase tracking-widest text-ink-muted underline underline-offset-4 hover:text-forest">Clear all</a>
      </div>
    <?php endif; ?>

    <div data-shop-chips class="mb-6 hidden flex-wrap items-center gap-2 text-sm"></div>
    <div data-shop-grid class="grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 xl:grid-cols-3 <?= $results ? '' : 'hidden' ?>">
      <?php foreach ($results as $p): ?><?php fl_partial('product-card', ['p' => $p, 'search' => fl_haystack($p)]); ?><?php endforeach; ?>
    </div>
      <div data-shop-empty class="rounded-3xl bg-cream/70 px-6 py-16 text-center <?= $results ? 'hidden' : '' ?>">
        <p class="font-serif text-3xl text-forest">Nothing here — yet</p>
        <p class="mx-auto mt-2 max-w-md text-sm text-ink-soft">We couldn’t find pieces matching these filters. Try removing a filter, or let us craft something just for you.</p>
        <div class="mt-6 flex flex-wrap justify-center gap-3">
          <a href="<?= fl_url('shop') ?>" class="btn-primary">View all products</a>
          <a href="<?= fl_url('custom-order') ?>" class="btn-outline">Custom order</a>
        </div>
      </div>
  </div>
</div>

<?php fl_partial('footer'); ?>
