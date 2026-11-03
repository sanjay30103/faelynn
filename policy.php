<?php
require __DIR__ . '/inc/bootstrap.php';
$type = (string) ($_GET['type'] ?? 'shipping');
if (!isset(FL_POLICIES[$type])) {
    http_response_code(404);
    include __DIR__ . '/404.php';
    exit;
}
$meta = FL_POLICIES[$type];
$data = fl_policies()[$meta['slug']];
$page_title = $data['title'] . ' | Faelynn';
fl_partial('header', compact('page_title'));
fl_partial('page-hero', ['title' => $data['title'], 'eyebrow' => 'Policies']);
?>
<section class="container grid gap-10 py-12 lg:grid-cols-12 lg:py-16">
  <nav class="lg:col-span-3" aria-label="Policies">
    <ul class="no-scrollbar flex gap-2 overflow-x-auto lg:sticky lg:top-28 lg:flex-col">
      <?php foreach (FL_POLICIES as $key => $pol): ?>
        <li class="shrink-0"><a href="<?= fl_url('policy', ['type' => $key]) ?>" class="block rounded-full border px-4 py-2 text-sm lg:rounded-xl <?= $key === $type ? 'border-forest bg-forest text-ivory' : 'border-line bg-white text-forest hover:border-forest' ?>" <?= $key === $type ? 'aria-current="page"' : '' ?>><?= e($pol['label']) ?></a></li>
      <?php endforeach; ?>
      <li class="shrink-0"><a href="<?= fl_url('faq') ?>" class="block rounded-full border border-line bg-white px-4 py-2 text-sm text-forest hover:border-forest lg:rounded-xl">FAQs</a></li>
    </ul>
  </nav>
  <article class="prose-faelynn max-w-3xl lg:col-span-9">
    <?= $data['html'] /* sanitised at build time from faelynn.co.in */ ?>
  </article>
</section>
<?php fl_partial('footer'); ?>
