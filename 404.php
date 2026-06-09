<?php
require_once __DIR__ . '/includes/site.php';
require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/front-header.php';

http_response_code(404);
security_headers(false);

$settings = get_site_settings();

$whatsapp_friendly = $settings['whatsapp_friendly'];
$email_contato = $settings['email_contato'];
$oab_registro = $settings['oab_registro'];
$nome_escritorio = $settings['nome_escritorio'];
$whatsapp_link = $settings['whatsapp_link'];
$endereco_local = $settings['endereco_local'];

?>
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php render_seo_meta($settings, [
    'title' => 'Página não encontrada | ' . $nome_escritorio,
    'description' => 'A página solicitada não foi encontrada. Volte ao início ou entre em contato com Gabriela Pita Advogados Associados.',
    'robots' => 'noindex, follow',
    'canonical' => '404.php',
]); ?>
<?php render_favicon_links($settings); ?>
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
                        bordo: '#3F070A',
                        bordoDeep: '#260305',
                        wine: '#5A0707',
                        wineDark: '#2F130D',
                        wineDeep: '#360000',
                        sand: '#D7C2A8',
                        cream: '#F4EDE4',
                        paper: '#FFF8EF',
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
<?php render_front_header_styles(); ?>
        .soft-radius { border-radius: 10px; }
        main > section + section,
        main > article > section + section,
        main + footer {
            border-top: 1px solid #6B181D;
        }
        body.font-sans { font-size: 17px; line-height: 1.42; }
        body.font-sans p,
        body.font-sans a,
        body.font-sans button { line-height: 1.42; }
        body.font-sans .text-xs { font-size: .82rem; line-height: 1.34; }
        body.font-sans .text-sm { font-size: .96rem; line-height: 1.42; }
        body.font-sans .text-base { font-size: 1.07rem; line-height: 1.42; }
        .reveal { opacity: 0; translate: 0 24px; transition: opacity .8s ease, translate .8s ease; }
        .reveal.active { opacity: 1; translate: 0 0; }
    </style>
<?php render_jost_weight_cap_styles(); ?>
</head>
<body class="bg-bordo text-cream font-sans antialiased overflow-x-hidden">
<?php render_front_header($settings); ?>

    <main>
        <section class="relative min-h-screen overflow-hidden bg-bordo text-cream">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_82%_18%,rgba(255,248,239,.12),transparent_28%),linear-gradient(120deg,#3F070A_0%,#3F070A_60%,#5A0707_100%)]"></div>
            <div class="relative mx-auto grid min-h-screen max-w-7xl items-center gap-10 px-5 py-20 lg:grid-cols-[.85fr_1.15fr] lg:px-8">
                <div class="reveal">
                    <p class="soft-radius mb-6 inline-flex bg-cream/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-cream/70">Erro 404</p>
                    <h1 class="font-serif text-5xl font-semibold leading-[0.95] sm:text-6xl lg:text-7xl">Página não encontrada.</h1>
                    <p class="mt-6 max-w-xl text-base text-cream/75">
                        O endereço pode ter mudado, a página foi removida ou o link foi digitado incorretamente. Você pode voltar para o início ou falar com nossa equipe.
                    </p>
                    <div class="mt-8 flex flex-col items-stretch gap-3 sm:flex-row">
                        <a href="index.php#inicio" class="soft-radius inline-flex items-center justify-center border border-white bg-white px-7 py-4 text-xs font-bold uppercase tracking-[0.16em] text-wineDark transition hover:bg-paper">Voltar ao início</a>
                        <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" class="soft-radius inline-flex items-center justify-center gap-2 border border-white bg-white px-7 py-4 text-xs font-bold uppercase tracking-[0.16em] text-wineDark transition hover:bg-paper">Conversar sobre meu caso</a>
                    </div>
                </div>
                <div class="soft-radius border border-[#6B181D] bg-bordoDeep/35 p-8 reveal">
                    <p class="font-serif text-[9rem] leading-none text-cream/90 md:text-[13rem]">404</p>
                    <div class="mt-8 grid gap-4 border-t border-cream/15 pt-8 text-sm text-cream/70">
                        <a class="hover:text-cream" href="index.php#situacoes">Ver situações atendidas</a>
                        <a class="hover:text-cream" href="index.php#direitos">Entender o que pode ser analisado</a>
                        <a class="hover:text-cream" href="index.php#duvidas">Consultar dúvidas frequentes</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-bordoDeep py-12 text-cream">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 md:grid-cols-3 lg:px-8">
            <div class="reveal">
                <p class="site-logo-name">Gabriela Pita</p>
                <p class="site-logo-subtitle text-cream/55">Advogados Associados</p>
                <p class="mt-5 text-xs text-cream/60"><?php echo e($oab_registro); ?></p>
            </div>
            <nav class="grid gap-2 text-xs uppercase tracking-[0.16em] text-cream/60 reveal">
                <a href="index.php#situacoes" class="hover:text-cream">Situações atendidas</a>
                <a href="index.php#direitos" class="hover:text-cream">Entenda seus direitos</a>
                <a href="index.php#duvidas" class="hover:text-cream">Dúvidas</a>
            </nav>
            <div class="grid content-start gap-3 text-sm text-cream/70 reveal">
                <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 hover:text-cream">WhatsApp: <?php echo e($whatsapp_friendly); ?></a>
                <a href="mailto:<?php echo e($email_contato); ?>" class="hover:text-cream"><?php echo e($email_contato); ?></a>
                <p><?php echo e($endereco_local); ?></p>
            </div>
        </div>
        <div class="mx-auto mt-10 max-w-7xl px-5 text-xs text-cream/45 lg:px-8 reveal">© <?php echo date('Y'); ?> <?php echo e($nome_escritorio); ?>. Todos os direitos reservados.</div>
    </footer>

<?php render_front_header_script(); ?>
    <script>
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



