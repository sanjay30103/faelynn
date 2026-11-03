# Faelynn — Website Redesign (PHP + Tailwind CSS)

Redesign of [faelynn.co.in](https://faelynn.co.in/) as a modern e-commerce storefront.
Layout inspired by jockey.in (hero slider, Most Loved, On-Trend Picks, New Arrivals showcase,
Best Sellers with Men/Women tabs, featured categories, journal, popular searches footer),
styled around the Faelynn logo — **"love for nature"**: forest-green ink, ivory/cotton neutrals,
serif headlines (Cormorant Garamond) and a clean sans (Jost), with the logo's dragonfly as a motif.

All products, prices, descriptions, images, policies, FAQs, size chart, testimonials and contact
details are taken from the live site (WooCommerce Store API + WordPress REST API).

**Live demo:** https://sanjay30103.github.io/faelynn/ (static build on GitHub Pages, from the `gh-pages` branch)

## Run the demo

```bash
cd site
php -S localhost:8000        # then open http://localhost:8000
```

Needs PHP 8.0+. No database. Works from any folder (all links are relative), so it can also be
dropped into XAMPP/MAMP `htdocs` or any shared host.

## Edit styles

```bash
npm install
npm run dev     # watches PHP/JS and rebuilds assets/css/app.css
npm run build   # minified production build
```

## Publish the static demo (GitHub Pages)

GitHub Pages can't run PHP, so `tools/build-static.py` renders every page to plain HTML
(`product-fae-dress.html`, `shop-women.html`, …) and rewrites the links. Shop filters, sort and
search then run in the browser (same URL parameters as the PHP version).

```bash
npm run build                          # CSS
python3 tools/build-static.py dist     # → dist/ (40 pages + assets)
# then push the contents of dist/ to the gh-pages branch
```

## What works in the demo

| Feature | Notes |
|---|---|
| Home page | 3-slide hero, USP strip, Most Loved, On-Trend Picks, Slow & Soulful (tabs), New Arrivals interactive showcase, Best Sellers (tabs), brand band, Featured Categories (tabs), Why Faelynn, custom-order banner, testimonials, journal, Instagram, SEO text |
| Shop / category | `shop.php?cat=…` — category, size & price filters, sort, search (`?q=`) — all server-side |
| Product page | Gallery with zoom, size selection, size guide (women), quantity, add to bag, buy now, PIN-code check, accordions, related products, sticky mobile bar, Product JSON-LD |
| Cart | Slide-in bag drawer, quick-add size picker, cart page, quantity / remove |
| Checkout | Address form with validation → order confirmation (**demo, no payment**) |
| Wishlist, search | Wishlist page + live search overlay |
| Logo preloader | Wordmark inks in, the dragonfly lands on the logo, then a curtain lifts (~1 s, capped at 1.5 s). Browsing pages only: list in `partials/header.php` (`$show_preloader`), timing in `partials/preloader.php`. Skipped on product, cart, checkout, account, wishlist, track order and on shop filter changes. |
| Help chat | Floating chat (bottom-right): instant answers for orders, sizing, returns, custom orders & shipping (from Faelynn's FAQ/policies); typed messages continue on WhatsApp (+91 9625309827). Can later be swapped for Tawk.to / Crisp / WhatsApp Business API. |
| Content pages | About, Contact, FAQs, Custom Order (size chart), Policies (shipping, returns, wash care, privacy, terms), Journal, Track Order, Account, 404 |

Cart and wishlist are stored in the browser (localStorage) for the demo.
Forms (contact, newsletter, custom order, sign-in) show a success state but do not send anything.

## Hero photo credits

The home-page hero uses free stock photos (Unsplash License: free for commercial use, no attribution required — credited here anyway). They are mood images, not Faelynn products; swap them for Faelynn's own shoot before launch.

| File | Photographer | Source |
|---|---|---|
| `assets/images/hero/hero-women.jpg` | Roman Manshin | https://unsplash.com/photos/woman-poses-gracefully-while-sitting-on-a-sofa-TBvmoNk7NaI |
| `assets/images/hero/hero-men.jpg` | Nadeem Choudhary | https://unsplash.com/photos/young-man-looking-out-over-a-lake-with-buildings-hRjU85idTMs |
| `assets/images/hero/hero-handcrafted.jpg` | Jordan González | https://unsplash.com/photos/a-woman-in-a-white-dress-sitting-on-a-bench-Z3B6e6ELkdg |

## Structure

```
site/
├── index.php              → front-page.php
├── shop.php               → woocommerce/archive-product.php
├── product.php            → woocommerce/single-product.php
├── cart.php / checkout.php→ WooCommerce cart & checkout (blocks or templates)
├── about.php, contact.php, faq.php, custom-order.php, policy.php, journal.php …  → page-*.php
├── partials/              → template-parts/ (header, footer, product-card, page-hero)
├── inc/bootstrap.php      → functions.php (helpers, icons)
├── inc/content.php        → Customizer / ACF options (USPs, testimonials, FAQ, size chart)
├── data/products.json     → replaced by WooCommerce products
├── data/policies.json     → replaced by WordPress pages
├── assets/css, js, images, vendor/swiper
└── tailwind.config.js     → design tokens (colors, fonts)
```

## WordPress / WooCommerce conversion map

- `fl_products()`, `fl_product()`, `fl_pick()` → `wc_get_products()`, `wc_get_product()`, product tags or ACF relationship fields for curated groups.
- `fl_url()` / `fl_product_url()` → `get_permalink()`, `get_term_link()`, `wc_get_cart_url()`, `wc_get_checkout_url()`.
- `fl_partial()` → `get_template_part()`.
- JS cart (`assets/js/app.js` → `Cart`) → WooCommerce Store API (`/wp-json/wc/store/v1/cart/add-item`) so the drawer keeps working.
- Wishlist → YITH / TI Wishlist or a small custom endpoint.
- Tailwind `content` paths: point at the theme's `*.php`, `template-parts/**/*.php`, `woocommerce/**/*.php`.
