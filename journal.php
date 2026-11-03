<?php
require __DIR__ . '/inc/bootstrap.php';
$post = null;
if (isset($_GET['post'])) {
    foreach (FL_JOURNAL as $j) {
        if ($j['slug'] === $_GET['post']) { $post = $j; }
    }
    if (!$post) { http_response_code(404); include __DIR__ . '/404.php'; exit; }
}

if ($post):
    $page_title = $post['title'] . ' | Faelynn Journal';
    $page_desc = $post['excerpt'];
    fl_partial('header', compact('page_title', 'page_desc'));
?>
<article>
  <header class="bg-cream">
    <div class="container max-w-4xl py-12 text-center lg:py-16">
      <nav class="mb-5 text-xs text-ink-muted" aria-label="Breadcrumb"><a href="<?= fl_url() ?>" class="hover:text-forest">Home</a> <span class="mx-1">/</span> <a href="<?= fl_url('journal') ?>" class="hover:text-forest">Journal</a></nav>
      <p class="eyebrow"><?= e($post['tag']) ?> · <?= e(date('j F Y', strtotime($post['date']))) ?></p>
      <h1 class="mt-4 font-serif text-4xl font-medium leading-tight text-forest sm:text-5xl"><?= e($post['title']) ?></h1>
    </div>
  </header>
  <div class="container max-w-4xl">
    <img src="<?= e($post['image']) ?>" alt="Samara night dress" class="-mt-2 aspect-[16/9] w-full rounded-[2rem] object-cover object-[50%_20%]">
  </div>
  <div class="container prose-faelynn max-w-3xl py-12 text-[17px]">
    <p>When it comes to winding down after a long day, slipping into a comfortable <a href="<?= fl_category_url('night-dress-women') ?>">night dress</a> is often an overlooked aspect of bedtime routines. However, the choice of what we wear to bed can significantly impact the quality of our sleep and overall well-being. In this article, we delve into the importance of wearing relaxed nightwear, such as night suits and sleepwear, for a restful night’s sleep.</p>
    <ol>
      <li><strong>Comfort is key:</strong> One of the primary reasons to opt for relaxed nightwear is the comfort it provides. Night suits and sleepwear are specifically designed with soft, breathable fabrics that allow for unrestricted movement during sleep. Whether it’s cotton, silk, or bamboo fabric, the right material can make all the difference in ensuring a cozy and comfortable sleep environment.</li>
      <li><strong>Temperature regulation:</strong> Maintaining the optimal body temperature is crucial for a good night’s sleep. Night suits and sleepwear crafted from lightweight, moisture-wicking fabrics help regulate body temperature by allowing excess heat to escape, preventing overheating and discomfort. Additionally, during colder months, wearing layers or thermal sleepwear can help retain body heat and keep you warm throughout the night.</li>
      <li><strong>Psychological association:</strong> Believe it or not, the clothes we wear can influence our mindset and mood. Changing into designated sleepwear signals to the brain that it’s time to relax and unwind, creating a mental separation between daytime activities and sleep. This psychological association can aid in transitioning into a more relaxed state, ultimately promoting better sleep quality.</li>
      <li><strong>Skin health:</strong> Choosing the right nightwear can also benefit skin health. Fabrics like organic cotton or silk are gentle on the skin, reducing the risk of irritation or allergic reactions. Additionally, breathable materials allow for proper air circulation, minimizing the likelihood of skin conditions such as acne or eczema, which can be exacerbated by wearing restrictive or synthetic clothing to bed.</li>
      <li><strong>Improved sleep quality:</strong> Ultimately, the goal of wearing relaxed nightwear is to optimize sleep quality. By prioritizing comfort, temperature regulation, and psychological cues, you create an environment conducive to restorative sleep. Quality sleep is essential for overall health and well-being, impacting everything from cognitive function to mood and immune function.</li>
    </ol>
    <p>Incorporating relaxed nightwear into your bedtime routine is a simple yet effective way to enhance the quality of your sleep. By prioritizing comfort, temperature regulation, and psychological cues, you set the stage for a restful night’s sleep, allowing you to wake up feeling refreshed and rejuvenated each morning. So invest in <a href="<?= fl_url('shop') ?>">comfortable nightwear</a>, and give yourself the gift of better sleep.</p>
  </div>
  <section class="bg-cream/70 py-14">
    <div class="container">
      <h2 class="section-title mb-8">Shop the <em>story</em></h2>
      <div class="grid grid-cols-2 gap-x-4 gap-y-10 sm:gap-x-6 lg:grid-cols-4">
        <?php foreach (fl_pick(FL_GROUPS['sleep_women']) as $i => $sp): if ($i > 3) break; ?><?php fl_partial('product-card', ['p' => $sp]); ?><?php endforeach; ?>
      </div>
    </div>
  </section>
</article>
<?php else:
    $page_title = 'Journal | Faelynn';
    fl_partial('header', compact('page_title'));
    fl_partial('page-hero', ['title' => 'Faelynn Journal', 'crumb' => 'Journal', 'eyebrow' => 'Stories', 'text' => 'Stories of comfort, care and craft — from our workshop to your wardrobe.']);
    $first = FL_JOURNAL[0];
?>
<section class="container py-12 lg:py-16">
  <a href="<?= fl_url('journal', ['post' => $first['slug']]) ?>" class="group grid items-center gap-8 overflow-hidden rounded-[2rem] bg-white shadow-card lg:grid-cols-2">
    <div class="overflow-hidden"><img src="<?= e($first['image']) ?>" alt="" class="aspect-[4/3] w-full object-cover object-[50%_20%] transition duration-700 group-hover:scale-105"></div>
    <div class="p-8 lg:p-12">
      <p class="eyebrow"><?= e($first['tag']) ?> · <?= e(date('j M Y', strtotime($first['date']))) ?></p>
      <h2 class="mt-4 font-serif text-3xl leading-tight text-forest group-hover:text-moss sm:text-4xl"><?= e($first['title']) ?></h2>
      <p class="mt-4 text-ink-soft"><?= e($first['excerpt']) ?></p>
      <span class="link-arrow mt-6">Read article <?= fl_icon('arrow-r', 'h-4 w-4') ?></span>
    </div>
  </a>
  <div class="mt-12 grid gap-8 md:grid-cols-3">
    <?php foreach (FL_STORIES as $s): ?>
      <a href="<?= e($s['url']) ?>" class="group">
        <div class="overflow-hidden rounded-3xl bg-cream"><img src="<?= e($s['image']) ?>" alt="" class="aspect-[4/3] w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy"></div>
        <p class="mt-4 text-[11px] font-medium uppercase tracking-widest text-moss"><?= e($s['tag']) ?></p>
        <h3 class="mt-2 font-serif text-2xl leading-snug text-forest group-hover:text-moss"><?= e($s['title']) ?></h3>
        <p class="mt-2 text-sm text-ink-soft"><?= e($s['excerpt']) ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
<?php fl_partial('footer'); ?>
