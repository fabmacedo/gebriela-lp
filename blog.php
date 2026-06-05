<?php
require_once __DIR__ . '/includes/site.php';
require_once __DIR__ . '/includes/security.php';

security_headers(false);

$settings = get_site_settings();
$posts = get_published_posts(1000);

$whatsapp_friendly = $settings['whatsapp_friendly'];
$email_contato = $settings['email_contato'];
$oab_registro = $settings['oab_registro'];
$nome_escritorio = $settings['nome_escritorio'];
$whatsapp_link = $settings['whatsapp_link'];
$endereco_local = $settings['endereco_local'];
$blogCanonical = 'blog.php';

?>
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php render_seo_meta($settings, [
    'title' => $settings['seo_blog_title'],
    'description' => $settings['seo_blog_description'],
    'canonical' => $blogCanonical,
    'og_title' => $settings['seo_blog_title'],
    'og_description' => $settings['seo_blog_description'],
]); ?>
<?php render_favicon_links($settings); ?>
<?php render_legal_schema($settings); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bellefair&family=Jost:wght@300;400;500&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<?php render_phosphor_icons(); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wine: '#5A0707',
                        wineDark: '#2F130D',
                        wineDeep: '#360000',
                        cream: '#F4EDE4',
                        paper: '#FFF8EF',
                        bordo: '#3F070A',
                        bordoDeep: '#260305',
                        sand: '#D7C2A8',
                        ink: '#2A1713',
                        muted: '#8B766D'
                    },
                    fontFamily: {
                        sans: ['Jost', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                        bellefair: ['Bellefair', 'serif']
                    }
                }
            }
        }
    </script>
    <style>
        .site-logo, .site-logo * { font-family: 'Bellefair', Georgia, serif !important; font-weight: 400; }
        .site-logo { line-height: 1; }
        .site-logo-name {
            display: block;
            margin: 0;
            font-family: 'Bellefair', Georgia, serif !important;
            font-size: 26px;
            letter-spacing: .035em;
            line-height: .9 !important;
            text-transform: uppercase;
        }
        .site-logo-subtitle {
            display: block;
            margin: 0;
            padding-top: 3px;
            font-family: 'Bellefair', Georgia, serif !important;
            font-size: 10px;
            letter-spacing: .18em;
            line-height: 1 !important;
            text-transform: uppercase;
        }
        .soft-radius { border-radius: 10px; }
        main > section + section,
        main > article > section + section,
        main + footer {
            border-top: 1px solid #6B181D;
        }
        .floating-header {
            border-radius: 10px;
            background: linear-gradient(90deg, rgba(47, 19, 13, .58), rgba(47, 19, 13, .34));
            box-shadow: 0 20px 62px rgba(18, 7, 5, .22);
        }
        @media (max-width: 767px) {
            .floating-header {
                background: rgba(47, 19, 13, .68);
            }
        }
        body.font-sans { font-size: 17px; line-height: 1.42; }
        body.font-sans p,
        body.font-sans li,
        body.font-sans a,
        body.font-sans button,
        body.font-sans input,
        body.font-sans textarea,
        body.font-sans select,
        body.font-sans label,
        body.font-sans summary { line-height: 1.42; }
        body.font-sans .text-xs { font-size: .82rem; line-height: 1.34; }
        body.font-sans .text-sm { font-size: .96rem; line-height: 1.42; }
        body.font-sans .text-base { font-size: 1.07rem; line-height: 1.42; }
        body.font-sans .leading-6,
        body.font-sans .leading-7,
        body.font-sans .leading-8 { line-height: 1.42; }
        .reveal { opacity: 0; translate: 0 24px; transition: opacity .8s ease, translate .8s ease; }
        .reveal.active { opacity: 1; translate: 0 0; }
    </style>
<?php render_jost_weight_cap_styles(); ?>
</head>
<body class="bg-bordo text-cream font-sans antialiased overflow-x-hidden">
    <header class="fixed inset-x-0 top-4 z-50 px-4 sm:top-6">
        <div class="floating-header mx-auto flex min-h-16 max-w-7xl items-center justify-between gap-6 border border-cream/15 px-5 py-3 backdrop-blur-xl lg:min-h-20 lg:px-8">
            <a href="index.php#inicio" class="site-logo text-cream" aria-label="<?php echo e($nome_escritorio); ?> Home">
                <span class="site-logo-name">Gabriela Pita</span>
                <span class="site-logo-subtitle">Advogados Associados</span>
            </a>
            <nav class="ml-auto hidden items-center gap-7 text-[11px] font-bold uppercase tracking-[0.18em] text-cream/80 lg:flex">
                <a class="transition hover:text-sand" href="index.php#sobre">Quem sou eu?</a>
                <a class="transition hover:text-sand" href="index.php#servicos">Serviços</a>
                <a class="transition hover:text-sand" href="index.php#diferenciais">Diferenciais</a>
                <a class="transition hover:text-sand" href="index.php#duvidas">Dúvidas</a>
                <a class="transition hover:text-sand" href="blog.php">Blog</a>
            </nav>
            <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" class="whatsapp-cta soft-radius hidden items-center gap-2 border border-sand bg-transparent px-5 py-3 text-[11px] font-bold uppercase tracking-[0.16em] text-sand transition hover:border-cream hover:text-cream sm:inline-flex">
                Falar com especialista
            </a>
            <button id="menu-btn" class="soft-radius grid h-10 w-10 place-items-center border border-cream/25 text-cream lg:hidden" aria-label="Abrir menu">
                <?php echo ph_icon('list', 'text-2xl leading-none'); ?>
            </button>
        </div>
        <div id="mobile-menu" class="mx-auto mt-3 hidden max-w-7xl rounded-[10px] border border-cream/15 bg-bordo/70 px-5 py-5 shadow-2xl backdrop-blur-xl lg:hidden">
            <nav class="grid gap-4 text-sm font-semibold text-cream">
                <a href="index.php#sobre">Quem sou eu?</a>
                <a href="index.php#servicos">Serviços</a>
                <a href="index.php#diferenciais">Diferenciais</a>
                <a href="index.php#duvidas">Dúvidas</a>
                <a href="blog.php">Blog</a>
                <a href="index.php#contato">Entre em contato</a>
                <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" class="whatsapp-cta soft-radius inline-flex items-center justify-center gap-2 border border-sand bg-transparent px-5 py-3 text-xs font-bold uppercase tracking-[0.16em] text-sand">Falar com especialista</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="bg-bordo pb-20 pt-36 text-cream md:pb-28 md:pt-44">
            <div class="mx-auto max-w-7xl px-5 text-center lg:px-8 reveal">
                <p class="soft-radius mb-5 inline-flex bg-cream/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-cream/70">Conteúdo jurídico</p>
                <h1 class="font-serif text-5xl leading-none md:text-7xl">Blog</h1>
                <p class="mx-auto mt-6 max-w-2xl text-sm leading-7 text-cream/70 md:text-base">
                    Artigos e orientações para trabalhadores, empresas e clientes que buscam informação clara antes de tomar decisões jurídicas.
                </p>
            </div>
        </section>

        <section class="bg-bordoDeep py-16 md:py-24">
            <div class="mx-auto max-w-7xl px-5 lg:px-8">
                <?php if ($posts): ?>
                    <div class="mb-8 flex flex-col gap-4 reveal md:flex-row md:items-center md:justify-between">
                        <label for="blog-search" class="sr-only">Buscar por título</label>
                        <div class="soft-radius flex w-full items-center gap-3 border border-[#6B181D] bg-bordo/35 px-4 py-3 text-cream md:max-w-md">
                            <?php echo ph_icon('magnifying-glass', 'text-xl leading-none text-sand'); ?>
                            <input id="blog-search" type="search" class="w-full bg-transparent text-sm text-cream outline-none placeholder:text-cream/42" placeholder="Buscar por título" autocomplete="off">
                        </div>
                        <p id="blog-result-count" class="text-xs font-bold uppercase tracking-[0.16em] text-sand/70"></p>
                    </div>
                    <div id="blog-grid" class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($posts as $post): ?>
                            <article class="soft-radius border border-[#6B181D] bg-bordo/35 p-7 text-cream transition hover:-translate-y-1 hover:border-[#8A252B] reveal" data-blog-card>
                                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-sand/70"><?php echo e(date('d-m-Y', strtotime($post['published_at']))); ?></p>
                                <h2 class="mt-5 font-serif text-3xl leading-tight text-cream" data-blog-title><?php echo e($post['title']); ?></h2>
                                <p class="mt-4 text-sm leading-7 text-cream/62"><?php echo e($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 170) . '...'); ?></p>
                                <a href="post.php?slug=<?php echo urlencode($post['slug']); ?>" class="mt-7 inline-flex text-xs font-bold uppercase tracking-[0.16em] text-sand">Ler artigo</a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    <div id="blog-empty" class="soft-radius mt-8 hidden border border-[#6B181D] bg-bordo/35 p-8 text-center text-sm text-cream/62 reveal">Nenhum conteúdo encontrado.</div>
                    <nav id="blog-pagination" class="mt-12 flex items-center justify-center gap-2 reveal" aria-label="Paginação do blog"></nav>
                <?php else: ?>
                    <div class="soft-radius border border-[#6B181D] bg-bordo/35 p-10 text-center text-cream reveal">
                        <p class="text-cream/62">Ainda não há posts publicados.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="bg-bordo py-12 text-cream">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 md:grid-cols-3 lg:px-8">
            <div class="reveal">
                <p class="site-logo-name">Gabriela Pita</p>
                <p class="site-logo-subtitle text-cream/55">Advogados Associados</p>
                <p class="mt-5 text-xs text-cream/60"><?php echo e($oab_registro); ?></p>
            </div>
            <nav class="grid gap-2 text-xs uppercase tracking-[0.16em] text-cream/60 reveal">
                <a href="index.php#sobre" class="hover:text-cream">Quem sou eu?</a>
                <a href="index.php#servicos" class="hover:text-cream">Serviços</a>
                <a href="index.php#duvidas" class="hover:text-cream">Dúvidas</a>
                <a href="index.php#diferenciais" class="hover:text-cream">Diferenciais</a>
                <a href="blog.php" class="hover:text-cream">Blog</a>
            </nav>
            <div class="grid content-start gap-3 text-sm text-cream/70 reveal">
                <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 hover:text-cream">WhatsApp: <?php echo e($whatsapp_friendly); ?></a>
                <a href="mailto:<?php echo e($email_contato); ?>" class="hover:text-cream"><?php echo e($email_contato); ?></a>
                <p><?php echo e($endereco_local); ?></p>
            </div>
        </div>
        <div class="mx-auto mt-10 max-w-7xl px-5 text-xs text-cream/45 lg:px-8 reveal">© <?php echo date('Y'); ?> <?php echo e($nome_escritorio); ?>. Todos os direitos reservados.</div>
    </footer>

    <script>
        document.getElementById('menu-btn')?.addEventListener('click', () => {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });

        const blogSearch = document.getElementById('blog-search');
        const blogGrid = document.getElementById('blog-grid');
        const blogPagination = document.getElementById('blog-pagination');
        const blogEmpty = document.getElementById('blog-empty');
        const blogResultCount = document.getElementById('blog-result-count');
        const blogPageSize = 12;
        let blogCurrentPage = 1;

        const normalizeBlogText = (value) => (value || '')
            .toLocaleLowerCase('pt-BR')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();

        const blogCards = blogGrid
            ? Array.prototype.slice.call(blogGrid.querySelectorAll('[data-blog-card]')).map((card) => ({
                card,
                title: normalizeBlogText(card.querySelector('[data-blog-title]')?.textContent || '')
            }))
            : [];

        const renderBlogPosts = () => {
            if (!blogGrid || !blogPagination) return;

            const term = normalizeBlogText(blogSearch?.value || '');
            const filtered = blogCards.filter((item) => item.title.includes(term));
            const totalPages = Math.max(1, Math.ceil(filtered.length / blogPageSize));
            blogCurrentPage = Math.min(blogCurrentPage, totalPages);
            const start = (blogCurrentPage - 1) * blogPageSize;
            const end = start + blogPageSize;

            blogCards.forEach(({ card }) => card.classList.add('hidden'));
            filtered.slice(start, end).forEach(({ card }) => {
                card.classList.remove('hidden');
                card.classList.add('active');
            });

            if (blogEmpty) {
                blogEmpty.classList.toggle('hidden', filtered.length > 0);
                blogEmpty.classList.add('active');
            }

            if (blogResultCount) {
                blogResultCount.textContent = `${filtered.length} ${filtered.length === 1 ? 'conteúdo' : 'conteúdos'}`;
            }

            blogPagination.innerHTML = '';
            blogPagination.classList.toggle('hidden', totalPages <= 1);

            for (let page = 1; page <= totalPages; page += 1) {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = String(page);
                button.className = page === blogCurrentPage
                    ? 'soft-radius grid h-10 w-10 place-items-center border border-[#8A252B] bg-bordo text-sm text-cream transition'
                    : 'soft-radius grid h-10 w-10 place-items-center border border-[#6B181D] text-sm text-sand transition hover:border-[#8A252B] hover:bg-bordo/35';
                button.setAttribute('aria-label', `Ir para página ${page}`);
                if (page === blogCurrentPage) {
                    button.setAttribute('aria-current', 'page');
                }
                button.addEventListener('click', () => {
                    blogCurrentPage = page;
                    renderBlogPosts();
                    blogGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
                blogPagination.appendChild(button);
            }
        };

        blogSearch?.addEventListener('input', () => {
            blogCurrentPage = 1;
            renderBlogPosts();
        });
        renderBlogPosts();

        const revealItems = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: .12 });
            revealItems.forEach((item) => observer.observe(item));
        } else {
            const revealOnScroll = () => {
                revealItems.forEach((item) => {
                    if (item.classList.contains('active')) return;
                    if (item.getBoundingClientRect().top < window.innerHeight * .88) {
                        item.classList.add('active');
                    }
                });
            };
            window.addEventListener('scroll', revealOnScroll, { passive: true });
            window.addEventListener('resize', revealOnScroll);
            window.addEventListener('load', revealOnScroll);
            window.addEventListener('hashchange', revealOnScroll);
            revealOnScroll();
            setTimeout(revealOnScroll, 150);
            setTimeout(revealOnScroll, 500);
        }
    </script>
</body>
</html>



