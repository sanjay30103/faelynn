<?php
require __DIR__ . '/inc/bootstrap.php';
$page_title = 'Track Order | Faelynn';
fl_partial('header', compact('page_title'));
fl_partial('page-hero', ['title' => 'Track your order', 'crumb' => 'Track Order', 'text' => 'Enter your order ID and the email used at checkout. Signed-in customers can also see orders in My Account.']);
?>
<section class="container py-12 lg:py-16">
  <form class="mx-auto max-w-xl rounded-[2rem] bg-white p-8 shadow-card" data-demo-form="Thanks! We’ll email your latest tracking update." novalidate>
    <div class="space-y-4">
      <div><label class="label" for="t-id">Order ID</label><input class="field" id="t-id" required placeholder="e.g. FL1234567"></div>
      <div><label class="label" for="t-email">Billing email</label><input class="field" id="t-email" type="email" required autocomplete="email"></div>
    </div>
    <button class="btn-primary mt-6 w-full">Track</button>
    <p data-form-success class="mt-4 hidden rounded-xl bg-sage-pale px-4 py-3 text-sm text-moss">✓ Request received. Tracking details are also sent by email once your order ships. For help, write to <?= FL_EMAIL ?>.</p>
  </form>
</section>
<?php fl_partial('footer'); ?>
