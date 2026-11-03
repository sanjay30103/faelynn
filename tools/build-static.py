#!/usr/bin/env python3
"""
Build a static copy of the Faelynn PHP site (for GitHub Pages / any static host).

    python3 tools/build-static.py dist

Starts `php -S` with FL_STATIC=1, renders every page, rewrites internal
`*.php?...` links to static file names, and copies assets/.

    index.php                      -> index.html
    shop.php?cat=women&sort=new    -> shop-women.html?sort=new   (filters run in JS)
    product.php?slug=fae-dress     -> product-fae-dress.html
    policy.php?type=returns        -> policy-returns.html
    journal.php?post=<slug>        -> journal-<slug>.html
    about.php                      -> about.html
"""
import html
import json
import os
import re
import shutil
import socket
import subprocess
import sys
import time
import urllib.parse
import urllib.request

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
OUT = os.path.abspath(sys.argv[1] if len(sys.argv) > 1 else os.path.join(ROOT, 'dist'))


def free_port():
    with socket.socket() as s:
        s.bind(('127.0.0.1', 0))
        return s.getsockname()[1]


def static_name(name, query):
    """Map a PHP route + query (list of pairs) to (static file, remaining query pairs)."""
    q = list(query)

    def take(key):
        for i, (k, v) in enumerate(q):
            if k == key:
                q.pop(i)
                return v
        return None

    if name == 'index':
        return 'index.html', q
    if name == 'shop':
        cat = take('cat')
        return (f'shop-{cat}.html' if cat else 'shop.html'), q
    if name == 'product':
        slug = take('slug')
        return (f'product-{slug}.html' if slug else 'shop.html'), q
    if name == 'policy':
        t = take('type')
        return (f'policy-{t}.html' if t else 'policy-shipping.html'), q
    if name == 'journal':
        post = take('post')
        return (f'journal-{post}.html' if post else 'journal.html'), q
    return f'{name}.html', q


LINK_RE = re.compile(r'(?<![\w/.:-])([a-z0-9-]+)\.php((?:\?[^"\'\s<>#)\\]*)?)')


def rewrite(text):
    def repl(m):
        name, query = m.group(1), m.group(2) or ''
        amp = '&amp;' in query
        pairs = urllib.parse.parse_qsl(html.unescape(query[1:]), keep_blank_values=True) if query else []
        target, rest = static_name(name, pairs)
        if rest:
            qs = urllib.parse.urlencode(rest)
            target += '?' + (qs.replace('&', '&amp;') if amp else qs)
        return target
    return LINK_RE.sub(repl, text)


def main():
    products = json.load(open(os.path.join(ROOT, 'data', 'products.json')))
    cats = ['women', 'men', 'dresses-women', 'co-ord-set-women', 'night-dress-women', 'shirt-men', 'trouser-men', 'shorts-men']
    policies = ['shipping', 'returns', 'wash-care', 'privacy', 'terms']
    posts = ['night-suits-good-sleep']

    routes = [('index.php', 'index.html'), ('shop.php', 'shop.html')]
    routes += [(f'shop.php?cat={c}', f'shop-{c}.html') for c in cats]
    routes += [(f'product.php?slug={p["slug"]}', f'product-{p["slug"]}.html') for p in products]
    routes += [(f'policy.php?type={t}', f'policy-{t}.html') for t in policies]
    routes += [(f'journal.php?post={s}', f'journal-{s}.html') for s in posts]
    routes += [(f'{n}.php', f'{n}.html') for n in ['cart', 'checkout', 'wishlist', 'account', 'about', 'contact', 'faq', 'custom-order', 'journal', 'track-order']]
    routes += [('404.php', '404.html')]

    port = free_port()
    env = dict(os.environ, FL_STATIC='1')
    server = subprocess.Popen(['php', '-S', f'127.0.0.1:{port}', '-t', ROOT], cwd=ROOT, env=env,
                              stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
    try:
        for _ in range(50):
            try:
                urllib.request.urlopen(f'http://127.0.0.1:{port}/index.php', timeout=2)
                break
            except Exception:
                time.sleep(0.1)

        if os.path.isdir(OUT):
            shutil.rmtree(OUT)
        os.makedirs(OUT)
        for src, dest in routes:
            try:
                body = urllib.request.urlopen(f'http://127.0.0.1:{port}/{src}', timeout=30).read().decode('utf-8')
            except urllib.error.HTTPError as e:  # 404.php returns 404 on purpose
                body = e.read().decode('utf-8')
            if re.search(r'(Fatal error|Warning|Notice|Deprecated)</b>:', body):
                raise SystemExit(f'PHP error while rendering {src}')
            with open(os.path.join(OUT, dest), 'w', encoding='utf-8') as f:
                f.write(rewrite(body))
            print(f'  {src:45} -> {dest}')
    finally:
        server.terminate()

    shutil.copytree(os.path.join(ROOT, 'assets'), os.path.join(OUT, 'assets'),
                    ignore=shutil.ignore_patterns('input.css', '.DS_Store'))
    open(os.path.join(OUT, '.nojekyll'), 'w').close()

    leftovers = []
    for fn in os.listdir(OUT):
        if fn.endswith('.html'):
            # internal links only (external URLs such as facebook.com/profile.php are left alone)
            leftovers += [f'{fn}: {m}' for m in re.findall(r'(?<![\w/.:-])[\w-]+\.php\b', open(os.path.join(OUT, fn)).read())]
    if leftovers:
        raise SystemExit('Unrewritten .php links:\n' + '\n'.join(leftovers[:20]))
    print(f'\nStatic site written to {OUT} ({len(routes)} pages)')


if __name__ == '__main__':
    main()
