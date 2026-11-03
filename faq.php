<?php
require __DIR__ . '/inc/bootstrap.php';
$page_title = 'FAQs | Faelynn';
fl_partial('header', compact('page_title'));
fl_partial('page-hero', ['title' => 'How can we help you?', 'crumb' => 'FAQs', 'eyebrow' => 'Frequently asked questions']);
?>
<section class="container grid gap-10 py-12 lg:grid-cols-12 lg:py-16">
  <nav class="lg:col-span-3" aria-label="FAQ topics">
    <ul class="flex gap-2 overflow-x-auto lg:sticky lg:top-28 lg:flex-col">
      <?php foreach (array_keys(FL_FAQ) as $topic): ?>
        <li><a href="#<?= e(strtolower(str_replace(' ', '-', $topic))) ?>" class="block shrink-0 rounded-full border border-line bg-white px-4 py-2 text-sm text-forest hover:border-forest lg:rounded-xl"><?= e($topic) ?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>
  <div class="space-y-12 lg:col-span-9">
    <?php foreach (FL_FAQ as $topic => $items): ?>
      <div id="<?= e(strtolower(str_replace(' ', '-', $topic))) ?>" class="scroll-mt-28">
        <h2 class="mb-2 font-serif text-3xl text-forest"><?= e($topic) ?></h2>
        <div class="divide-y divide-line border-y border-line">
          <?php foreach ($items as $i => $f): ?>
            <details class="acc" <?= $i === 0 ? 'open' : '' ?>>
              <summary class="!normal-case !tracking-normal"><span class="text-base"><?= e($f['q']) ?></span><span class="acc-icon"><?= fl_icon('plus', 'h-4 w-4') ?></span></summary>
              <p class="pb-5 text-sm leading-relaxed text-ink-soft"><?= e($f['a']) ?></p>
            </details>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
    <div class="rounded-3xl bg-sage-pale p-8">
      <p class="font-serif text-2xl text-forest">Still have a question?</p>
      <p class="mt-1 text-sm text-ink-soft">Call or WhatsApp <?= FL_PHONE ?> (<?= FL_HOURS ?>) or write to <?= FL_EMAIL ?>.</p>
      <a href="<?= fl_url('contact') ?>" class="btn-primary mt-5">Contact us</a>
    </div>
  </div>
</section>
<?php fl_partial('footer'); ?>
