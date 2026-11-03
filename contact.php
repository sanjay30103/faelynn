<?php
require __DIR__ . '/inc/bootstrap.php';
$page_title = 'Contact Us | Faelynn';
fl_partial('header', compact('page_title'));
fl_partial('page-hero', ['title' => 'You can ask us anything', 'crumb' => 'Contact', 'eyebrow' => 'Contact us', 'text' => 'Thank you for reaching out! Fill in the form and we will get back to you as soon as possible.']);
?>
<section class="container py-12 lg:py-16">
  <div class="grid gap-10 lg:grid-cols-12">
    <div class="space-y-4 lg:col-span-5">
      <?php foreach ([
        ['phone', 'Call or WhatsApp', FL_PHONE, 'tel:' . FL_PHONE_RAW, FL_HOURS],
        ['mail', 'Email', FL_EMAIL, 'mailto:' . FL_EMAIL, 'We will gladly assist you in the shortest time.'],
        ['pin', 'Bengaluru office', FL_ADDRESS, null, 'India'],
      ] as [$ic, $t, $v, $href, $sub]): ?>
        <div class="flex gap-5 rounded-3xl bg-white p-6 shadow-card">
          <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-sage-pale text-moss"><?= fl_icon($ic, 'h-5 w-5') ?></span>
          <div class="min-w-0">
            <p class="text-xs font-medium uppercase tracking-widest text-ink-muted"><?= e($t) ?></p>
            <?php if ($href): ?><a href="<?= e($href) ?>" class="mt-1 block break-all font-serif text-2xl text-forest hover:text-moss"><?= e($v) ?></a><?php else: ?><p class="mt-1 font-serif text-2xl text-forest"><?= e($v) ?></p><?php endif; ?>
            <p class="mt-1 text-sm text-ink-soft"><?= e($sub) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
      <a href="https://wa.me/<?= FL_PHONE_RAW ?>" target="_blank" rel="noopener" class="btn-primary w-full"><?= fl_social_icon('whatsapp', 'h-4 w-4') ?> Chat on WhatsApp</a>
      <p class="text-center text-xs text-ink-muted">For PR / sourcing requests, please write to <?= FL_EMAIL ?></p>
    </div>

    <form class="rounded-[2rem] bg-cream/70 p-6 sm:p-10 lg:col-span-7" data-demo-form="Thank you! We’ll get back to you shortly." novalidate>
      <p class="font-serif text-3xl text-forest">Get in touch</p>
      <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <div><label class="label" for="c-name">Your name *</label><input class="field" id="c-name" required autocomplete="name"></div>
        <div><label class="label" for="c-email">Your email *</label><input class="field" id="c-email" type="email" required autocomplete="email"></div>
        <div class="sm:col-span-2"><label class="label" for="c-subject">Subject *</label><input class="field" id="c-subject" required></div>
        <div class="sm:col-span-2"><label class="label" for="c-msg">Your message</label><textarea class="field min-h-[160px]" id="c-msg"></textarea></div>
      </div>
      <button class="btn-primary mt-6">Send message</button>
      <p data-form-success class="mt-4 hidden rounded-xl bg-sage-pale px-4 py-3 text-sm text-moss">✓ Thank you for reaching out — our team will reply within one business day.</p>
    </form>
  </div>
</section>
<?php fl_partial('footer'); ?>
