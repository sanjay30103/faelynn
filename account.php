<?php
require __DIR__ . '/inc/bootstrap.php';
$page_title = 'My Account | Faelynn';
fl_partial('header', compact('page_title'));
?>
<section class="container py-12 lg:py-20">
  <div class="mx-auto grid max-w-5xl overflow-hidden rounded-[2rem] bg-white shadow-card lg:grid-cols-2">
    <div class="relative hidden lg:block">
      <img src="assets/images/brand/Designer-3.jpg" alt="" class="absolute inset-0 h-full w-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/30 to-transparent"></div>
      <div class="absolute bottom-0 p-10 text-ivory">
        <img src="assets/images/brand/faelynn-logo-light.png" alt="Faelynn" class="h-12 w-auto">
        <p class="mt-5 font-serif text-3xl">Join Faelynn &amp; get <em class="text-sand">₹100 off</em></p>
        <p class="mt-2 text-sm text-ivory/80">Registration is free and the offer can be combined with any other offer.</p>
      </div>
    </div>
    <div class="p-8 sm:p-12" data-tabs>
      <div class="seg mb-8 w-full" role="tablist">
        <button type="button" role="tab" aria-selected="true" data-tab="login" class="flex-1">Sign in</button>
        <button type="button" role="tab" aria-selected="false" data-tab="register" class="flex-1">Create account</button>
      </div>
      <form data-panel="login" data-demo-form="Signed in (demo) — welcome back!" class="space-y-4" novalidate>
        <p class="font-serif text-3xl text-forest">Welcome back</p>
        <div><label class="label" for="l-email">Email or mobile</label><input class="field" id="l-email" required autocomplete="username"></div>
        <div><label class="label" for="l-pass">Password</label><input class="field" id="l-pass" type="password" required autocomplete="current-password"></div>
        <div class="flex items-center justify-between text-sm"><label class="flex items-center gap-2 text-ink-soft"><input type="checkbox" class="rounded text-forest focus:ring-moss"> Remember me</label><a href="<?= fl_url('contact') ?>" class="text-moss underline underline-offset-4">Forgot password?</a></div>
        <button class="btn-primary w-full">Sign in</button>
        <p class="text-center text-xs text-ink-muted">Prefer not to sign in? You can check out as a guest.</p>
      </form>
      <form data-panel="register" data-demo-form="Account created (demo) — ₹100 off has been added!" class="hidden space-y-4" novalidate>
        <p class="font-serif text-3xl text-forest">Create your account</p>
        <div class="grid gap-4 sm:grid-cols-2">
          <div><label class="label" for="r-fn">First name</label><input class="field" id="r-fn" required autocomplete="given-name"></div>
          <div><label class="label" for="r-ln">Last name</label><input class="field" id="r-ln" autocomplete="family-name"></div>
        </div>
        <div><label class="label" for="r-email">Email</label><input class="field" id="r-email" type="email" required autocomplete="email"></div>
        <div><label class="label" for="r-pass">Password</label><input class="field" id="r-pass" type="password" required minlength="6" autocomplete="new-password"></div>
        <button class="btn-primary w-full">Create account &amp; get ₹100 off</button>
        <p class="text-center text-xs text-ink-muted">By creating an account you agree to our <a href="<?= fl_url('policy', ['type' => 'terms']) ?>" class="underline">Terms</a> &amp; <a href="<?= fl_url('policy', ['type' => 'privacy']) ?>" class="underline">Privacy Policy</a>.</p>
      </form>
    </div>
  </div>
</section>
<?php fl_partial('footer'); ?>
