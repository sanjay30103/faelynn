</main>

<!-- Trust strip -->
<section class="border-t border-line bg-cream/60">
  <div class="container grid grid-cols-2 gap-6 py-8 lg:grid-cols-4">
    <?php foreach ([
      ['truck', 'Free Delivery', 'On all orders across India'],
      ['return', 'Easy Returns', 'Within 10 days of purchase'],
      ['leaf', '100% Cotton', 'Soft, breathable & skin-friendly'],
      ['scissors', 'Custom Orders', 'Made to your measurements'],
    ] as [$ic, $t, $s]): ?>
      <div class="flex items-center gap-3">
        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-ivory text-moss"><?= fl_icon($ic, 'h-5 w-5') ?></span>
        <div><p class="text-sm font-medium text-forest"><?= e($t) ?></p><p class="text-xs text-ink-muted"><?= e($s) ?></p></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<footer class="bg-forest text-ivory/80">
  <!-- Popular searches -->
  <div class="container border-b border-ivory/10 py-10">
    <p class="mb-5 font-serif text-2xl text-ivory">Popular Searches</p>
    <div class="space-y-4 text-[13px] leading-7">
      <?php foreach (FL_POPULAR_SEARCHES as $group => $links): ?>
        <div>
          <p class="mb-1 text-[11px] font-semibold uppercase tracking-widest2 text-sage"><?= e($group) ?></p>
          <p class="text-ivory/60">
            <?php foreach ($links as $i => [$label, $href]): ?><?= $i ? '<span class="mx-2 text-ivory/20">|</span>' : '' ?><a href="<?= e($href) ?>" class="hover:text-ivory"><?= e($label) ?></a><?php endforeach; ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="container grid gap-10 py-14 lg:grid-cols-12">
    <div class="lg:col-span-4">
      <img src="assets/images/brand/faelynn-logo-light.png" alt="Faelynn — love for nature" class="h-14 w-auto" loading="lazy">
      <p class="mt-5 max-w-sm text-sm leading-relaxed text-ivory/65">Premium handcrafted clothing for men &amp; women — soft, breathable cotton, finished by the hands of Indian artisans.</p>
      <div class="mt-6 flex gap-3">
        <a href="<?= FL_INSTAGRAM ?>" target="_blank" rel="noopener" aria-label="Instagram" class="grid h-10 w-10 place-items-center rounded-full border border-ivory/20 hover:bg-ivory hover:text-forest"><?= fl_social_icon('instagram') ?></a>
        <a href="<?= FL_FACEBOOK ?>" target="_blank" rel="noopener" aria-label="Facebook" class="grid h-10 w-10 place-items-center rounded-full border border-ivory/20 hover:bg-ivory hover:text-forest"><?= fl_social_icon('facebook') ?></a>
        <a href="https://wa.me/<?= FL_PHONE_RAW ?>" target="_blank" rel="noopener" aria-label="WhatsApp" class="grid h-10 w-10 place-items-center rounded-full border border-ivory/20 hover:bg-ivory hover:text-forest"><?= fl_social_icon('whatsapp') ?></a>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-5">
      <div>
        <p class="mb-4 text-[11px] font-semibold uppercase tracking-widest2 text-ivory">Shop</p>
        <ul class="space-y-2.5 text-sm">
          <li><a class="hover:text-ivory" href="<?= fl_category_url('women') ?>">Women</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_category_url('men') ?>">Men</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_category_url('dresses-women') ?>">Dresses</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_category_url('co-ord-set-women') ?>">Co-ord Sets</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_category_url('night-dress-women') ?>">Night Dresses</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_category_url('shirt-men') ?>">Men’s Shirts</a></li>
        </ul>
      </div>
      <div>
        <p class="mb-4 text-[11px] font-semibold uppercase tracking-widest2 text-ivory">Help</p>
        <ul class="space-y-2.5 text-sm">
          <li><a class="hover:text-ivory" href="<?= fl_url('faq') ?>">FAQs</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_url('track-order') ?>">Track Order</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_url('policy', ['type' => 'shipping']) ?>">Shipping</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_url('policy', ['type' => 'returns']) ?>">Returns &amp; Refunds</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_url('policy', ['type' => 'wash-care']) ?>">Wash Care</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_url('contact') ?>">Contact Us</a></li>
        </ul>
      </div>
      <div>
        <p class="mb-4 text-[11px] font-semibold uppercase tracking-widest2 text-ivory">Faelynn</p>
        <ul class="space-y-2.5 text-sm">
          <li><a class="hover:text-ivory" href="<?= fl_url('about') ?>">Our Story</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_url('custom-order') ?>">Custom Orders</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_url('journal') ?>">Journal</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_url('policy', ['type' => 'privacy']) ?>">Privacy Policy</a></li>
          <li><a class="hover:text-ivory" href="<?= fl_url('policy', ['type' => 'terms']) ?>">Terms &amp; Conditions</a></li>
        </ul>
      </div>
    </div>

    <div class="lg:col-span-3">
      <p class="mb-2 text-[11px] font-semibold uppercase tracking-widest2 text-ivory">Be the first to know</p>
      <p class="mb-4 text-sm text-ivory/65">New collections, stories from the workshop and members-only offers.</p>
      <form class="flex rounded-full border border-ivory/25 p-1 focus-within:border-ivory/60" data-newsletter novalidate>
        <label for="nl-email" class="sr-only">Email address</label>
        <input id="nl-email" type="email" required placeholder="Your email address" class="min-w-0 flex-1 border-0 bg-transparent px-4 text-sm text-ivory placeholder:text-ivory/40 focus:ring-0">
        <button class="rounded-full bg-ivory px-5 py-2.5 text-xs font-semibold uppercase tracking-widest text-forest hover:bg-sand">Subscribe</button>
      </form>
      <ul class="mt-7 space-y-3 text-sm">
        <li class="flex gap-3"><?= fl_icon('phone', 'mt-0.5 h-4 w-4 shrink-0 text-sage') ?><a href="tel:<?= FL_PHONE_RAW ?>" class="hover:text-ivory"><?= FL_PHONE ?></a></li>
        <li class="flex gap-3"><?= fl_icon('mail', 'mt-0.5 h-4 w-4 shrink-0 text-sage') ?><a href="mailto:<?= FL_EMAIL ?>" class="break-all hover:text-ivory"><?= FL_EMAIL ?></a></li>
        <li class="flex gap-3"><?= fl_icon('pin', 'mt-0.5 h-4 w-4 shrink-0 text-sage') ?><span><?= FL_ADDRESS ?></span></li>
        <li class="flex gap-3"><?= fl_icon('clock', 'mt-0.5 h-4 w-4 shrink-0 text-sage') ?><span><?= FL_HOURS ?></span></li>
      </ul>
    </div>
  </div>

  <div class="border-t border-ivory/10">
    <div class="container flex flex-col items-center justify-between gap-3 py-6 text-xs text-ivory/50 sm:flex-row">
      <p>© <?= date('Y') ?> Faelynn. All rights reserved.</p>
      <p class="flex items-center gap-2"><img src="assets/images/brand/dragonfly-light.png" alt="" class="h-4 w-auto opacity-60"> Love for nature · Made in India</p>
    </div>
  </div>
</footer>

<!-- Back to top -->
<button type="button" data-back-top class="fixed bottom-[5.75rem] right-[1.5625rem] z-40 grid h-11 w-11 translate-y-20 place-items-center rounded-full bg-forest text-ivory opacity-0 shadow-soft transition duration-300" aria-label="Back to top">
  <?= fl_icon('arrow-l', 'h-4 w-4 rotate-90') ?>
</button>

<?php fl_partial('chat'); ?>

<!-- Cart drawer -->
<div class="overlay hidden" data-overlay="cart"></div>
<aside id="cart" class="drawer right-0 translate-x-full" aria-label="Shopping bag" aria-hidden="true" role="dialog">
  <div class="flex items-center justify-between border-b border-line px-6 py-5">
    <p class="font-serif text-2xl text-forest">Your Bag <span data-cart-count-text class="font-sans text-sm text-ink-muted"></span></p>
    <button type="button" data-close class="grid h-10 w-10 place-items-center rounded-full hover:bg-cream" aria-label="Close bag"><?= fl_icon('close') ?></button>
  </div>
  <div class="bg-sage-pale px-6 py-3 text-xs text-moss"><span class="inline-flex items-center gap-2"><?= fl_icon('truck', 'h-4 w-4') ?> You’ve unlocked <strong>free delivery</strong> on this order.</span></div>
  <div data-cart-items class="flex-1 overflow-y-auto px-6"></div>
  <div data-cart-footer class="border-t border-line bg-white px-6 py-5">
    <div class="mb-1 flex justify-between text-sm"><span class="text-ink-soft">Subtotal</span><span data-cart-subtotal class="font-semibold text-forest"></span></div>
    <p class="mb-4 text-xs text-ink-muted">Inclusive of all taxes. Shipping calculated at checkout.</p>
    <a href="<?= fl_url('checkout') ?>" class="btn-primary w-full">Checkout</a>
    <a href="<?= fl_url('cart') ?>" class="mt-2 block text-center text-xs font-medium uppercase tracking-widest text-forest underline-offset-4 hover:underline">View bag</a>
  </div>
</aside>

<!-- Quick add (size picker) -->
<div class="overlay hidden" data-overlay="quick-add"></div>
<div id="quick-add" class="fixed inset-x-0 bottom-0 z-[70] rounded-t-3xl bg-ivory p-6 shadow-2xl sm:inset-auto sm:left-1/2 sm:top-1/2 sm:w-[440px] sm:rounded-3xl" aria-hidden="true" role="dialog" aria-label="Choose size">
  <button type="button" data-close class="absolute right-4 top-4 grid h-9 w-9 place-items-center rounded-full hover:bg-cream" aria-label="Close"><?= fl_icon('close', 'h-4 w-4') ?></button>
  <div class="flex gap-4">
    <img data-qa-img src="" alt="" class="h-28 w-20 shrink-0 rounded-xl bg-cream object-cover object-top">
    <div class="pr-8">
      <p data-qa-cat class="eyebrow"></p>
      <p data-qa-name class="mt-1 font-serif text-xl leading-tight text-forest"></p>
      <p data-qa-price class="mt-1 text-sm font-medium text-forest"></p>
    </div>
  </div>
  <p class="mb-3 mt-6 text-xs font-medium uppercase tracking-widest text-ink-soft">Select size</p>
  <div data-qa-sizes class="flex flex-wrap gap-2"></div>
  <p data-qa-error class="mt-2 hidden text-xs text-clay">Please select a size.</p>
  <button type="button" data-qa-add class="btn-primary mt-6 w-full"><?= fl_icon('bag', 'h-4 w-4') ?> Add to bag</button>
  <a data-qa-link href="#" class="mt-3 block text-center text-xs font-medium uppercase tracking-widest text-forest underline-offset-4 hover:underline">View full details</a>
</div>

<!-- Toast -->
<div data-toast class="pointer-events-none fixed left-1/2 top-24 z-[90] flex -translate-x-1/2 -translate-y-4 items-center gap-3 rounded-full bg-forest px-5 py-3 text-sm text-ivory opacity-0 shadow-soft transition duration-300" role="status" aria-live="polite"></div>

<script>window.FAELYNN = <?= json_encode(['products' => fl_products_js(), 'urls' => ['cart' => fl_url('cart'), 'checkout' => fl_url('checkout'), 'shop' => fl_url('shop'), 'women' => fl_category_url('women'), 'men' => fl_category_url('men')]], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?>;</script>
<script src="<?= fl_asset('assets/vendor/swiper/swiper-bundle.min.js') ?>"></script>
<script src="<?= fl_asset('assets/js/app.js') ?>"></script>
</body>
</html>
