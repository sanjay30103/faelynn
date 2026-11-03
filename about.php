<?php
require __DIR__ . '/inc/bootstrap.php';
$page_title = 'Our Story | Faelynn';
$page_desc = 'Faelynn was born from a simple wish — to slow down and find beauty in the quiet. Soft, soulful clothing, made by hand by Indian artisans.';
fl_partial('header', compact('page_title', 'page_desc'));
?>

<!-- Hero -->
<section class="relative overflow-hidden bg-forest text-ivory">
  <img src="assets/images/products/img-9807.jpg" alt="" class="absolute inset-0 h-full w-full object-cover opacity-30 mix-blend-luminosity">
  <div class="absolute inset-0 bg-gradient-to-r from-forest via-forest/80 to-forest/30"></div>
  <div class="container relative py-20 lg:py-32">
    <nav class="mb-6 text-xs text-ivory/60" aria-label="Breadcrumb"><a href="<?= fl_url() ?>" class="hover:text-ivory">Home</a> <span class="mx-1">/</span> <span class="text-ivory">Our Story</span></nav>
    <p class="text-[11px] font-medium uppercase tracking-widest2 text-sand">About Faelynn</p>
    <h1 class="mt-4 max-w-3xl font-serif text-5xl font-medium leading-[0.95] sm:text-7xl">Soft, soulful<br><em class="text-sand">&amp; made by hand</em></h1>
    <p class="mt-6 max-w-xl text-lg text-ivory/80"><?= e(FL_STORY[0]) ?></p>
  </div>
</section>

<!-- Story -->
<section class="py-16 lg:py-24">
  <div class="container grid items-center gap-12 lg:grid-cols-2">
    <div class="relative">
      <img src="assets/images/brand/Designer-3.jpg" alt="Women relaxing in Faelynn loungewear" class="aspect-[4/5] w-full rounded-[2rem] object-cover lg:aspect-[4/4.6]">
      <div class="absolute -bottom-8 -right-4 hidden w-48 overflow-hidden rounded-3xl border-8 border-ivory shadow-soft sm:block lg:-right-10">
        <img src="assets/images/products/haizead5.jpg" alt="Queen Bee hand embroidery" class="aspect-square w-full object-cover">
      </div>
    </div>
    <div>
      <p class="eyebrow">Our story</p>
      <h2 class="mt-3 font-serif text-4xl font-medium leading-tight text-forest sm:text-5xl">Love for <em class="text-moss">nature</em></h2>
      <div class="prose-faelynn mt-6">
        <?php foreach (array_slice(FL_STORY, 1) as $para): ?><p><?= e($para) ?></p><?php endforeach; ?>
      </div>
      <img src="assets/images/brand/faelynn-logo.png" alt="Faelynn" class="mt-6 h-12 w-auto opacity-80">
    </div>
  </div>
</section>

<!-- Values -->
<section class="bg-sage-pale py-16 lg:py-20">
  <div class="container grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <?php foreach (FL_USPS as $u): ?>
      <div class="rounded-3xl bg-ivory p-7 shadow-card">
        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-sage-pale text-moss"><?= fl_icon($u['icon'], 'h-6 w-6') ?></span>
        <p class="mt-5 font-serif text-2xl text-forest"><?= e($u['title']) ?></p>
        <p class="mt-1 text-sm text-ink-soft"><?= e($u['text']) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Workshop -->
<section class="py-16 lg:py-24">
  <div class="container grid items-center gap-12 lg:grid-cols-2">
    <div class="order-2 lg:order-1">
      <p class="eyebrow">The workshop</p>
      <h2 class="mt-3 font-serif text-4xl font-medium leading-tight text-forest sm:text-5xl">Where clothing is <em class="text-moss">lived into being</em></h2>
      <div class="prose-faelynn mt-6">
        <?php foreach (FL_WORKSHOP as $para): ?><p><?= e($para) ?></p><?php endforeach; ?>
      </div>
    </div>
    <div class="order-1 grid grid-cols-2 gap-4 lg:order-2">
      <img src="assets/images/products/belinda6.jpg" alt="Floral print fabric detail" class="aspect-[3/4] w-full rounded-3xl object-cover">
      <img src="assets/images/products/fae4.jpg" alt="Hand embroidery detail" class="mt-10 aspect-[3/4] w-full rounded-3xl object-cover">
      <img src="assets/images/products/img-9836.jpg" alt="Daisy embroidery detail" class="-mt-10 aspect-[3/4] w-full rounded-3xl object-cover">
      <img src="assets/images/products/kendyl5.jpg" alt="Pixelated floral print detail" class="aspect-[3/4] w-full rounded-3xl object-cover">
    </div>
  </div>
</section>

<!-- CTA -->
<section class="container pb-16 lg:pb-24">
  <div class="grid gap-6 md:grid-cols-2">
    <a href="<?= fl_category_url('women') ?>" class="group relative block overflow-hidden rounded-[2rem]">
      <img src="assets/images/brand/Designer-7.jpg" alt="" class="h-80 w-full object-cover object-[50%_25%] transition duration-700 group-hover:scale-105 lg:h-[420px]">
      <div class="absolute inset-0 bg-gradient-to-t from-forest/80 to-transparent"></div>
      <div class="absolute bottom-0 p-8 text-ivory"><p class="font-serif text-4xl">Shop Women</p><p class="mt-1 text-sm text-ivory/80">Dresses · Co-ord sets · Night dresses</p></div>
    </a>
    <a href="<?= fl_category_url('men') ?>" class="group relative block overflow-hidden rounded-[2rem]">
      <img src="assets/images/brand/Designer-8.jpg" alt="" class="h-80 w-full object-cover object-[50%_20%] transition duration-700 group-hover:scale-105 lg:h-[420px]">
      <div class="absolute inset-0 bg-gradient-to-t from-forest/80 to-transparent"></div>
      <div class="absolute bottom-0 p-8 text-ivory"><p class="font-serif text-4xl">Shop Men</p><p class="mt-1 text-sm text-ivory/80">Shirts · Trousers · Shorts</p></div>
    </a>
  </div>
</section>

<?php fl_partial('footer'); ?>
