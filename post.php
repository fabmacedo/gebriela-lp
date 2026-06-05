<?php
require_once __DIR__ . '/includes/site.php';
require_once __DIR__ . '/includes/security.php';

security_headers(false);

$settings = get_site_settings();
$slug = trim_limited((string) ($_GET['slug'] ?? ''), 190);
if ($slug !== '' && !preg_match('/^[a-z0-9-]+$/', $slug)) {
    $slug = '';
}
$post = null;
$pdo = db();

$whatsapp_friendly = $settings['whatsapp_friendly'];
$email_contato = $settings['email_contato'];
$oab_registro = $settings['oab_registro'];
$nome_escritorio = $settings['nome_escritorio'];
$whatsapp_link = $settings['whatsapp_link'];
$endereco_local = $settings['endereco_local'];


if ($pdo && $slug !== '') {
    try {
        $stmt = $pdo->prepare(
            'SELECT title, slug, excerpt, content, published_at
             FROM blog_posts
             WHERE slug = :slug AND status = "published"
             LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        $post = $stmt->fetch();
    } catch (Throwable $e) {
        $post = null;
    }
}

if (!$post) {
    http_response_code(404);
}

$postTitle = $post ? $post['title'] . ' | ' . $settings['seo_post_title_suffix'] : 'Artigo não encontrado | ' . $settings['seo_post_title_suffix'];
$postDescription = $post ? ($post['excerpt'] ?: $settings['seo_blog_description']) : 'Artigo não encontrado.';
$postCanonical = $post ? 'post.php?slug=' . $post['slug'] : 'blog.php';
?>
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php render_seo_meta($settings, [
    'title' => $postTitle,
    'description' => $postDescription,
    'canonical' => $postCanonical,
    'type' => $post ? 'article' : 'website',
    'robots' => $post ? $settings['seo_robots'] : 'noindex, follow',
    'og_title' => $post ? $post['title'] : 'Artigo não encontrado',
    'og_description' => $postDescription,
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
        <?php if ($post): ?>
            <article>
                <section class="bg-bordoDeep pb-16 pt-36 text-cream md:pb-24 md:pt-44">
                    <div class="mx-auto max-w-7xl px-5 lg:px-8 reveal">
                        <button type="button" onclick="if (history.length > 1) { history.back(); } else { window.location.href = 'blog.php'; }" class="mb-8 text-xs font-bold uppercase tracking-[0.16em] text-cream/70 transition hover:text-cream">
                            Voltar
                        </button>
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-cream/60"><?php echo e(date('d-m-Y', strtotime($post['published_at']))); ?></p>
                        <h1 class="mt-5 max-w-5xl font-serif text-4xl leading-tight md:text-6xl"><?php echo e($post['title']); ?></h1>
                        <?php if ($post['excerpt']): ?>
                            <p class="mt-6 max-w-3xl text-sm leading-7 text-cream/70 md:text-base"><?php echo e($post['excerpt']); ?></p>
                        <?php endif; ?>
                    </div>
                </section>
                <section class="bg-bordo py-16 text-cream md:py-24">
                    <div class="mx-auto grid max-w-7xl gap-10 px-5 lg:grid-cols-[minmax(0,760px)_minmax(220px,1fr)] lg:px-8 reveal">
                        <div>
                            <div class="whitespace-pre-line font-serif text-2xl leading-10 text-cream md:text-3xl"><?php echo e($post['content']); ?></div>
                            <button type="button" onclick="if (history.length > 1) { history.back(); } else { window.location.href = 'blog.php'; }" class="soft-radius mt-12 inline-flex border border-sand px-6 py-3 text-xs font-bold uppercase tracking-[0.16em] text-sand transition hover:bg-sand hover:text-wineDark">Voltar</button>
                        </div>
                        <aside class="soft-radius h-fit border border-[#6B181D] bg-bordoDeep/35 p-7 text-cream lg:sticky lg:top-24">
                            <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-sand/70">Conteúdo jurídico</p>
                            <p class="mt-5 font-serif text-2xl leading-tight text-cream">Precisa de uma análise individual?</p>
                            <p class="mt-4 text-sm leading-7 text-cream/62">Cada caso exige avaliação dos documentos, prazos e provas disponíveis.</p>
                            <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" class="soft-radius mt-6 inline-flex items-center justify-center gap-2 border border-sand px-5 py-3 text-xs font-bold uppercase tracking-[0.16em] text-sand transition hover:bg-sand hover:text-wineDark">Falar com especialista</a>
                        </aside>
                    </div>
                </section>
            </article>
        <?php else: ?>
            <section class="bg-bordo py-28 text-cream">
                <div class="mx-auto max-w-3xl px-5 text-center lg:px-8 reveal">
                    <h1 class="font-serif text-4xl text-cream">Artigo não encontrado</h1>
                    <p class="mt-4 text-cream/70">O conteúdo pode ter sido removido ou ainda não está publicado.</p>
                    <button type="button" onclick="if (history.length > 1) { history.back(); } else { window.location.href = 'blog.php'; }" class="soft-radius mt-8 inline-flex border border-sand px-6 py-3 text-xs font-bold uppercase tracking-[0.16em] text-sand transition hover:bg-sand hover:text-wineDark">Voltar</button>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <footer class="bg-bordoDeep py-12 text-cream">
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



