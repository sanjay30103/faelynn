<?php
/**
 * Site content — all copy is taken from faelynn.co.in (products, policies, FAQ,
 * testimonials, brand story). In WordPress these become Customizer / ACF options.
 */

/** Product categories (slugs match the live WooCommerce store). */
const FL_CATEGORIES = [
    'women' => [
        'label' => 'Women', 'gender' => 'women', 'parent' => null,
        'blurb' => 'Breathable, hand-finished cotton for slow days and deep sleep.',
        'image' => 'assets/images/brand/Designer-3.jpg',
    ],
    'dresses-women' => [
        'label' => 'Dresses', 'gender' => 'women', 'parent' => 'women',
        'blurb' => 'Flowy, breathable & hand-embroidered.',
        'image' => 'assets/images/products/fae1.jpg', 'tone' => 'bg-rose-pale',
    ],
    'co-ord-set-women' => [
        'label' => 'Co-ord Sets', 'gender' => 'women', 'parent' => 'women',
        'blurb' => 'Matching sets, made for ease.',
        'image' => 'assets/images/products/belinda2.jpg', 'tone' => 'bg-sky-pale',
    ],
    'night-dress-women' => [
        'label' => 'Night Dresses', 'gender' => 'women', 'parent' => 'women',
        'blurb' => 'Featherweight cotton for peaceful sleep.',
        'image' => 'assets/images/products/img-9800.jpg', 'tone' => 'bg-cream',
    ],
    'men' => [
        'label' => 'Men', 'gender' => 'men', 'parent' => null,
        'blurb' => 'Relaxed cotton shirts and easy trousers for long, warm days.',
        'image' => 'assets/images/brand/Designer-1.jpg',
    ],
    'shirt-men' => [
        'label' => 'Shirts', 'gender' => 'men', 'parent' => 'men',
        'blurb' => 'Super-relaxed cotton with coconut-shell buttons.',
        'image' => 'assets/images/products/walden.jpg', 'tone' => 'bg-sand',
    ],
    'trouser-men' => [
        'label' => 'Trousers', 'gender' => 'men', 'parent' => 'men',
        'blurb' => 'Easy-fit, elasticated & breathable.',
        'image' => 'assets/images/products/rio.jpg', 'tone' => 'bg-sage-pale',
    ],
    'shorts-men' => [
        'label' => 'Shorts', 'gender' => 'men', 'parent' => 'men',
        'blurb' => 'Light cotton twill for summer.',
        'image' => 'assets/images/products/ethan.jpg', 'tone' => 'bg-cream',
    ],
];

/** Curated product groups for the home page (all real products). */
const FL_GROUPS = [
    'most_loved'      => ['heath-a-line-dress', 'haizea-oversized-co-ords', 'carolyn-floral-dress', 'ramah-night-dress', 'walden-shirt', 'fae-dress', 'ethan', 'sybil-co-ord-set'],
    'new_women'       => ['haizea-oversized-dress', 'haizea-oversized-co-ords', 'fae-dress', 'belinda-co-ord-set', 'heath-a-line-dress'],
    'new_men'         => ['ridge-coat-style-shirt', 'walden-shirt', 'ethan', 'rio'],
    'best_women'      => ['heath-a-line-dress', 'haizea-oversized-co-ords', 'carolyn-floral-dress', 'ramah-night-dress', 'samara-front-open-night-dress', 'sybil-co-ord-set', 'belinda-co-ord-set'],
    'best_men'        => ['walden-shirt', 'ethan', 'rio', 'ridge-coat-style-shirt'],
    'sleep_women'     => ['ramah-night-dress', 'samara-front-open-night-dress', 'carolyn-floral-dress', 'sybil-co-ord-set', 'belinda-co-ord-set'],
    'sleep_men'       => ['rio', 'ethan', 'walden-shirt', 'ridge-coat-style-shirt'],
];

const FL_ANNOUNCEMENTS = [
    'Free delivery on all orders across India',
    'Easy returns within 10 days',
    'Sign up & get ₹100 off your first order',
    '100% cotton · handcrafted by Indian artisans',
    'Custom orders welcome — made to your measurements',
];

const FL_USPS = [
    ['icon' => 'leaf',    'title' => 'Breathable Cotton', 'text' => 'Soft, airy fabric for everyday comfort.'],
    ['icon' => 'needle',  'title' => 'Handcrafted',       'text' => 'Thoughtful details with a premium finish.'],
    ['icon' => 'feather', 'title' => 'All-Day Comfort',   'text' => 'Relaxed silhouettes for ease.'],
    ['icon' => 'sparkle', 'title' => 'Everyday Premium',  'text' => 'Elegant pieces you’ll love wearing.'],
];

const FL_TESTIMONIALS = [
    ['title' => 'Incredibly Comfortable', 'text' => 'Faelynn feels soft, elegant, and incredibly comfortable to wear. The fabric quality and calm, premium feel make it perfect for relaxing at home.', 'name' => 'Shailee Mishra', 'role' => 'Engineer'],
    ['title' => 'Breathable', 'text' => 'I loved the breathable cotton and the thoughtful finish of the outfit. Faelynn strikes a lovely balance between comfort and a refined look.', 'name' => 'Snehlatha Pandey', 'role' => 'Software Professional'],
    ['title' => 'Soft and Comfortable', 'text' => 'The Carolyn Floral Dress is exceptionally soft and comfortable. It offers the perfect blend of coziness and ease of movement — ideal for both lounging and daily wear at home.', 'name' => 'Deepika Mishra', 'role' => 'Asst. Professor, AIIMS'],
];

const FL_STORY = [
    'Faelynn was born from a simple wish — to slow down and find beauty in the quiet. We craft every piece from soft, breathable cotton, designed to move with ease and feel like a gentle touch against the skin.',
    'Each garment carries the care of Indian artisans, whose hands weave stories of nature, memory, and tradition into every thread. We don’t follow trends — we create with feeling, shaping clothes for those who find strength in stillness and grace in every moment.',
    'Faelynn is more than clothing. It’s a way of being — soft, soulful, and made by hand.',
];

const FL_WORKSHOP = [
    'The air is thick with the scent of fresh dye and earth, and the occasional gentle hum of conversation floats between the workers — a shared language of artistry and respect. Overhead, colorful fabrics dry in the soft breeze, their patterns swaying like poems written by hand.',
    'This workshop is more than a place of work; it is a living, breathing testament to slow craftsmanship — an ode to the enduring human spirit that finds joy in creation, meaning in the tactile, and connection in the handmade. Every piece born here carries not just the beauty of its design but the soul of the person who shaped it — a quiet, graceful resistance to the noise of mass production.',
    'In this sacred space, clothing is not just made; it is lived into being. Every print, every fold, every stitch whispers a story — of nature’s inspiration, of hands that love their craft, and of a world where time still listens to the patient heartbeat of artistry.',
];

/** Size chart from faelynn.co.in/custom-order (inches). */
const FL_SIZE_CHART = [
    'head' => ['Size', 'Bust', 'Waist', 'Hip', 'Shoulder'],
    'rows' => [
        ['S',  '34–36', '26–28', '36–38', '13.5'],
        ['M',  '36–38', '28–30', '38–40', '14'],
        ['L',  '38–40', '32–34', '42–44', '14.5–15'],
        ['XL', '40–42', '36–38', '44–46', '15.5–16'],
    ],
];

const FL_FAQ = [
    'My Orders' => [
        ['q' => 'How do I track my order?', 'a' => 'Please log in to your account on faelynn.co.in and go to the My Orders section to access your order history. If you are a guest user, fill in your order details on the Track Order page.'],
        ['q' => 'Is there any minimum order requirement?', 'a' => 'No, we don’t have any minimum order requirement. But we provide extra offers on bulk prepaid orders.'],
        ['q' => 'How do I cancel my order?', 'a' => 'You can cancel your order before it is shipped from our warehouse. Please write to us at customercare@faelynn.co.in for the same.'],
    ],
    'Returns' => [
        ['q' => 'How do I return the order I have placed?', 'a' => 'To initiate a return, please contact our customer service team at customercare@faelynn.co.in within the stipulated 10-day period. Provide your order number, the reason for the return, and any supporting documentation, such as photographs, if applicable.'],
        ['q' => 'If I return the order, will the GST amount be refunded?', 'a' => 'Yes. If any order is returned, the GST amount will be refunded.'],
    ],
    'General' => [
        ['q' => 'What is Faelynn?', 'a' => 'Faelynn is a premium clothing brand crafting luxury sleepwear, loungewear and everyday cotton wear. Faelynn was born out of a passion for redefining the way we experience comfort and style in our most intimate moments — clothing that not only complements the modern lifestyle but elevates it.'],
        ['q' => 'How do I get started at Faelynn?', 'a' => 'Joining Faelynn is free and anyone can register to get ₹100 off. This can be combined with any other offer. You can also browse and buy without logging in, using guest checkout.'],
        ['q' => 'Do you have promotions and offers?', 'a' => 'We run promotional offers from time to time. We currently offer a 5% discount on each product for first-time customers.'],
        ['q' => 'Can I unsubscribe from Faelynn’s mailing list?', 'a' => 'Yes, you can unsubscribe by clicking the ‘unsubscribe’ link in our newsletters or promotional emails. We will continue to email you regarding your orders or returns.'],
    ],
];

/** Policy pages (content in data/policies.json). key => source slug */
const FL_POLICIES = [
    'shipping'  => ['slug' => 'shipping-policy',      'label' => 'Shipping Policy'],
    'returns'   => ['slug' => 'return-refund-policy', 'label' => 'Returns & Refunds'],
    'wash-care' => ['slug' => 'wash-care',            'label' => 'Wash Care'],
    'privacy'   => ['slug' => 'privacy-policy',       'label' => 'Privacy Policy'],
    'terms'     => ['slug' => 'terms-and-conditions', 'label' => 'Terms & Conditions'],
];

const FL_JOURNAL = [
    [
        'slug' => 'night-suits-good-sleep',
        'title' => 'The Importance of Relaxing in Night Suits for a Good Night’s Sleep',
        'date' => '2024-02-18',
        'image' => 'assets/images/products/img-9817.jpg',
        'excerpt' => 'Slipping into comfortable nightwear is often an overlooked part of bedtime routines — yet what we wear to bed can significantly impact the quality of our sleep.',
        'tag' => 'Sleep',
    ],
];

/** Editorial cards shown alongside the journal (link to real pages). */
const FL_STORIES = [
    ['title' => 'Inside our workshop: where clothing is lived into being', 'tag' => 'Our Story', 'image' => 'assets/images/products/belinda6.jpg', 'url' => 'about.php', 'excerpt' => 'Colourful fabrics drying in the breeze, patterns swaying like poems written by hand.'],
    ['title' => 'Nurturing the elegance: your nightwear’s care ritual', 'tag' => 'Wash Care', 'image' => 'assets/images/products/img-9836.jpg', 'url' => 'policy.php?type=wash-care', 'excerpt' => 'A heartfelt guide to keeping your cotton soft, airy and beautiful for years.'],
    ['title' => 'Made to measure: dream it, we’ll craft it', 'tag' => 'Custom Order', 'image' => 'assets/images/products/fae4.jpg', 'url' => 'custom-order.php', 'excerpt' => 'Share your style, colours and measurements — we’ll make a piece that is yours alone.'],
];

const FL_POPULAR_SEARCHES = [
    'Women' => [
        ['Cotton Dresses for Women', 'shop.php?cat=dresses-women'],
        ['Hand Embroidered Dresses', 'shop.php?q=embroider'],
        ['Co-ord Sets for Women', 'shop.php?cat=co-ord-set-women'],
        ['Night Dresses', 'shop.php?cat=night-dress-women'],
        ['Nursing Night Dress', 'shop.php?q=nursing'],
        ['Floral Print Dresses', 'shop.php?q=floral'],
        ['Oversized Dress', 'shop.php?q=oversized'],
        ['Cotton Loungewear', 'shop.php?q=loungewear'],
    ],
    'Men' => [
        ['Cotton Shirts for Men', 'shop.php?cat=shirt-men'],
        ['Relaxed Fit Shirts', 'shop.php?q=relaxed'],
        ['Shirt & Trouser Sets', 'shop.php?cat=trouser-men'],
        ['Cotton Shorts for Men', 'shop.php?cat=shorts-men'],
        ['Summer Outfits for Men', 'shop.php?q=summer'],
    ],
    'Help' => [
        ['Size Guide', 'custom-order.php#size-chart'],
        ['Track Order', 'track-order.php'],
        ['Custom Orders', 'custom-order.php'],
        ['Wash Care', 'policy.php?type=wash-care'],
    ],
];

function fl_policies(): array
{
    static $data = null;
    if ($data === null) {
        $data = json_decode(file_get_contents(__DIR__ . '/../data/policies.json'), true, 512, JSON_THROW_ON_ERROR);
    }
    return $data;
}
