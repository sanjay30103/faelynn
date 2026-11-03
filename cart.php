<?php
require __DIR__ . '/inc/bootstrap.php';
$page_title = 'Your Bag | Faelynn';
fl_partial('header', compact('page_title'));
fl_partial('page-hero', ['title' => 'Your Bag', 'crumb' => 'Bag']);
?>
<section class="container py-10 lg:py-14" data-cart-page>
  <div class="grid gap-10 lg:grid-cols-12">
    <div class="lg:col-span-8">
      <div data-cart-page-items class="divide-y divide-line border-y border-line"></div>
      <a href="<?= fl_url('shop') ?>" class="link-arrow mt-6"><?= fl_icon('arrow-l', 'h-4 w-4') ?> Continue shopping</a>
    </div>
    <aside data-cart-page-summary class="hidden lg:col-span-4">
      <div class="rounded-3xl bg-white p-6 shadow-card lg:sticky lg:top-28">
        <p class="font-serif text-2xl text-forest">Order summary</p>
        <dl class="mt-5 space-y-3 text-sm">
          <div class="flex justify-between"><dt class="text-ink-soft">Items (<span data-summary-count></span>)</dt><dd data-summary-subtotal class="text-forest"></dd></div>
          <div class="flex justify-between"><dt class="text-ink-soft">Delivery</dt><dd class="font-medium text-moss">Free</dd></div>
          <div class="flex justify-between border-t border-line pt-4 text-base"><dt class="font-medium text-forest">Total</dt><dd data-summary-total class="font-serif text-2xl text-forest"></dd></div>
        </dl>
        <p class="mt-1 text-xs text-ink-muted">Inclusive of all taxes</p>
        <a href="<?= fl_url('checkout') ?>" class="btn-primary mt-6 w-full">Proceed to checkout</a>
        <div class="mt-6 space-y-3 border-t border-line pt-5 text-xs text-ink-soft">
          <p class="flex items-center gap-2"><?= fl_icon('shield', 'h-4 w-4 text-moss') ?> Safe &amp; secure checkout</p>
          <p class="flex items-center gap-2"><?= fl_icon('return', 'h-4 w-4 text-moss') ?> Easy returns within 10 days</p>
          <p class="flex items-center gap-2"><?= fl_icon('user', 'h-4 w-4 text-moss') ?> <span><a href="<?= fl_url('account') ?>" class="underline underline-offset-2">Sign up</a> &amp; get ₹100 off</span></p>
        </div>
      </div>
    </aside>
  </div>
</section>
<?php fl_partial('footer'); ?>
