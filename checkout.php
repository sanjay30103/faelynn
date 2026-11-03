<?php
require __DIR__ . '/inc/bootstrap.php';
$page_title = 'Checkout | Faelynn';
$states = ['Andaman and Nicobar Islands', 'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chandigarh', 'Chhattisgarh', 'Dadra and Nagar Haveli and Daman and Diu', 'Delhi', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jammu and Kashmir', 'Jharkhand', 'Karnataka', 'Kerala', 'Ladakh', 'Lakshadweep', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Puducherry', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'];
fl_partial('header', compact('page_title'));
?>
<section class="container py-10 lg:py-14">
  <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
    <h1 class="font-serif text-4xl font-medium text-forest sm:text-5xl">Checkout</h1>
    <ol class="flex items-center gap-2 text-xs uppercase tracking-widest text-ink-muted">
      <li><a href="<?= fl_url('cart') ?>" class="hover:text-forest">Bag</a></li><li>›</li>
      <li class="font-semibold text-forest">Details</li><li>›</li>
      <li>Confirmation</li>
    </ol>
  </div>

  <!-- Empty -->
  <div data-checkout-empty class="hidden rounded-3xl bg-cream/70 px-6 py-16 text-center">
    <p class="font-serif text-3xl text-forest">Your bag is empty</p>
    <p class="mt-2 text-sm text-ink-soft">Add a few pieces before checking out.</p>
    <a href="<?= fl_url('shop') ?>" class="btn-primary mt-6">Shop the collection</a>
  </div>

  <!-- Done -->
  <div data-order-done class="hidden rounded-3xl bg-sage-pale px-6 py-16 text-center">
    <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-forest text-ivory"><?= fl_icon('check', 'h-8 w-8') ?></span>
    <p class="mt-6 eyebrow">Order placed</p>
    <p class="mt-2 font-serif text-4xl text-forest">Thank you for choosing Faelynn</p>
    <p class="mx-auto mt-3 max-w-lg text-ink-soft">Your order <strong data-order-no class="text-forest"></strong> for <strong data-order-total class="text-forest"></strong> has been received. A confirmation will be sent to <strong data-order-email class="text-forest"></strong>. Orders are dispatched within 3–4 business days.</p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
      <a href="<?= fl_url('shop') ?>" class="btn-primary">Continue shopping</a>
      <a href="<?= fl_url('track-order') ?>" class="btn-outline">Track order</a>
    </div>
    <p class="mt-6 text-xs text-ink-muted">Demo checkout — no payment was taken.</p>
  </div>

  <div class="grid gap-10 lg:grid-cols-12">
    <form data-checkout class="space-y-10 lg:col-span-7" novalidate>
      <fieldset>
        <legend class="mb-5 flex w-full items-center justify-between font-serif text-2xl text-forest">Contact <a href="<?= fl_url('account') ?>" class="font-sans text-xs uppercase tracking-widest text-moss underline underline-offset-4">Sign in</a></legend>
        <div class="grid gap-4 sm:grid-cols-2">
          <div><label class="label" for="co-email">Email</label><input class="field" id="co-email" name="email" type="email" required autocomplete="email" placeholder="you@example.com"></div>
          <div><label class="label" for="co-phone">Mobile number</label><input class="field" id="co-phone" name="phone" type="tel" required pattern="[6-9][0-9]{9}" maxlength="10" inputmode="numeric" autocomplete="tel-national" placeholder="10-digit mobile"></div>
        </div>
        <label class="mt-4 flex items-center gap-3 text-sm text-ink-soft"><input type="checkbox" checked class="rounded text-forest focus:ring-moss"> Email me about new collections &amp; offers</label>
      </fieldset>

      <fieldset>
        <legend class="mb-5 font-serif text-2xl text-forest">Delivery address</legend>
        <div class="grid gap-4 sm:grid-cols-2">
          <div><label class="label" for="co-fn">First name</label><input class="field" id="co-fn" name="first" required autocomplete="given-name"></div>
          <div><label class="label" for="co-ln">Last name</label><input class="field" id="co-ln" name="last" required autocomplete="family-name"></div>
          <div class="sm:col-span-2"><label class="label" for="co-addr">Address</label><input class="field" id="co-addr" name="address" required autocomplete="address-line1" placeholder="House no., building, street"></div>
          <div class="sm:col-span-2"><label class="label" for="co-addr2">Apartment, landmark (optional)</label><input class="field" id="co-addr2" name="address2" autocomplete="address-line2"></div>
          <div><label class="label" for="co-city">City</label><input class="field" id="co-city" name="city" required autocomplete="address-level2"></div>
          <div><label class="label" for="co-pin">PIN code</label><input class="field" id="co-pin" name="pin" required pattern="[1-9][0-9]{5}" maxlength="6" inputmode="numeric" autocomplete="postal-code"></div>
          <div class="sm:col-span-2"><label class="label" for="co-state">State</label>
            <select class="field" id="co-state" name="state" required autocomplete="address-level1">
              <option value="">Select state</option>
              <?php foreach ($states as $s): ?><option><?= e($s) ?></option><?php endforeach; ?>
            </select>
          </div>
        </div>
      </fieldset>

      <fieldset>
        <legend class="mb-5 font-serif text-2xl text-forest">Delivery method</legend>
        <label class="flex cursor-pointer items-center justify-between rounded-2xl border-2 border-forest bg-white p-4">
          <span class="flex items-center gap-3"><input type="radio" name="ship" checked class="text-forest focus:ring-moss"> <span><span class="block text-sm font-medium text-forest">Standard delivery</span><span class="block text-xs text-ink-muted">3–7 business days after dispatch</span></span></span>
          <span class="text-sm font-medium text-moss">Free</span>
        </label>
      </fieldset>

      <fieldset>
        <legend class="mb-5 font-serif text-2xl text-forest">Payment</legend>
        <label class="flex cursor-pointer items-center justify-between rounded-2xl border-2 border-forest bg-white p-4">
          <span class="flex items-center gap-3"><input type="radio" name="pay" checked class="text-forest focus:ring-moss"> <span><span class="block text-sm font-medium text-forest">Pay online</span><span class="block text-xs text-ink-muted">UPI, cards, net banking &amp; wallets</span></span></span>
          <?= fl_icon('shield', 'h-5 w-5 text-moss') ?>
        </label>
        <p class="mt-3 text-xs text-ink-muted">You’ll be redirected to a secure payment page to complete your purchase.</p>
      </fieldset>

      <button class="btn-primary w-full !py-4">Place order</button>
      <p class="text-center text-xs text-ink-muted">By placing your order you agree to our <a href="<?= fl_url('policy', ['type' => 'terms']) ?>" class="underline">Terms</a> &amp; <a href="<?= fl_url('policy', ['type' => 'privacy']) ?>" class="underline">Privacy Policy</a>.</p>
    </form>

    <aside data-checkout-aside class="lg:col-span-5">
      <div class="rounded-3xl bg-white p-6 shadow-card lg:sticky lg:top-28">
        <p class="font-serif text-2xl text-forest">Order summary</p>
        <div data-checkout-summary class="mt-3 divide-y divide-line"></div>
        <dl class="mt-4 space-y-3 border-t border-line pt-4 text-sm">
          <div class="flex justify-between"><dt class="text-ink-soft">Subtotal</dt><dd data-summary-subtotal class="text-forest"></dd></div>
          <div class="flex justify-between"><dt class="text-ink-soft">Delivery</dt><dd class="font-medium text-moss">Free</dd></div>
          <div class="flex justify-between border-t border-line pt-4"><dt class="font-medium text-forest">Total</dt><dd data-summary-total class="font-serif text-2xl text-forest"></dd></div>
        </dl>
        <p class="mt-1 text-xs text-ink-muted">Inclusive of all taxes</p>
      </div>
    </aside>
  </div>
</section>
<?php fl_partial('footer'); ?>
