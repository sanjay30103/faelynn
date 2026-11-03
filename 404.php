<?php
require_once __DIR__ . '/inc/bootstrap.php';
http_response_code(404);
$page_title = 'Page not found | Faelynn';
fl_partial('header', compact('page_title'));
?>
<section class="container grid min-h-[60vh] place-items-center py-20 text-center">
  <div>
    <img src="assets/images/brand/dragonfly.png" alt="" class="mx-auto h-16 w-auto animate-float">
    <p class="mt-6 font-serif text-7xl text-forest">404</p>
    <p class="mt-2 font-serif text-2xl italic text-moss">This page has fluttered away</p>
    <p class="mx-auto mt-3 max-w-md text-ink-soft">The page you’re looking for doesn’t exist or may have moved.</p>
    <div class="mt-8 flex flex-wrap justify-center gap-3">
      <a href="<?= fl_url() ?>" class="btn-primary">Back to home</a>
      <a href="<?= fl_url('shop') ?>" class="btn-outline">Shop all</a>
    </div>
  </div>
</section>
<?php fl_partial('footer'); ?>
