<?php
require __DIR__ . '/inc/bootstrap.php';
$page_title = 'Custom Order — Made to Measure | Faelynn';
$page_desc = 'Custom-designed dresses tailored to your unique desires and perfect fit. Share your style, colours, fabric and measurements.';
fl_partial('header', compact('page_title', 'page_desc'));
?>
<section class="relative overflow-hidden bg-sand">
  <div class="container grid items-center gap-10 py-14 lg:grid-cols-2 lg:py-20">
    <div>
      <nav class="mb-5 text-xs text-ink-soft" aria-label="Breadcrumb"><a href="<?= fl_url() ?>" class="hover:text-forest">Home</a> <span class="mx-1">/</span> <span class="text-forest">Custom Order</span></nav>
      <p class="eyebrow">Bespoke · Made to measure</p>
      <h1 class="mt-3 font-serif text-5xl font-medium leading-[0.95] text-forest sm:text-6xl">Where luxury meets<br><em class="text-clay">bespoke elegance</em></h1>
      <p class="mt-6 max-w-lg text-ink-soft">At Faelynn, we specialise in custom-designed pieces tailored to your unique desires and perfect fit. Dream of a flowing gown or a delicate set? Share your vision with us — your style, preferred colours, fabric and measurements.</p>
      <p class="mt-3 max-w-lg text-ink-soft">Let us craft a made-to-measure piece that reflects your essence and feels as magical as it looks. <strong class="text-forest">Every dream deserves a personal touch.</strong></p>
      <a href="#custom-form" class="btn-primary mt-8">Start your custom order</a>
    </div>
    <div class="relative mx-auto h-[420px] w-full max-w-md">
      <img src="assets/images/products/fae1.jpg" alt="Fae dress" class="absolute left-0 top-0 h-[85%] w-[60%] rounded-t-[999px] object-cover shadow-soft">
      <img src="assets/images/products/fae4.jpg" alt="Hand embroidery detail" class="absolute bottom-0 right-0 h-[60%] w-[52%] rounded-3xl border-8 border-sand object-cover shadow-soft">
      <img src="assets/images/brand/dragonfly.png" alt="" class="absolute right-10 top-6 h-12 w-auto animate-float opacity-70">
    </div>
  </div>
</section>

<!-- Steps -->
<section class="container py-14 lg:py-20">
  <div class="grid gap-6 md:grid-cols-4">
    <?php foreach ([
      ['01', 'Share your vision', 'Tell us the style, colours and fabric you love.'],
      ['02', 'Send measurements', 'Use the chart below to measure yourself.'],
      ['03', 'We confirm details', 'Our team reaches out to confirm and seek your approval.'],
      ['04', 'Handcrafted for you', 'Your piece is made by hand and delivered to your door.'],
    ] as [$n, $t, $d]): ?>
      <div class="rounded-3xl border border-line bg-white p-6">
        <p class="font-serif text-4xl text-sand-dark"><?= $n ?></p>
        <p class="mt-3 font-serif text-xl text-forest"><?= e($t) ?></p>
        <p class="mt-1 text-sm text-ink-soft"><?= e($d) ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Size chart -->
<section id="size-chart" class="scroll-mt-28 bg-cream/70 py-14 lg:py-20">
  <div class="container grid gap-10 lg:grid-cols-12">
    <div class="lg:col-span-4">
      <p class="eyebrow">Size chart</p>
      <h2 class="mt-3 font-serif text-4xl text-forest">Measure your body</h2>
      <p class="mt-3 text-ink-soft">Measure your body as follows to choose the size that suits you best. All measurements are in inches.</p>
      <ul class="mt-5 space-y-2 text-sm text-ink-soft">
        <li><strong class="text-forest">Bust</strong> — around the fullest part of the chest.</li>
        <li><strong class="text-forest">Waist</strong> — around the natural waistline.</li>
        <li><strong class="text-forest">Hip</strong> — around the fullest part of the hips.</li>
        <li><strong class="text-forest">Shoulder</strong> — from one shoulder edge to the other.</li>
      </ul>
    </div>
    <div class="lg:col-span-8">
      <div class="overflow-x-auto rounded-3xl border border-line bg-white">
        <table class="w-full text-center">
          <thead class="bg-forest text-ivory"><tr><?php foreach (FL_SIZE_CHART['head'] as $h): ?><th class="px-4 py-4 text-xs font-medium uppercase tracking-widest"><?= e($h) ?></th><?php endforeach; ?></tr></thead>
          <tbody class="divide-y divide-line">
            <?php foreach (FL_SIZE_CHART['rows'] as $row): ?>
              <tr class="hover:bg-cream/50"><?php foreach ($row as $i => $cell): ?><td class="px-4 py-4 <?= $i === 0 ? 'font-serif text-xl text-forest' : 'text-ink-soft' ?>"><?= e($cell) ?></td><?php endforeach; ?></tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- Form -->
<section id="custom-form" class="container scroll-mt-28 py-14 lg:py-20">
  <form class="mx-auto max-w-3xl rounded-[2rem] bg-white p-6 shadow-card sm:p-10" data-demo-form="Thank you! Our team will contact you about your custom order." novalidate>
    <p class="eyebrow">Custom order contact</p>
    <p class="mt-2 font-serif text-4xl text-forest">We would love to hear from you</p>
    <div class="mt-8 grid gap-4 sm:grid-cols-2">
      <div><label class="label" for="co-n">Name *</label><input class="field" id="co-n" required autocomplete="name"></div>
      <div><label class="label" for="co-p">Phone / WhatsApp *</label><input class="field" id="co-p" type="tel" required autocomplete="tel"></div>
      <div class="sm:col-span-2"><label class="label" for="co-e">Email *</label><input class="field" id="co-e" type="email" required autocomplete="email"></div>
      <div><label class="label" for="co-t">What would you like?</label>
        <select class="field" id="co-t"><option>Dress</option><option>Co-ord set</option><option>Night dress</option><option>Men’s shirt &amp; trousers</option><option>Something else</option></select>
      </div>
      <div><label class="label" for="co-c">Preferred colours</label><input class="field" id="co-c" placeholder="e.g. soft blue, ivory"></div>
      <div class="grid grid-cols-2 gap-4 sm:col-span-2 sm:grid-cols-4">
        <?php foreach (['Bust', 'Waist', 'Hip', 'Shoulder'] as $m): ?>
          <div><label class="label" for="m-<?= strtolower($m) ?>"><?= $m ?> (in)</label><input class="field" id="m-<?= strtolower($m) ?>" inputmode="decimal"></div>
        <?php endforeach; ?>
      </div>
      <div class="sm:col-span-2"><label class="label" for="co-v">Describe your vision *</label><textarea class="field min-h-[140px]" id="co-v" required placeholder="Style, length, sleeves, embroidery, fabric…"></textarea></div>
    </div>
    <button class="btn-primary mt-6 w-full sm:w-auto">Send request</button>
    <p class="mt-4 text-xs text-ink-muted">Custom pieces are made just for you — cancellations are accepted within 24 hours of placing the order, and returns only in case of a defect. <a href="<?= fl_url('policy', ['type' => 'returns']) ?>" class="underline">Read policy</a>.</p>
    <p data-form-success class="mt-4 hidden rounded-xl bg-sage-pale px-4 py-3 text-sm text-moss">✓ Thank you! Our team will reach out to confirm the details of your custom order.</p>
  </form>
</section>
<?php fl_partial('footer'); ?>
