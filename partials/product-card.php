<?php
/** @var array $p  Product */
$img1 = $p['images'][0];
$img2 = $p['images'][1] ?? $p['images'][0];
$tags = array_map('strtolower', $p['tags']);
$badge = null;
if (in_array('new arrivals', $tags, true) || in_array($p['slug'], FL_GROUPS['new_men'], true)) {
    $badge = 'New';
} elseif (array_intersect(['hand work', 'hand embroidered', 'embroidery'], $tags)) {
    $badge = 'Handcrafted';
}
$url = fl_product_url($p);
?>
<article class="pcard group relative flex h-full flex-col" data-id="<?= (int) $p['id'] ?>" data-price="<?= (int) $p['price'] ?>" data-sizes="<?= e(implode(' ', $p['sizes'])) ?>"<?= isset($search) ? ' data-search="' . e($search) . '"' : '' ?>>
  <div class="relative aspect-[3/4] overflow-hidden rounded-2xl bg-[#F1EFEC]">
    <a href="<?= e($url) ?>" class="block h-full" tabindex="-1" aria-hidden="true">
      <img src="<?= e($img1['src']) ?>" alt="<?= e($p['short_name']) ?>" class="img-1 absolute inset-0 h-full w-full object-cover object-top transition duration-700" loading="lazy">
      <img src="<?= e($img2['src']) ?>" alt="" class="img-2 absolute inset-0 h-full w-full object-cover object-top" loading="lazy">
    </a>
    <?php if ($badge): ?>
      <span class="absolute left-3 top-3 rounded-full bg-ivory/95 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-forest"><?= e($badge) ?></span>
    <?php endif; ?>
    <button type="button" data-wishlist="<?= e($p['slug']) ?>" aria-pressed="false" aria-label="Add <?= e($p['short_name']) ?> to wishlist" class="absolute right-3 top-3 z-10 grid h-9 w-9 place-items-center rounded-full bg-ivory/95 text-forest transition hover:scale-110 aria-pressed:text-clay [&[aria-pressed=true]_svg]:fill-current">
      <?= fl_icon('heart', 'h-[18px] w-[18px]') ?>
    </button>
    <!-- Desktop quick size add -->
    <div class="absolute inset-x-3 bottom-3 z-10 hidden translate-y-3 rounded-xl bg-ivory/95 p-3 opacity-0 shadow-card backdrop-blur transition duration-300 group-hover:translate-y-0 group-hover:opacity-100 lg:block [@media(hover:none)]:!hidden">
      <p class="mb-2 text-center text-[10px] font-semibold uppercase tracking-widest text-ink-soft">Quick add · select size</p>
      <div class="flex justify-center gap-1.5">
        <?php foreach ($p['sizes'] as $s): ?>
          <button type="button" data-add="<?= e($p['slug']) ?>" data-size="<?= e($s) ?>" class="grid h-8 min-w-[36px] place-items-center rounded-full border border-line bg-white px-2 text-xs font-medium text-forest transition hover:border-forest hover:bg-forest hover:text-ivory"><?= e($s) ?></button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <div class="flex flex-1 flex-col pt-3">
    <p class="text-[10px] font-medium uppercase tracking-widest2 text-moss"><?= e(fl_category_label($p)) ?></p>
    <h3 class="mt-1 font-serif text-lg leading-snug text-forest sm:text-xl">
      <a href="<?= e($url) ?>" class="after:absolute after:inset-0 after:content-[''] focus:outline-none"><?= e($p['short_name']) ?></a>
    </h3>
    <div class="mt-auto flex items-center justify-between gap-2 pt-2">
      <p class="text-sm font-medium text-forest"><?= fl_price($p['price']) ?></p>
      <button type="button" data-quick-add="<?= e($p['slug']) ?>" class="relative z-10 inline-flex items-center gap-1.5 rounded-full border border-forest/80 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-widest text-forest transition hover:bg-forest hover:text-ivory">
        <?= fl_icon('bag', 'h-3.5 w-3.5') ?><span class="sm:hidden">Add</span><span class="hidden sm:inline">Add to bag</span>
      </button>
    </div>
  </div>
</article>
