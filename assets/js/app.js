/* Faelynn — storefront interactions (vanilla JS, no build step)
 * Cart & wishlist are kept in localStorage for the demo.
 * In WooCommerce these map to the Store API cart (wc/store/v1/cart) and a wishlist plugin.
 */
(function () {
  'use strict';

  const FL = window.FAELYNN || { products: [], urls: {} };
  const PRODUCTS = {};
  FL.products.forEach((p) => { PRODUCTS[p.slug] = p; });

  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => Array.from(el.querySelectorAll(s));
  const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const money = (n) => '₹' + new Intl.NumberFormat('en-IN').format(Math.round(n));

  /* ------------------------------------------------------------------ */
  /* Storage (guarded: private mode / blocked storage must not break UI) */
  /* ------------------------------------------------------------------ */
  const store = {
    get(key, fallback) {
      try { const v = JSON.parse(localStorage.getItem(key)); return v ?? fallback; } catch (e) { return fallback; }
    },
    set(key, val) { try { localStorage.setItem(key, JSON.stringify(val)); } catch (e) { /* ignore */ } },
  };

  /* ------------------------------------------------------------------ */
  /* Cart                                                                */
  /* ------------------------------------------------------------------ */
  const Cart = {
    items() {
      return store.get('fl_cart', []).filter((i) => PRODUCTS[i.slug] && i.qty > 0);
    },
    save(items) { store.set('fl_cart', items); renderCart(); },
    add(slug, size, qty = 1) {
      const p = PRODUCTS[slug];
      if (!p) return;
      const items = Cart.items();
      const found = items.find((i) => i.slug === slug && i.size === size);
      if (found) found.qty = Math.min(10, found.qty + qty); else items.push({ slug, size, qty });
      Cart.save(items);
      toast(`<img src="${esc(p.img)}" alt="" class="h-8 w-6 rounded object-cover"> Added <b class="font-medium">${esc(p.short)}</b> (${esc(size)}) to bag`);
      bump('[data-cart-count]');
    },
    setQty(slug, size, qty) {
      let items = Cart.items();
      items = items.map((i) => (i.slug === slug && i.size === size ? { ...i, qty: Math.max(0, Math.min(10, qty)) } : i)).filter((i) => i.qty > 0);
      Cart.save(items);
    },
    remove(slug, size) { Cart.save(Cart.items().filter((i) => !(i.slug === slug && i.size === size))); },
    clear() { Cart.save([]); },
    count() { return Cart.items().reduce((n, i) => n + i.qty, 0); },
    subtotal() { return Cart.items().reduce((n, i) => n + PRODUCTS[i.slug].price * i.qty, 0); },
  };

  /* ------------------------------------------------------------------ */
  /* Wishlist                                                            */
  /* ------------------------------------------------------------------ */
  const Wish = {
    items() { return store.get('fl_wishlist', []).filter((s) => PRODUCTS[s]); },
    has(slug) { return Wish.items().includes(slug); },
    toggle(slug) {
      const items = Wish.items();
      const on = !items.includes(slug);
      store.set('fl_wishlist', on ? [...items, slug] : items.filter((s) => s !== slug));
      syncWishlist();
      const p = PRODUCTS[slug];
      toast(on ? `♥ Saved <b class="font-medium">${esc(p.short)}</b> to wishlist` : `Removed ${esc(p.short)} from wishlist`);
      if (on) bump('[data-wishlist-count]');
      renderWishlistPage();
    },
  };

  function syncWishlist() {
    const items = Wish.items();
    $$('[data-wishlist]').forEach((b) => {
      const on = items.includes(b.dataset.wishlist);
      b.setAttribute('aria-pressed', on ? 'true' : 'false');
      const svg = b.querySelector('svg');
      if (svg) svg.setAttribute('fill', on ? 'currentColor' : 'none');
    });
    $$('[data-wishlist-count]').forEach((el) => {
      el.textContent = items.length;
      el.classList.toggle('hidden', !items.length);
      el.classList.toggle('grid', !!items.length);
    });
  }

  /* ------------------------------------------------------------------ */
  /* UI helpers: toast, bump, panels                                     */
  /* ------------------------------------------------------------------ */
  let toastTimer;
  function toast(html) {
    const el = $('[data-toast]');
    if (!el) return;
    el.innerHTML = html;
    el.classList.add('is-show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => el.classList.remove('is-show'), 2600);
  }

  function bump(sel) {
    $$(sel).forEach((el) => {
      el.animate([{ transform: 'scale(1)' }, { transform: 'scale(1.5)' }, { transform: 'scale(1)' }], { duration: 400 });
    });
  }

  let openPanel = null;
  let lastFocus = null;
  function open(id) {
    const panel = document.getElementById(id);
    if (!panel) return;
    if (openPanel && openPanel !== id) close(true);
    const ov = $(`[data-overlay="${id}"]`);
    lastFocus = document.activeElement;
    if (ov) { ov.classList.remove('hidden'); requestAnimationFrame(() => ov.classList.add('is-open')); }
    requestAnimationFrame(() => panel.classList.add('is-open'));
    panel.setAttribute('aria-hidden', 'false');
    document.documentElement.style.overflow = 'hidden';
    openPanel = id;
    if (id === 'cart') renderCart();
    setTimeout(() => {
      const f = panel.querySelector('[data-search-input]') || panel.querySelector('[data-close]');
      if (f) f.focus({ preventScroll: true });
    }, 120);
  }

  function close(keepScroll) {
    if (!openPanel) return;
    const panel = document.getElementById(openPanel);
    const ov = $(`[data-overlay="${openPanel}"]`);
    panel.classList.remove('is-open');
    panel.setAttribute('aria-hidden', 'true');
    if (ov) { ov.classList.remove('is-open'); setTimeout(() => ov.classList.add('hidden'), 300); }
    if (!keepScroll) document.documentElement.style.overflow = '';
    openPanel = null;
    if (lastFocus && lastFocus.focus) lastFocus.focus({ preventScroll: true });
  }

  document.addEventListener('click', (e) => {
    const t = e.target;
    const opener = t.closest('[data-open]');
    if (opener) { e.preventDefault(); open(opener.dataset.open); return; }
    if (t.closest('[data-close]') || t.matches('[data-overlay]')) { close(); return; }

    const wl = t.closest('[data-wishlist]');
    if (wl) { e.preventDefault(); e.stopPropagation(); Wish.toggle(wl.dataset.wishlist); return; }

    const add = t.closest('[data-add][data-size]');
    if (add) { e.preventDefault(); e.stopPropagation(); Cart.add(add.dataset.add, add.dataset.size); return; }

    const qa = t.closest('[data-quick-add]');
    if (qa) { e.preventDefault(); e.stopPropagation(); quickAdd(qa.dataset.quickAdd); return; }

    const qty = t.closest('[data-qty]');
    if (qty) {
      const { slug, size } = qty.dataset;
      const item = Cart.items().find((i) => i.slug === slug && i.size === size);
      if (item) Cart.setQty(slug, size, item.qty + Number(qty.dataset.qty));
      return;
    }
    const rm = t.closest('[data-remove]');
    if (rm) { Cart.remove(rm.dataset.slug, rm.dataset.size); return; }
  });

  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });

  /* ------------------------------------------------------------------ */
  /* Quick add (size picker)                                             */
  /* ------------------------------------------------------------------ */
  let qaSlug = null;
  let qaSize = null;
  function quickAdd(slug) {
    const p = PRODUCTS[slug];
    const root = $('#quick-add');
    if (!p || !root) return;
    qaSlug = slug; qaSize = null;
    $('[data-qa-img]', root).src = p.img;
    $('[data-qa-img]', root).alt = p.short;
    $('[data-qa-cat]', root).textContent = p.cat;
    $('[data-qa-name]', root).textContent = p.short;
    $('[data-qa-price]', root).textContent = money(p.price);
    $('[data-qa-link]', root).href = p.url;
    $('[data-qa-error]', root).classList.add('hidden');
    $('[data-qa-sizes]', root).innerHTML = p.sizes.map((s) => `<button type="button" class="size-pill" aria-pressed="false" data-qa-size="${esc(s)}">${esc(s)}</button>`).join('');
    open('quick-add');
  }
  document.addEventListener('click', (e) => {
    const b = e.target.closest('[data-qa-size]');
    if (b) {
      qaSize = b.dataset.qaSize;
      $$('[data-qa-size]').forEach((x) => x.setAttribute('aria-pressed', x === b ? 'true' : 'false'));
      $('[data-qa-error]').classList.add('hidden');
    }
    if (e.target.closest('[data-qa-add]')) {
      if (!qaSize) { $('[data-qa-error]').classList.remove('hidden'); return; }
      Cart.add(qaSlug, qaSize);
      close();
    }
  });

  /* ------------------------------------------------------------------ */
  /* Cart rendering (drawer, cart page, checkout summary)                */
  /* ------------------------------------------------------------------ */
  function lineHTML(i, compact) {
    const p = PRODUCTS[i.slug];
    return `
      <div class="flex gap-4 py-5 ${compact ? '' : 'sm:gap-6'}">
        <a href="${esc(p.url)}" class="shrink-0"><img src="${esc(p.img)}" alt="${esc(p.short)}" class="${compact ? 'h-28 w-20' : 'h-36 w-28'} rounded-xl bg-cream object-cover object-top"></a>
        <div class="flex min-w-0 flex-1 flex-col">
          <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
              <p class="text-[10px] font-medium uppercase tracking-widest text-moss">${esc(p.cat)}</p>
              <a href="${esc(p.url)}" class="mt-0.5 block font-serif text-lg leading-snug text-forest hover:text-moss">${esc(p.short)}</a>
              <p class="mt-1 text-xs text-ink-muted">Size: <span class="font-medium text-forest">${esc(i.size)}</span></p>
            </div>
            <p class="whitespace-nowrap text-sm font-medium text-forest">${money(p.price * i.qty)}</p>
          </div>
          <div class="mt-auto flex items-center justify-between pt-3">
            <div class="inline-flex items-center rounded-full border border-line bg-white">
              <button type="button" class="grid h-8 w-8 place-items-center text-forest" data-qty="-1" data-slug="${esc(i.slug)}" data-size="${esc(i.size)}" aria-label="Decrease quantity">−</button>
              <span class="w-6 text-center text-sm">${i.qty}</span>
              <button type="button" class="grid h-8 w-8 place-items-center text-forest" data-qty="1" data-slug="${esc(i.slug)}" data-size="${esc(i.size)}" aria-label="Increase quantity">+</button>
            </div>
            <button type="button" class="text-xs text-ink-muted underline underline-offset-4 hover:text-clay" data-remove data-slug="${esc(i.slug)}" data-size="${esc(i.size)}">Remove</button>
          </div>
        </div>
      </div>`;
  }

  const emptyHTML = (msg) => `
    <div class="flex flex-col items-center py-16 text-center">
      <span class="grid h-16 w-16 place-items-center rounded-full bg-cream text-moss"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg></span>
      <p class="mt-5 font-serif text-2xl text-forest">${msg}</p>
      <p class="mt-2 text-sm text-ink-muted">Discover soft, breathable pieces made by hand.</p>
      <div class="mt-6 flex gap-3"><a href="${esc(FL.urls.women)}" class="btn-primary !px-5 !py-2.5">Shop Women</a><a href="${esc(FL.urls.men)}" class="btn-outline !px-5 !py-2.5">Shop Men</a></div>
    </div>`;

  function renderCart() {
    const items = Cart.items();
    const count = Cart.count();
    const sub = Cart.subtotal();

    $$('[data-cart-count]').forEach((el) => {
      el.textContent = count;
      el.classList.toggle('hidden', !count);
      el.classList.toggle('grid', !!count);
    });
    $$('[data-cart-count-text]').forEach((el) => { el.textContent = count ? `(${count} ${count === 1 ? 'item' : 'items'})` : ''; });

    const list = $('[data-cart-items]');
    if (list) {
      list.innerHTML = items.length ? `<div class="divide-y divide-line">${items.map((i) => lineHTML(i, true)).join('')}</div>` : emptyHTML('Your bag is empty');
      $('[data-cart-footer]').classList.toggle('hidden', !items.length);
      $$('[data-cart-subtotal]').forEach((el) => { el.textContent = money(sub); });
    }

    // Cart page
    const page = $('[data-cart-page]');
    if (page) {
      const has = items.length > 0;
      $('[data-cart-page-items]').innerHTML = has ? items.map((i) => lineHTML(i, false)).join('') : emptyHTML('Your bag is empty');
      $('[data-cart-page-summary]').classList.toggle('hidden', !has);
      $$('[data-summary-subtotal]').forEach((el) => { el.textContent = money(sub); });
      $$('[data-summary-total]').forEach((el) => { el.textContent = money(sub); });
      $$('[data-summary-count]').forEach((el) => { el.textContent = count; });
    }

    // Checkout summary
    const co = $('[data-checkout-summary]');
    if (co) {
      co.innerHTML = items.map((i) => {
        const p = PRODUCTS[i.slug];
        return `<div class="flex items-center gap-4 py-3">
          <div class="relative shrink-0"><img src="${esc(p.img)}" alt="" class="h-20 w-16 rounded-lg bg-cream object-cover object-top"><span class="absolute -right-2 -top-2 grid h-5 min-w-[20px] place-items-center rounded-full bg-forest px-1 text-[10px] text-ivory">${i.qty}</span></div>
          <div class="min-w-0 flex-1"><p class="truncate font-serif text-base text-forest">${esc(p.short)}</p><p class="text-xs text-ink-muted">Size ${esc(i.size)}</p></div>
          <p class="text-sm font-medium text-forest">${money(p.price * i.qty)}</p></div>`;
      }).join('');
      $$('[data-summary-subtotal]').forEach((el) => { el.textContent = money(sub); });
      $$('[data-summary-total]').forEach((el) => { el.textContent = money(sub); });
      const empty = $('[data-checkout-empty]');
      const form = $('[data-checkout]');
      const aside = $('[data-checkout-aside]');
      if (empty && form && !form.classList.contains('is-done')) {
        empty.classList.toggle('hidden', items.length > 0);
        form.classList.toggle('hidden', items.length === 0);
        if (aside) aside.classList.toggle('hidden', items.length === 0);
      }
    }
  }

  /* ------------------------------------------------------------------ */
  /* Wishlist page                                                       */
  /* ------------------------------------------------------------------ */
  function renderWishlistPage() {
    const root = $('[data-wishlist-page]');
    if (!root) return;
    const items = Wish.items();
    if (!items.length) {
      root.innerHTML = `<div class="col-span-full flex flex-col items-center py-16 text-center">
        <span class="grid h-16 w-16 place-items-center rounded-full bg-cream text-clay"><svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19.5 12.6 12 20l-7.5-7.4A4.9 4.9 0 0 1 12 6.1a4.9 4.9 0 0 1 7.5 6.5Z"/></svg></span>
        <p class="mt-5 font-serif text-3xl text-forest">Your wishlist is empty</p>
        <p class="mt-2 text-sm text-ink-muted">Tap the heart on any piece to save it here.</p>
        <a href="${esc(FL.urls.shop)}" class="btn-primary mt-6">Explore the collection</a></div>`;
      return;
    }
    root.innerHTML = items.map((slug) => {
      const p = PRODUCTS[slug];
      return `<article class="group relative">
        <a href="${esc(p.url)}" class="block overflow-hidden rounded-2xl bg-[#F1EFEC]"><img src="${esc(p.img)}" alt="${esc(p.short)}" class="aspect-[3/4] w-full object-cover object-top transition duration-700 group-hover:scale-105"></a>
        <button type="button" data-wishlist="${esc(slug)}" aria-pressed="true" class="absolute right-3 top-3 grid h-9 w-9 place-items-center rounded-full bg-ivory text-clay" aria-label="Remove from wishlist"><svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 6l12 12M18 6 6 18"/></svg></button>
        <p class="mt-3 text-[10px] font-medium uppercase tracking-widest text-moss">${esc(p.cat)}</p>
        <a href="${esc(p.url)}" class="mt-1 block font-serif text-xl text-forest">${esc(p.short)}</a>
        <div class="mt-2 flex items-center justify-between"><span class="text-sm font-medium">${money(p.price)}</span>
        <button type="button" data-quick-add="${esc(slug)}" class="rounded-full bg-forest px-4 py-2 text-[10px] font-semibold uppercase tracking-widest text-ivory hover:bg-moss">Move to bag</button></div>
      </article>`;
    }).join('');
  }

  /* ------------------------------------------------------------------ */
  /* Live search                                                         */
  /* ------------------------------------------------------------------ */
  const searchInput = $('[data-search-input]');
  if (searchInput) {
    const results = $('[data-search-results]');
    searchInput.addEventListener('input', () => {
      const q = searchInput.value.trim().toLowerCase();
      if (q.length < 2) { results.innerHTML = ''; return; }
      const hits = FL.products.filter((p) => [p.name, p.cat, p.gender, ...(p.tags || [])].join(' ').toLowerCase().includes(q));
      results.innerHTML = hits.length
        ? hits.map((p) => `<a href="${esc(p.url)}" class="group"><div class="overflow-hidden rounded-xl bg-[#F1EFEC]"><img src="${esc(p.img)}" alt="" class="aspect-[3/4] w-full object-cover object-top transition duration-500 group-hover:scale-105"></div><p class="mt-2 font-serif text-base leading-tight text-forest">${esc(p.short)}</p><p class="text-xs text-ink-muted">${money(p.price)}</p></a>`).join('')
        : `<p class="col-span-full text-sm text-ink-muted">No products found for “${esc(searchInput.value)}”. Try “dress”, “co-ord” or “shirt”.</p>`;
    });
  }

  /* ------------------------------------------------------------------ */
  /* Header: sticky shadow + back to top                                 */
  /* ------------------------------------------------------------------ */
  const header = $('#site-header');
  const backTop = $('[data-back-top]');
  const onScroll = () => {
    const y = window.scrollY;
    if (header) header.classList.toggle('is-stuck', y > 10);
    if (backTop) backTop.classList.toggle('is-show', y > 900);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
  if (backTop) backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

  /* ------------------------------------------------------------------ */
  /* Reveal on scroll                                                    */
  /* ------------------------------------------------------------------ */
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => { if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); } });
    }, { rootMargin: '0px 0px -8% 0px' });
    $$('.reveal').forEach((el) => io.observe(el));
  } else {
    $$('.reveal').forEach((el) => el.classList.add('is-visible'));
  }

  /* ------------------------------------------------------------------ */
  /* Sliders (Swiper)                                                    */
  /* ------------------------------------------------------------------ */
  function initSwipers() {
    if (typeof Swiper === 'undefined') return;

    const hero = $('.hero-swiper');
    if (hero) {
      const total = hero.querySelectorAll('.swiper-slide').length;
      const cur = $('[data-hero-current]', hero);
      const bar = $('[data-hero-bar]', hero);
      new Swiper(hero, {
        effect: 'fade',
        fadeEffect: { crossFade: true },
        loop: true,
        speed: 900,
        autoplay: { delay: 6500, disableOnInteraction: false, pauseOnMouseEnter: true },
        navigation: { prevEl: '.hero-prev', nextEl: '.hero-next' },
        keyboard: { enabled: true },
        on: {
          slideChange(s) { if (cur) cur.textContent = String(s.realIndex + 1).padStart(2, '0'); },
          autoplayTimeLeft(s, time, progress) { if (bar) bar.style.width = ((1 - progress) * 100) + '%'; },
        },
      });
      const counter = hero.querySelector('.hero-counter .opacity-60:last-child');
      if (counter) counter.textContent = String(total).padStart(2, '0');
    }

    $$('.loved-swiper').forEach((el) => {
      const wrap = el.parentElement;
      new Swiper(el, {
        slidesPerView: 1.3, spaceBetween: 14, grabCursor: true,
        navigation: { prevEl: wrap.querySelector('.loved-prev'), nextEl: wrap.querySelector('.loved-next') },
        breakpoints: { 640: { slidesPerView: 2.3, spaceBetween: 18 }, 1024: { slidesPerView: 4, spaceBetween: 22 } },
      });
    });

    $$('.trend-swiper').forEach((el) => {
      new Swiper(el, {
        slidesPerView: 1.12, centeredSlides: true, loop: true, spaceBetween: 14, speed: 700, grabCursor: true,
        autoplay: { delay: 4500, disableOnInteraction: false },
        pagination: { el: el.querySelector('.trend-dots'), clickable: true },
        breakpoints: { 768: { slidesPerView: 1.6, spaceBetween: 24 }, 1280: { slidesPerView: 2.15, spaceBetween: 28 } },
      });
    });

    $$('.card-swiper').forEach((el) => {
      const panel = el.closest('[data-panel]') || el.parentElement;
      new Swiper(el, {
        slidesPerView: 1.5, spaceBetween: 14, grabCursor: true, observer: true, observeParents: true,
        navigation: { prevEl: panel.querySelector('.card-prev'), nextEl: panel.querySelector('.card-next') },
        breakpoints: { 640: { slidesPerView: 2.3, spaceBetween: 18 }, 1024: { slidesPerView: 2.4, spaceBetween: 20 }, 1280: { slidesPerView: 3, spaceBetween: 22 } },
      });
    });

    $$('.grid-swiper').forEach((el) => {
      const panel = el.closest('[data-panel]') || el.parentElement;
      new Swiper(el, {
        slidesPerView: 1.6, spaceBetween: 14, grabCursor: true, observer: true, observeParents: true,
        navigation: { prevEl: panel.querySelector('.grid-prev'), nextEl: panel.querySelector('.grid-next') },
        pagination: { el: panel.querySelector('.grid-dots'), clickable: true },
        breakpoints: { 640: { slidesPerView: 2.5, spaceBetween: 18 }, 1024: { slidesPerView: 4, spaceBetween: 22 } },
      });
    });

    $$('.related-swiper').forEach((el) => {
      const wrap = el.closest('section') || el.parentElement;
      new Swiper(el, {
        slidesPerView: 1.6, spaceBetween: 14, grabCursor: true,
        navigation: { prevEl: wrap.querySelector('.rel-prev'), nextEl: wrap.querySelector('.rel-next') },
        breakpoints: { 640: { slidesPerView: 2.5, spaceBetween: 18 }, 1024: { slidesPerView: 4, spaceBetween: 22 } },
      });
    });

    $$('.pdp-swiper').forEach((el) => {
      new Swiper(el, { slidesPerView: 1, pagination: { el: el.querySelector('.pdp-dots'), clickable: true } });
    });
  }

  /* ------------------------------------------------------------------ */
  /* Tabs (Men / Women)                                                  */
  /* ------------------------------------------------------------------ */
  $$('[data-tabs]').forEach((root) => {
    const btns = $$('[data-tab]', root);
    btns.forEach((b) => b.addEventListener('click', () => {
      btns.forEach((x) => x.setAttribute('aria-selected', x === b ? 'true' : 'false'));
      $$('[data-panel]', root).forEach((p) => {
        const show = p.dataset.panel === b.dataset.tab;
        p.classList.toggle('hidden', !show);
        if (show) {
          p.animate([{ opacity: 0, transform: 'translateY(10px)' }, { opacity: 1, transform: 'none' }], { duration: 450, easing: 'ease-out' });
          $$('.swiper', p).forEach((s) => s.swiper && s.swiper.update());
        }
      });
    }));
  });

  /* ------------------------------------------------------------------ */
  /* New arrivals showcase                                               */
  /* ------------------------------------------------------------------ */
  const na = $('[data-new-arrivals]');
  if (na) {
    const data = JSON.parse($('#na-data').textContent);
    let gender = 'women';
    let idx = 0;
    const mainImg = $('[data-na-main]', na);

    const setMain = (src, alt) => {
      mainImg.style.opacity = '0.2';
      const img = new Image();
      img.onload = img.onerror = () => { mainImg.src = src; mainImg.alt = alt; mainImg.style.opacity = '1'; };
      img.src = src;
    };

    const render = () => {
      const list = data[gender] || [];
      const p = list[idx];
      if (!p) return;
      setMain(p.images[0], p.name);
      $('[data-na-main-link]', na).href = p.url;
      $('[data-na-gallery]', na).innerHTML = p.images.slice(0, 4).map((src, i) =>
        `<button type="button" data-na-img="${esc(src)}" class="overflow-hidden rounded-xl border-2 ${i === 0 ? 'border-forest' : 'border-transparent'} bg-[#F1EFEC] transition hover:border-forest/50" aria-label="View image ${i + 1}"><img src="${esc(src)}" alt="" class="aspect-[3/4] w-full object-cover object-top"></button>`).join('');
      $('[data-na-thumbs]', na).innerHTML = list.map((x, i) =>
        `<button type="button" data-na-pick="${i}" class="w-24 shrink-0 snap-start overflow-hidden rounded-2xl border-2 bg-[#F1EFEC] transition sm:w-28 ${i === idx ? 'border-forest' : 'border-transparent opacity-70 hover:opacity-100'}" aria-label="${esc(x.name)}"><img src="${esc(x.images[0])}" alt="" class="aspect-[3/4] w-full object-cover object-top"></button>`).join('');
      $('[data-na-cat]', na).textContent = p.cat;
      $('[data-na-name]', na).textContent = p.name;
      $('[data-na-tagline]', na).textContent = p.tagline;
      $('[data-na-price]', na).textContent = p.price;
      $('[data-na-link]', na).href = p.url;
      $('[data-na-add]', na).dataset.quickAdd = p.slug;
      $('[data-na-specs]', na).innerHTML = p.specs.map(([k, v]) =>
        `<div class="rounded-2xl bg-ivory/10 px-4 py-3"><dt class="text-[10px] uppercase tracking-widest text-ivory/60">${esc(k)}</dt><dd class="mt-1 text-ivory">${esc(v)}</dd></div>`).join('');
      const active = $(`[data-na-pick="${idx}"]`, na);
      const strip = $('[data-na-thumbs]', na);
      if (active && strip) strip.scrollTo({ left: active.offsetLeft - strip.offsetLeft - 8, behavior: 'smooth' });
    };

    na.addEventListener('click', (e) => {
      const tab = e.target.closest('[data-na-tab]');
      if (tab) {
        gender = tab.dataset.naTab; idx = 0;
        $$('[data-na-tab]', na).forEach((b) => b.setAttribute('aria-selected', b === tab ? 'true' : 'false'));
        render(); return;
      }
      const pick = e.target.closest('[data-na-pick]');
      if (pick) { idx = Number(pick.dataset.naPick); render(); return; }
      const im = e.target.closest('[data-na-img]');
      if (im) {
        setMain(im.dataset.naImg, '');
        $$('[data-na-img]', na).forEach((b) => { b.classList.toggle('border-forest', b === im); b.classList.toggle('border-transparent', b !== im); });
        return;
      }
      const len = (data[gender] || []).length;
      if (e.target.closest('[data-na-prev]')) { idx = (idx - 1 + len) % len; render(); }
      if (e.target.closest('[data-na-next]')) { idx = (idx + 1) % len; render(); }
    });
    render();
  }

  /* ------------------------------------------------------------------ */
  /* Product page                                                        */
  /* ------------------------------------------------------------------ */
  const pdp = $('[data-product]');
  if (pdp) {
    const slug = pdp.dataset.product;
    let size = null;
    const err = $('[data-size-error]', pdp);
    const qtyEl = $('[data-pdp-qty]', pdp);
    let qty = 1;

    $$('[data-pdp-size]', pdp).forEach((b) => b.addEventListener('click', () => {
      size = b.dataset.pdpSize;
      $$('[data-pdp-size]', pdp).forEach((x) => x.setAttribute('aria-pressed', x === b ? 'true' : 'false'));
      $$('[data-selected-size]').forEach((el) => { el.textContent = size; });
      if (err) err.classList.add('hidden');
    }));

    $$('[data-pdp-step]', pdp).forEach((b) => b.addEventListener('click', () => {
      qty = Math.max(1, Math.min(10, qty + Number(b.dataset.pdpStep)));
      if (qtyEl) qtyEl.textContent = qty;
    }));

    const requireSize = () => {
      if (size) return true;
      if (err) err.classList.remove('hidden');
      const sizes = $('[data-sizes]', pdp);
      if (sizes) { sizes.scrollIntoView({ behavior: 'smooth', block: 'center' }); sizes.animate([{ transform: 'translateX(0)' }, { transform: 'translateX(-6px)' }, { transform: 'translateX(6px)' }, { transform: 'translateX(0)' }], { duration: 300 }); }
      return false;
    };

    $$('[data-pdp-add]').forEach((b) => b.addEventListener('click', () => { if (requireSize()) { Cart.add(slug, size, qty); open('cart'); } }));
    $$('[data-pdp-buy]').forEach((b) => b.addEventListener('click', () => { if (requireSize()) { Cart.add(slug, size, qty); window.location.href = FL.urls.checkout; } }));

    // Desktop gallery: thumbnails swap main image
    const main = $('[data-pdp-main]', pdp);
    $$('[data-pdp-thumb]', pdp).forEach((t) => t.addEventListener('click', () => {
      if (!main) return;
      main.src = t.dataset.pdpThumb;
      $$('[data-pdp-thumb]', pdp).forEach((x) => { x.classList.toggle('border-forest', x === t); x.classList.toggle('border-transparent', x !== t); });
    }));

    // Zoom on hover (desktop)
    const zoom = $('[data-zoom]', pdp);
    if (zoom && main) {
      zoom.addEventListener('mousemove', (e) => {
        const r = zoom.getBoundingClientRect();
        main.style.transformOrigin = `${((e.clientX - r.left) / r.width) * 100}% ${((e.clientY - r.top) / r.height) * 100}%`;
        main.style.transform = 'scale(1.8)';
      });
      zoom.addEventListener('mouseleave', () => { main.style.transform = ''; });
    }

    // Sticky mobile bar appears once the main CTA scrolls away
    const sticky = $('[data-sticky-bar]');
    const cta = $('[data-pdp-add]', pdp);
    if (sticky && cta && 'IntersectionObserver' in window) {
      new IntersectionObserver(([en]) => {
        const hide = en.isIntersecting || en.boundingClientRect.top > 0;
        sticky.classList.toggle('translate-y-full', hide);
        document.body.classList.toggle('has-sticky-bar', !hide && window.innerWidth < 1024);
      }).observe(cta);
    }

    // Pincode check (demo: validates Indian PIN format)
    const pin = $('[data-pincode]', pdp);
    if (pin) {
      pin.addEventListener('submit', (e) => {
        e.preventDefault();
        const v = pin.querySelector('input').value.trim();
        const out = $('[data-pincode-result]', pdp);
        if (/^[1-9][0-9]{5}$/.test(v)) {
          out.innerHTML = `<span class="text-moss">✓ Delivery available to ${esc(v)} · Free shipping · Dispatched in 3–4 business days</span>`;
        } else {
          out.innerHTML = '<span class="text-clay">Please enter a valid 6-digit PIN code.</span>';
        }
      });
    }

    // Share
    const share = $('[data-share]', pdp);
    if (share) share.addEventListener('click', async () => {
      const data = { title: document.title, url: window.location.href };
      try {
        if (navigator.share) await navigator.share(data);
        else { await navigator.clipboard.writeText(data.url); toast('Link copied to clipboard'); }
      } catch (e) { /* cancelled */ }
    });
  }


  /* ------------------------------------------------------------------ */
  /* Static build (GitHub Pages): shop filters / sort / search in JS      */
  /* The PHP version does this on the server with the same parameters.   */
  /* ------------------------------------------------------------------ */
  if (document.body.dataset.static === '1' && document.body.dataset.page === 'shop') {
    const params = new URLSearchParams(window.location.search);
    const grid = $('[data-shop-grid]');
    if (params.get('cat')) {
      // Forms that still carry ?cat= go to that category's page
      const cat = params.get('cat');
      params.delete('cat');
      const qs = params.toString();
      window.location.replace(`shop-${encodeURIComponent(cat)}.html${qs ? '?' + qs : ''}`);
    } else if (grid) {
      const q = (params.get('q') || '').trim();
      const sizes = params.getAll('size[]').filter((s) => ['S', 'M', 'L', 'XL'].includes(s));
      const price = params.get('price') || '';
      const max = params.has('max') ? Number(params.get('max')) : null;
      const sort = params.get('sort') || '';
      const ranges = { u2500: [0, 2499, 'Under ₹2,500'], '2500-3500': [2500, 3500, '₹2,500 – ₹3,500'], o3500: [3501, Infinity, 'Above ₹3,500'] };
      const cards = $$('.pcard', grid);
      let shown = cards.filter((c) => {
        if (q && !(c.dataset.search || '').includes(q.toLowerCase())) return false;
        if (sizes.length && !sizes.some((s) => (c.dataset.sizes || '').split(' ').includes(s))) return false;
        if (ranges[price]) {
          const v = Number(c.dataset.price);
          if (v < ranges[price][0] || v > ranges[price][1]) return false;
        }
        if (max !== null && !Number.isNaN(max) && Number(c.dataset.price) > max) return false;
        return true;
      });
      const by = {
        new: (a, b) => b.dataset.id - a.dataset.id,
        'price-asc': (a, b) => a.dataset.price - b.dataset.price,
        'price-desc': (a, b) => b.dataset.price - a.dataset.price,
      };
      if (by[sort]) shown = shown.slice().sort(by[sort]);
      cards.forEach((c) => c.classList.toggle('hidden', !shown.includes(c)));
      shown.forEach((c) => grid.appendChild(c));

      const n = shown.length;
      $('[data-shop-count]').textContent = n;
      $('[data-shop-word]').textContent = n === 1 ? 'product' : 'products';
      grid.classList.toggle('hidden', n === 0);
      $('[data-shop-empty]').classList.toggle('hidden', n > 0);
      if (q) {
        $('[data-shop-heading]').textContent = `Search: “${q}”`;
        $('[data-shop-blurb]').textContent = `${n} ${n === 1 ? 'piece matches' : 'pieces match'} your search.`;
        document.title = 'Search results | Faelynn';
      } else if (sort === 'new' && /\/shop\.html$/.test(window.location.pathname)) {
        $('[data-shop-heading]').textContent = 'New Arrivals';
      }

      // Reflect active filters in the form controls and carry them between the two forms
      $$('input[name="size[]"]').forEach((i) => { i.checked = sizes.includes(i.value); });
      $$('input[name="price"]').forEach((i) => { i.checked = i.value === price; });
      const sel = $('#sort');
      if (sel) sel.value = sort;
      $$('form[data-autosubmit]').forEach((f) => {
        const add = (name, value) => {
          const h = document.createElement('input');
          h.type = 'hidden'; h.name = name; h.value = value;
          f.appendChild(h);
        };
        if (q && !f.querySelector('input[name="q"]')) add('q', q);
        if (max !== null && !f.querySelector('input[name="max"]')) add('max', max);
        if (f.hasAttribute('data-sort-form')) {
          sizes.forEach((s) => add('size[]', s));
          if (price) add('price', price);
        } else {
          const s = f.querySelector('input[name="sort"]');
          if (s) s.value = sort;
        }
      });

      // Removable filter chips
      const chips = $('[data-shop-chips]');
      const page = window.location.pathname.split('/').pop() || 'shop.html';
      const link = (mutate) => {
        const p = new URLSearchParams(window.location.search);
        mutate(p);
        const s = p.toString();
        return page + (s ? '?' + s : '');
      };
      const chip = (label, href) => `<a href="${esc(href)}" class="inline-flex items-center gap-1.5 rounded-full bg-cream px-3 py-1.5 text-forest">${esc(label)} ✕</a>`;
      const parts = [];
      if (q) parts.push(chip(`“${q}”`, link((p) => p.delete('q'))));
      sizes.forEach((s) => parts.push(chip(`Size ${s}`, link((p) => {
        const rest = p.getAll('size[]').filter((x) => x !== s);
        p.delete('size[]');
        rest.forEach((x) => p.append('size[]', x));
      }))));
      if (ranges[price]) parts.push(chip(ranges[price][2], link((p) => p.delete('price'))));
      if (max !== null) parts.push(chip(`Up to ${money(max)}`, link((p) => p.delete('max'))));
      if (parts.length && chips) {
        chips.innerHTML = parts.join('') + `<a href="${esc(page)}" class="ml-1 text-xs uppercase tracking-widest text-ink-muted underline underline-offset-4 hover:text-forest">Clear all</a>`;
        chips.classList.remove('hidden');
        chips.classList.add('flex');
      }
    }
  }

  /* ------------------------------------------------------------------ */
  /* Shop filters: auto-submit on change                                 */
  /* ------------------------------------------------------------------ */
  $$('[data-autosubmit]').forEach((form) => {
    form.addEventListener('change', () => form.submit());
  });

  /* ------------------------------------------------------------------ */
  /* Read more                                                           */
  /* ------------------------------------------------------------------ */
  $$('[data-readmore-btn]').forEach((btn) => {
    const box = btn.previousElementSibling;
    btn.addEventListener('click', () => {
      const openNow = box.classList.toggle('max-h-24');
      box.style.maxHeight = openNow ? '' : box.scrollHeight + 'px';
      const fade = $('[data-readmore-fade]', box);
      if (fade) fade.classList.toggle('hidden', !openNow);
      btn.textContent = openNow ? 'Show more' : 'Show less';
    });
  });

  /* ------------------------------------------------------------------ */
  /* Forms (demo — no backend)                                           */
  /* ------------------------------------------------------------------ */
  $$('[data-newsletter]').forEach((f) => f.addEventListener('submit', (e) => {
    e.preventDefault();
    const input = f.querySelector('input[type=email]');
    if (!input.value || !input.checkValidity()) { toast('Please enter a valid email address'); input.focus(); return; }
    input.value = '';
    toast('Thank you for subscribing to Faelynn ✿');
  }));

  $$('[data-demo-form]').forEach((f) => f.addEventListener('submit', (e) => {
    e.preventDefault();
    if (!f.checkValidity()) { f.reportValidity(); return; }
    const ok = f.querySelector('[data-form-success]');
    if (ok) { ok.classList.remove('hidden'); ok.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
    f.querySelectorAll('input:not([type=radio]):not([type=checkbox]), textarea').forEach((i) => { i.value = ''; });
    toast(f.dataset.demoForm || 'Thank you! We will get back to you shortly.');
  }));

  // Checkout
  const checkout = $('[data-checkout]');
  if (checkout) {
    checkout.addEventListener('submit', (e) => {
      e.preventDefault();
      if (!checkout.checkValidity()) { checkout.reportValidity(); return; }
      const items = Cart.items();
      if (!items.length) return;
      const orderNo = 'FL' + String(Date.now()).slice(-7);
      const fd = new FormData(checkout);
      const done = $('[data-order-done]');
      $('[data-order-no]', done).textContent = '#' + orderNo;
      $('[data-order-email]', done).textContent = fd.get('email');
      $('[data-order-total]', done).textContent = money(Cart.subtotal());
      checkout.classList.add('is-done', 'hidden');
      $('[data-checkout-aside]').classList.add('hidden');
      done.classList.remove('hidden');
      Cart.clear();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ------------------------------------------------------------------ */
  /* Help chat (quick answers + hand-over to WhatsApp)                   */
  /* ------------------------------------------------------------------ */
  const chat = $('[data-chat]');
  if (chat) {
    const panel = $('[data-chat-panel]', chat);
    const log = $('[data-chat-log]', chat);
    const input = $('[data-chat-input]', chat);
    const hint = $('[data-chat-hint]', chat);
    const dot = $('[data-chat-dot]', chat);
    const launcher = $('button[aria-controls="chat-panel"]', chat);
    let data = { topics: {}, wa: '' };
    try { data = JSON.parse($('#chat-data').textContent); } catch (e) { /* keep defaults */ }

    const scrollDown = () => { log.scrollTop = log.scrollHeight; };
    const bubble = (cls, html) => {
      const el = document.createElement('div');
      el.className = cls;
      el.innerHTML = html;
      log.appendChild(el);
      scrollDown();
      return el;
    };
    const botReply = (html, delay = 700) => {
      const typing = bubble('chat-bot chat-typing', '<span></span><span></span><span></span>');
      setTimeout(() => { typing.remove(); bubble('chat-bot', html); }, delay);
    };
    const waLink = (text) => `https://wa.me/${data.wa}?text=${encodeURIComponent(text)}`;

    const setOpen = (on) => {
      panel.classList.toggle('is-open', on);
      panel.setAttribute('aria-hidden', on ? 'false' : 'true');
      launcher.setAttribute('aria-expanded', on ? 'true' : 'false');
      $('[data-chat-icon-open]', chat).classList.toggle('hidden', on);
      $('[data-chat-icon-close]', chat).classList.toggle('hidden', !on);
      if (hint) hint.classList.add('sm:hidden');
      if (dot) dot.remove();
      store.set('fl_chat_seen', 1);
      if (on) setTimeout(() => input.focus({ preventScroll: true }), 200);
      else launcher.focus({ preventScroll: true });
    };

    chat.addEventListener('click', (e) => {
      if (e.target.closest('[data-chat-toggle]')) { setOpen(!panel.classList.contains('is-open')); return; }
      const t = e.target.closest('[data-chat-topic]');
      if (t) {
        const topic = data.topics[t.dataset.chatTopic];
        if (!topic) return;
        bubble('chat-user', esc(topic.q));
        botReply(`${topic.a}<br><a href="${esc(topic.link[0])}">${esc(topic.link[1])} →</a><br><span class="mt-2 block text-xs text-ink-muted">Anything else? Pick another topic or type below.</span>`);
      }
    });

    $('[data-chat-form]', chat).addEventListener('submit', (e) => {
      e.preventDefault();
      const text = input.value.trim();
      if (!text) { input.focus(); return; }
      bubble('chat-user', esc(text).replace(/\n/g, '<br>'));
      input.value = '';
      input.style.height = '';
      const page = document.title.split('|')[0].trim();
      const url = waLink(`Hi Faelynn! ${text}\n\n(Page: ${page})`);
      botReply(`Thanks! We’re opening WhatsApp so our team can reply to you personally (${esc(data.hours || '')}).<br><a href="${url}" target="_blank" rel="noopener">Continue on WhatsApp →</a>`, 600);
      setTimeout(() => { window.open(url, '_blank', 'noopener'); }, 900);
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); $('[data-chat-form]', chat).requestSubmit(); }
    });
    input.addEventListener('input', () => { input.style.height = 'auto'; input.style.height = Math.min(input.scrollHeight, 112) + 'px'; });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && panel.classList.contains('is-open')) setOpen(false); });

    // Gentle nudge for first-time visitors
    if (store.get('fl_chat_seen', 0)) {
      if (hint) hint.remove();
      if (dot) dot.remove();
    } else if (hint) {
      setTimeout(() => { if (hint.isConnected) hint.classList.add('sm:hidden'); }, 9000);
    }
  }

  /* ------------------------------------------------------------------ */
  /* Boot                                                                */
  /* ------------------------------------------------------------------ */
  initSwipers();
  renderCart();
  syncWishlist();
  renderWishlistPage();
  window.addEventListener('storage', () => { renderCart(); syncWishlist(); renderWishlistPage(); });
})();
