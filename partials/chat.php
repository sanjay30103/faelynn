<?php
/**
 * Help chat widget (floating, bottom-right).
 * Quick answers come from Faelynn's own FAQ / policies; free-text messages are
 * handed over to WhatsApp so a real person replies. Swap for Tawk.to / Crisp /
 * WhatsApp Business API later without touching the page markup.
 */
$chat_topics = [
    'track' => [
        'q' => 'Track my order',
        'a' => 'Signed-in customers can see every order under <b>My Account → My Orders</b>. Guests can use the Track Order page with their order ID. Orders are dispatched within 3–4 business days and usually delivered in 3–7 business days.',
        'link' => [fl_url('track-order'), 'Track order'],
    ],
    'size' => [
        'q' => 'Help with size',
        'a' => 'Our pieces are cut in relaxed silhouettes. Bust guide: <b>S</b> 34–36″, <b>M</b> 36–38″, <b>L</b> 38–40″, <b>XL</b> 40–42″. Between sizes? We can make it to your measurements.',
        'link' => [fl_url('custom-order') . '#size-chart', 'See size chart'],
    ],
    'returns' => [
        'q' => 'Returns & refunds',
        'a' => 'Returns can be started within <b>10 days</b> of purchase for unused items in original packaging. Email ' . FL_EMAIL . ' with your order number. Approved refunds are processed within 7 business days.',
        'link' => [fl_url('policy', ['type' => 'returns']), 'Returns policy'],
    ],
    'custom' => [
        'q' => 'Custom order',
        'a' => 'Yes! We craft made-to-measure pieces. Share your style, preferred colours, fabric and measurements and our team will confirm the details with you.',
        'link' => [fl_url('custom-order'), 'Start a custom order'],
    ],
    'shipping' => [
        'q' => 'Delivery & shipping',
        'a' => 'Delivery is <b>free on all orders</b> across India. We also ship internationally (base charge ₹3,500; international orders are final sale).',
        'link' => [fl_url('policy', ['type' => 'shipping']), 'Shipping policy'],
    ],
];
?>
<!-- Help chat -->
<div data-chat class="fixed bottom-5 right-5 z-[55] flex flex-col items-end transition-transform duration-300">
  <!-- Panel -->
  <section id="chat-panel" data-chat-panel role="dialog" aria-label="Chat with Faelynn" aria-hidden="true"
    class="chat-panel mb-4 flex max-h-[min(640px,calc(100svh-7rem))] w-[calc(100vw-2.5rem)] flex-col overflow-hidden rounded-3xl bg-ivory shadow-2xl ring-1 ring-forest/10 sm:w-[380px]">
    <header class="relative bg-forest px-5 pb-5 pt-5 text-ivory">
      <div class="flex items-center gap-3">
        <span class="relative grid h-11 w-11 place-items-center rounded-full bg-ivory/10">
          <img src="assets/images/brand/dragonfly-light.png" alt="" class="h-6 w-auto">
          <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-forest bg-[#5FBF7A]" aria-hidden="true"></span>
        </span>
        <div class="min-w-0 flex-1">
          <p class="font-serif text-xl leading-tight">Faelynn Care</p>
          <p class="text-xs text-ivory/70">We reply on WhatsApp · <?= FL_HOURS ?></p>
        </div>
        <button type="button" data-chat-toggle class="grid h-9 w-9 place-items-center rounded-full text-ivory/80 hover:bg-ivory/10 hover:text-ivory" aria-label="Close chat"><?= fl_icon('close', 'h-5 w-5') ?></button>
      </div>
    </header>

    <div data-chat-log class="no-scrollbar flex-1 space-y-3 overflow-y-auto bg-[#F6F2EA] px-4 py-5" aria-live="polite">
      <div class="chat-bot">
        <p>Hi there 👋 Welcome to Faelynn.</p>
        <p class="mt-1">How can we help you today? Pick a topic or type your question.</p>
      </div>
      <div data-chat-chips class="flex flex-wrap gap-2 pl-1">
        <?php foreach ($chat_topics as $key => $t): ?>
          <button type="button" data-chat-topic="<?= e($key) ?>" class="rounded-full border border-forest/20 bg-white px-3.5 py-1.5 text-xs font-medium text-forest transition hover:border-forest hover:bg-forest hover:text-ivory"><?= e($t['q']) ?></button>
        <?php endforeach; ?>
      </div>
    </div>

    <form data-chat-form class="border-t border-line bg-white p-3">
      <div class="flex items-end gap-2">
        <label for="chat-input" class="sr-only">Type your message</label>
        <textarea id="chat-input" data-chat-input rows="1" maxlength="500" placeholder="Type your message…" class="max-h-28 min-h-[44px] flex-1 resize-none rounded-2xl border-line bg-ivory px-4 py-2.5 text-sm text-forest placeholder:text-ink-muted focus:border-moss focus:ring-moss"></textarea>
        <button class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-forest text-ivory transition hover:bg-moss" aria-label="Send message">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 3 10 14"/><path d="m21 3-7 18-4-7-7-4z"/></svg>
        </button>
      </div>
      <div class="mt-2 flex items-center justify-between px-1 text-[11px] text-ink-muted">
        <span>Messages continue on WhatsApp</span>
        <span class="flex gap-3">
          <a href="tel:<?= FL_PHONE_RAW ?>" class="inline-flex items-center gap-1 hover:text-forest"><?= fl_icon('phone', 'h-3.5 w-3.5') ?> Call</a>
          <a href="mailto:<?= FL_EMAIL ?>" class="inline-flex items-center gap-1 hover:text-forest"><?= fl_icon('mail', 'h-3.5 w-3.5') ?> Email</a>
        </span>
      </div>
    </form>
  </section>

  <!-- Launcher -->
  <div class="flex items-center gap-3">
    <span data-chat-hint class="chat-hint hidden rounded-full bg-white px-4 py-2 text-sm font-medium text-forest shadow-soft sm:block">Need help? Chat with us</span>
    <button type="button" data-chat-toggle aria-controls="chat-panel" aria-expanded="false" aria-label="Chat with us"
      class="relative grid h-14 w-14 place-items-center rounded-full bg-forest text-ivory shadow-soft ring-4 ring-ivory transition hover:scale-105 hover:bg-moss">
      <svg data-chat-icon-open class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12Z"/><path d="M8.5 11h.01M12 11h.01M15.5 11h.01" stroke-width="2.4"/></svg>
      <svg data-chat-icon-close class="hidden h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
      <span data-chat-dot class="absolute right-0.5 top-0.5 h-3.5 w-3.5 rounded-full border-2 border-ivory bg-clay" aria-hidden="true"></span>
    </button>
  </div>
</div>
<script type="application/json" id="chat-data"><?= json_encode([
    'topics' => array_map(fn($t) => ['q' => $t['q'], 'a' => $t['a'], 'link' => $t['link']], $chat_topics),
    'wa' => FL_PHONE_RAW,
    'hours' => FL_HOURS,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?></script>
