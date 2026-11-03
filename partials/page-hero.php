<?php
/** @var string $title  @var string|null $eyebrow  @var string|null $text */
?>
<section class="relative overflow-hidden bg-cream">
  <svg class="pointer-events-none absolute -right-16 -top-10 h-72 w-72 text-sand" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width=".7" aria-hidden="true"><path d="M100 190C100 120 60 80 20 60M100 190c0-70 40-110 80-130M100 190V30M100 120c-20-10-35-30-40-55M100 120c20-10 35-30 40-55M100 80c-12-8-20-20-22-35M100 80c12-8 20-20 22-35"/></svg>
  <div class="container relative py-12 lg:py-16">
    <nav class="mb-4 text-xs text-ink-muted" aria-label="Breadcrumb"><a href="<?= fl_url() ?>" class="hover:text-forest">Home</a> <span class="mx-1">/</span> <span class="text-forest"><?= e($crumb ?? $title) ?></span></nav>
    <?php if (!empty($eyebrow)): ?><p class="eyebrow"><?= e($eyebrow) ?></p><?php endif; ?>
    <h1 class="mt-2 font-serif text-4xl font-medium leading-tight text-forest sm:text-6xl"><?= e($title) ?></h1>
    <?php if (!empty($text)): ?><p class="mt-3 max-w-2xl text-ink-soft"><?= e($text) ?></p><?php endif; ?>
  </div>
</section>
