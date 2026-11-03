<?php
require __DIR__ . '/inc/bootstrap.php';
$page_title = 'Wishlist | Faelynn';
fl_partial('header', compact('page_title'));
fl_partial('page-hero', ['title' => 'Your Wishlist', 'crumb' => 'Wishlist', 'text' => 'Pieces you’ve fallen for — saved on this device.']);
?>
<section class="container py-10 lg:py-14">
  <div data-wishlist-page class="grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 md:grid-cols-3 lg:grid-cols-4"></div>
</section>
<?php fl_partial('footer'); ?>
