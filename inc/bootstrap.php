<?php
/**
 * Faelynn — bootstrap: config, data access and template helpers.
 *
 * WordPress migration notes:
 *  - fl_products() / fl_product()      → wc_get_products() / wc_get_product()
 *  - fl_url()                          → get_permalink() / wc_get_page_permalink()
 *  - fl_partial()                      → get_template_part()
 *  - partials/header.php, footer.php   → header.php, footer.php
 */

declare(strict_types=1);

const FL_SITE_NAME = 'Faelynn';
const FL_TAGLINE   = 'Love for nature';
const FL_PHONE     = '+91 9625309827';
const FL_PHONE_RAW = '919625309827';
const FL_EMAIL     = 'customercare@faelynn.co.in';
const FL_ADDRESS   = 'Whitefield, Bengaluru, Karnataka';
const FL_HOURS     = '10 am – 5 pm, Mon – Sat';
const FL_INSTAGRAM = 'https://www.instagram.com/faelynn.co.in';
const FL_FACEBOOK  = 'https://www.facebook.com/profile.php?id=61556502650105';
const FL_ASSET_VER = '1.1.0';
/** Static export mode (GitHub Pages build): FL_STATIC=1 php -S … */
define('FL_STATIC', getenv('FL_STATIC') === '1');

require __DIR__ . '/content.php';

/* ------------------------------------------------------------------ */
/*  Escaping & formatting                                              */
/* ------------------------------------------------------------------ */

function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function fl_price(int|float $amount): string
{
    // Indian grouping: 1,23,456
    $n = (string) (int) round($amount);
    $last3 = substr($n, -3);
    $rest  = substr($n, 0, -3);
    if ($rest !== '') {
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
        return '₹' . $rest . ',' . $last3;
    }
    return '₹' . $last3;
}

function fl_asset(string $path): string
{
    return $path . (str_ends_with($path, '.css') || str_ends_with($path, '.js') ? '?v=' . FL_ASSET_VER : '');
}

/** Build an internal URL. Keeps every link relative so the site works from any folder. */
function fl_url(string $page = 'home', array $args = []): string
{
    $map = [
        'home' => 'index.php', 'shop' => 'shop.php', 'product' => 'product.php', 'cart' => 'cart.php',
        'checkout' => 'checkout.php', 'wishlist' => 'wishlist.php', 'account' => 'account.php',
        'about' => 'about.php', 'contact' => 'contact.php', 'faq' => 'faq.php',
        'custom-order' => 'custom-order.php', 'policy' => 'policy.php', 'journal' => 'journal.php',
        'track-order' => 'track-order.php',
    ];
    $url = $map[$page] ?? $page;
    $args = array_filter($args, fn($v) => $v !== null && $v !== '');
    return $args ? $url . '?' . http_build_query($args) : $url;
}

function fl_product_url(array $p): string
{
    return fl_url('product', ['slug' => $p['slug']]);
}

function fl_category_url(string $slug): string
{
    return fl_url('shop', ['cat' => $slug]);
}

function fl_partial(string $name, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    include __DIR__ . '/../partials/' . $name . '.php';
}

function fl_icon(string $name, string $class = 'h-5 w-5'): string
{
    $paths = [
        'search'  => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'heart'   => '<path d="M19.5 12.6 12 20l-7.5-7.4A4.9 4.9 0 0 1 12 6.1a4.9 4.9 0 0 1 7.5 6.5Z"/>',
        'user'    => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'bag'     => '<path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>',
        'menu'    => '<path d="M4 7h16M4 12h16M4 17h10"/>',
        'close'   => '<path d="M6 6l12 12M18 6 6 18"/>',
        'arrow-r' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'arrow-l' => '<path d="M19 12H5M11 6l-6 6 6 6"/>',
        'chev-r'  => '<path d="m9 6 6 6-6 6"/>',
        'chev-l'  => '<path d="m15 6-6 6 6 6"/>',
        'chev-d'  => '<path d="m6 9 6 6 6-6"/>',
        'plus'    => '<path d="M12 5v14M5 12h14"/>',
        'minus'   => '<path d="M5 12h14"/>',
        'truck'   => '<path d="M3 6h11v10H3zM14 10h4l3 3v3h-7"/><circle cx="7" cy="17.5" r="1.8"/><circle cx="17" cy="17.5" r="1.8"/>',
        'return'  => '<path d="M4 10h11a5 5 0 0 1 0 10H9"/><path d="m8 6-4 4 4 4"/>',
        'leaf'    => '<path d="M5 19c0-8 6-14 15-14 0 9-6 15-14 15"/><path d="M5 19 13 11"/>',
        'needle'  => '<path d="M4 20 18 6"/><circle cx="19" cy="5" r="1.5"/><path d="M8 20c-2 0-4-1-4-3s3-2 5-4 2-5 0-6"/>',
        'sparkle' => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M6 18l2.5-2.5M15.5 8.5 18 6"/>',
        'feather' => '<path d="M20 4c-8 0-14 6-14 14v2"/><path d="M6 14h7M9 10h7"/><path d="M20 4c0 8-4 12-11 13"/>',
        'phone'   => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
        'mail'    => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'pin'     => '<path d="M12 21s-7-6.2-7-12a7 7 0 0 1 14 0c0 5.8-7 12-7 12Z"/><circle cx="12" cy="9" r="2.5"/>',
        'clock'   => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'ruler'   => '<path d="M3 17 17 3l4 4L7 21z"/><path d="m7 13 2 2M10 10l2 2M13 7l2 2"/>',
        'check'   => '<path d="m5 12 5 5 9-10"/>',
        'shield'  => '<path d="M12 3 5 6v6c0 4.5 3 7.5 7 9 4-1.5 7-4.5 7-9V6z"/><path d="m9 12 2 2 4-4"/>',
        'filter'  => '<path d="M4 6h16M7 12h10M10 18h4"/>',
        'whatsapp'=> '<path d="M4 20l1.3-4A8 8 0 1 1 8 18.7z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 1c-1-.5-2-1.5-2.5-2.5l1-1-1-2z"/>',
        'quote'   => '<path d="M7 7h4v4c0 3-1.5 5-4 6M15 7h4v4c0 3-1.5 5-4 6"/>',
        'scissors'=> '<circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M8.5 8 20 18M8.5 16 20 6"/>',
    ];
    $p = $paths[$name] ?? '';
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $p . '</svg>';
}

function fl_social_icon(string $name, string $class = 'h-5 w-5'): string
{
    $icons = [
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="17.5" cy="6.5" r="1.1" fill="currentColor"/>',
        'facebook'  => '<path fill="currentColor" d="M13.5 21v-7.5H16l.4-3h-2.9V8.6c0-.9.3-1.5 1.5-1.5h1.5V4.4c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v2.4H8v3h2.6V21z"/>',
        'whatsapp'  => '<path fill="currentColor" d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2c-1.5 0-3-.4-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.4.8 3.2.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.2-1.2-.1-.1-.3-.2-.5-.3Z"/>',
    ];
    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
}

/* ------------------------------------------------------------------ */
/*  Product data (mirrors faelynn.co.in WooCommerce catalogue)         */
/* ------------------------------------------------------------------ */

function fl_products(): array
{
    static $products = null;
    if ($products === null) {
        $json = file_get_contents(__DIR__ . '/../data/products.json');
        $products = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
    return $products;
}

function fl_product(string $slug): ?array
{
    foreach (fl_products() as $p) {
        if ($p['slug'] === $slug) {
            return $p;
        }
    }
    return null;
}

/** Fetch products by an ordered list of slugs (unknown slugs are skipped). */
function fl_pick(array $slugs): array
{
    return array_values(array_filter(array_map('fl_product', $slugs)));
}

function fl_in_category(array $p, string $cat): bool
{
    return in_array($cat, $p['categories'], true);
}

function fl_category(string $slug): ?array
{
    return FL_CATEGORIES[$slug] ?? null;
}

function fl_category_count(string $slug): int
{
    return count(array_filter(fl_products(), fn($p) => fl_in_category($p, $slug)));
}

function fl_category_label(array $p): string
{
    return FL_CATEGORIES[$p['primary_category']]['label'] ?? ucfirst($p['gender']);
}

/** Related products: same gender, sharing a category first. */
function fl_related(array $product, int $limit = 8): array
{
    $pool = array_filter(fl_products(), fn($p) => $p['slug'] !== $product['slug'] && $p['gender'] === $product['gender']);
    usort($pool, function ($a, $b) use ($product) {
        $sa = count(array_intersect($a['categories'], $product['categories']));
        $sb = count(array_intersect($b['categories'], $product['categories']));
        return $sb <=> $sa;
    });
    return array_slice(array_values($pool), 0, $limit);
}

/** Slim product data for the JS cart / wishlist / search. */
function fl_products_js(): array
{
    return array_map(fn($p) => [
        'id' => $p['id'],
        'slug' => $p['slug'],
        'name' => $p['name'],
        'short' => $p['short_name'],
        'cat' => fl_category_label($p),
        'gender' => $p['gender'],
        'price' => $p['price'],
        'img' => $p['images'][0]['src'],
        'img2' => $p['images'][1]['src'] ?? $p['images'][0]['src'],
        'sizes' => $p['sizes'],
        'url' => fl_product_url($p),
        'tags' => $p['tags'],
    ], fl_products());
}

function fl_current_page(): string
{
    return basename($_SERVER['SCRIPT_NAME'] ?? 'index.php', '.php');
}
