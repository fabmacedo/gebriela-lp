<?php
require_once __DIR__ . '/includes/site.php';
require_once __DIR__ . '/includes/security.php';

security_headers(false);
csrf_token();

$settings = get_site_settings();
$blog_posts = get_published_posts(3);

$settings['hero_image_url'] = 'image/foto-hero.png';
if (in_array(trim($settings['seo_og_image'] ?? ''), ['', 'image/foto1.png'], true)) {
    $settings['seo_og_image'] = 'image/foto-hero.png';
}

$whatsapp_raw       = $settings['whatsapp_raw'];
$whatsapp_friendly  = $settings['whatsapp_friendly'];
$email_contato      = $settings['email_contato'];
$oab_registro       = $settings['oab_registro'];
$nome_escritorio    = $settings['nome_escritorio'];
$endereco_local     = $settings['endereco_local'];
$google_reviews_url = $settings['google_reviews_url'];
$hero_image_url     = $settings['hero_image_url'];
$whatsapp_link      = $settings['whatsapp_link'];

?>
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php render_seo_meta($settings, ['canonical' => '']); ?>
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
                        bordoSoft: '#541014',
                        bordoPanel: '#4A0C10',
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
        @font-face {
            font-family: "Taken by Vultures";
            src: url("font/Taken%20by%20Vultures%20Demo.otf") format("opentype");
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
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
        .faq-content {
            display: grid;
            grid-template-rows: 0fr;
            opacity: 0;
            transition: grid-template-rows .32s ease, opacity .24s ease;
        }
        details[open] .faq-content {
            grid-template-rows: 1fr;
            opacity: 1;
        }
        .faq-content-inner { overflow: hidden; }
        .faq-icon { transition: transform .24s ease, color .24s ease; }
        details[open] .faq-icon { transform: rotate(180deg); }
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
        .hero-base {
            background:
                radial-gradient(circle at 88% 18%, rgba(215, 194, 168, .18), transparent 28%),
                linear-gradient(90deg, #3F070A 0%, #3F070A 56%, #5A0707 100%);
        }
        .hero-photo {
            object-position: center center;
        }
        .hero-overlay {
            background:
                linear-gradient(90deg, rgba(63, 7, 10, .82) 0%, rgba(63, 7, 10, .62) 28%, rgba(63, 7, 10, .18) 59%, rgba(63, 7, 10, .02) 100%),
                linear-gradient(0deg, rgba(18, 7, 5, .58) 0%, rgba(18, 7, 5, .06) 38%, rgba(18, 7, 5, .18) 100%);
        }
        .hero-copy h1,
        .hero-copy p,
        .hero-kicker { text-shadow: 0 14px 38px rgba(18, 7, 5, .42); }
        .whatsapp-cta,
        .whatsapp-cta *,
        .whatsapp-icon,
        .whatsapp-icon * {
            filter: none !important;
            -webkit-filter: none !important;
            text-shadow: none !important;
            box-shadow: none !important;
        }
        .hero-tag {
            border: 1px solid rgba(244, 237, 228, .34);
            background: rgba(244, 237, 228, .16);
            box-shadow: 0 12px 34px rgba(18, 7, 5, .24);
            backdrop-filter: blur(10px);
        }
        .about-signature {
            font-family: "Taken by Vultures", cursive;
            font-weight: 400;
            letter-spacing: .02em;
            line-height: .8;
        }
        @media (max-width: 767px) {
            .floating-header {
                background: rgba(47, 19, 13, .68);
            }
            .hero-photo {
                width: 100%;
                max-width: 100%;
                transform: none;
                object-fit: cover;
                object-position: 70% bottom;
            }
            .hero-overlay {
                background:
                    linear-gradient(180deg, rgba(63, 7, 10, .72) 0%, rgba(63, 7, 10, .38) 36%, rgba(63, 7, 10, .68) 100%),
                    linear-gradient(90deg, rgba(63, 7, 10, .72) 0%, rgba(63, 7, 10, .2) 100%);
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
            <a href="#inicio" class="site-logo text-cream">
                <span class="site-logo-name">Gabriela Pita</span>
                <span class="site-logo-subtitle">Advogados Associados</span>
            </a>
            <nav class="ml-auto hidden items-center gap-7 text-[11px] font-bold uppercase tracking-[0.18em] text-cream/80 lg:flex">
                <a class="transition hover:text-sand" href="#sobre">Quem sou eu?</a>
                <a class="transition hover:text-sand" href="#servicos">Serviços</a>
                <a class="transition hover:text-sand" href="#diferenciais">Diferenciais</a>
                <a class="transition hover:text-sand" href="#duvidas">Dúvidas</a>
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
                <a href="#sobre">Quem sou eu?</a>
                <a href="#servicos">Serviços</a>
                <a href="#diferenciais">Diferenciais</a>
                <a href="#duvidas">Dúvidas</a>
                <a href="blog.php">Blog</a>
                <a href="#contato">Entre em contato</a>
                <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" class="whatsapp-cta soft-radius inline-flex items-center justify-center gap-2 border border-sand bg-transparent px-5 py-3 text-xs font-bold uppercase tracking-[0.16em] text-sand">Falar com especialista</a>
            </nav>
        </div>
    </header>

    <main>
        <section id="inicio" class="hero-base relative min-h-[760px] overflow-hidden text-cream sm:min-h-screen">
            <img src="<?php echo e($hero_image_url); ?>" alt="" aria-hidden="true" class="hero-photo absolute inset-0 h-full w-full object-cover">
            <div class="hero-overlay absolute inset-0"></div>
            <div class="relative mx-auto flex min-h-[760px] max-w-7xl items-center px-5 pb-16 pt-32 sm:min-h-screen sm:pt-36 lg:px-8">
                <div class="hero-copy reveal max-w-[34rem] pt-12 text-left sm:pt-4 lg:pt-10">
                    <div class="hero-kicker soft-radius mb-6 inline-flex items-center bg-cream/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-sand">
                        Advocacia estratégica
                    </div>
                    <h1 class="font-serif text-[2.05rem] font-semibold leading-[1.04] sm:text-[2.5rem] lg:text-[2.75rem] xl:text-[2.95rem]">
                        Advocacia trabalhista<br>estratégica com atendimento<br>humanizado e atuação<br>especializada
                    </h1>
                    <p class="mt-6 max-w-[34rem] text-base leading-7 text-cream/85 lg:text-lg">
                        Defendemos os direitos dos trabalhadores e oferecemos soluções jurídicas seguras para empresas, com atuação técnica, transparente e eficiente.
                    </p>
                    <div class="mt-10 flex flex-row items-center gap-3">
                        <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" class="whatsapp-cta soft-radius inline-flex min-h-14 items-center justify-center gap-2 border border-sand bg-transparent px-7 py-4 text-xs font-bold uppercase tracking-[0.16em] text-sand transition hover:bg-sand hover:text-wineDark">
                            Falar com especialista
                        </a>
                        <a href="#sobre" aria-label="Ir para a próxima seção" class="soft-radius grid h-14 w-14 shrink-0 place-items-center border border-sand text-sand transition hover:bg-sand hover:text-wineDark">
                            <?php echo ph_icon('caret-down', 'text-xl leading-none'); ?>
                        </a>
                    </div>
                </div>
                <div class="hero-tag soft-radius absolute bottom-28 right-[38%] hidden px-4 py-2 text-[10px] font-bold uppercase tracking-[0.16em] text-cream lg:block">
                    Estratégia jurídica
                </div>
                <div class="hero-tag soft-radius absolute bottom-48 right-12 hidden px-4 py-2 text-[10px] font-bold uppercase tracking-[0.16em] text-cream lg:block">
                    Atendimento humanizado
                </div>
            </div>
        </section>

        <section id="sobre" class="bg-bordoDeep py-20 text-cream md:py-28">
            <div class="mx-auto grid max-w-7xl gap-14 px-5 lg:grid-cols-[minmax(0,1fr)_minmax(360px,.92fr)] lg:items-center lg:gap-16 lg:px-8">
                <div class="reveal">
                    <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.2em] text-sand">Quem sou eu?</p>
                    <h2 class="font-serif text-4xl leading-tight text-cream md:text-6xl">Gabriela Pita</h2>
                    <div class="mt-7 max-w-[42rem] space-y-5 text-sm leading-7 text-cream/72 md:text-[0.95rem]">
                        <p>O Gabriela Pita Advogados Associados nasce da continuidade de uma história construída com ética, competência e compromisso com as pessoas.</p>
                        <p>A trajetória do escritório teve início ainda no ambiente familiar, ao lado da advogada Eurídice Pita, referência profissional construída com seriedade e respeito ao longo dos anos. Com o tempo, a advocacia evoluiu, cresceu e passou a ocupar um espaço mais estruturado, dando origem ao Carvalho Melo e Carvalho Melo Advocacia Geral e, posteriormente, ao Carvalho Melo e Pita Advogados Associados.</p>
                        <p>Ao longo dessa caminhada, Gabriela Pita construiu sua própria identidade profissional, consolidando seu nome através da atuação jurídica, da liderança institucional e do compromisso com a advocacia. Sua trajetória inclui atuação na OAB, onde exerceu funções como Secretária-Geral, Vice-Presidente, Presidente da OAB - Subseção de Senhor do Bonfim e, atualmente, Conselheira Estadual da OAB/BA.</p>
                        <p>Com uma advocacia marcada pela responsabilidade, estratégia e proximidade com o cliente, surgiu o Gabriela Pita Advogados Associados: um escritório que une tradição, impacto, segurança jurídica e visão contemporânea da advocacia.</p>
                        <p>Hoje, o escritório atua de forma estratégica nas áreas trabalhista, cível e previdenciária, oferecendo atendimento humanizado, análise técnica aprofundada e atuação comprometida com resultados.</p>
                    </div>
                    <p class="about-signature mt-8 text-[3.2rem] text-sand sm:text-[4.4rem]">Gabriela Pita</p>
                </div>

                <div class="relative mx-auto w-full max-w-[30rem] pb-12 reveal lg:pb-10">
                    <div class="absolute left-0 top-1/2 hidden h-12 w-px -translate-y-1/2 bg-sand/20 sm:block"></div>
                    <div class="soft-radius absolute -right-3 top-12 h-[72%] w-20 bg-bordo sm:-right-6 lg:-right-8"></div>
                    <div class="soft-radius relative overflow-hidden bg-bordo shadow-2xl shadow-bordoDeep/35">
                        <img src="image/foto3.jpg" alt="Dra. Gabriela Pita" class="aspect-[4/5] w-full object-cover object-[50%_44%]">
                    </div>
                    <div class="soft-radius absolute -bottom-2 right-0 z-10 w-[58%] max-w-[19rem] overflow-hidden bg-bordo shadow-2xl shadow-bordoDeep/45 sm:-right-8 lg:-right-10">
                        <img src="image/foto2.webp" alt="Gabriela Pita em atendimento no escritório" class="aspect-[4/3] w-full object-cover object-center grayscale">
                    </div>
                    <div class="soft-radius absolute left-0 top-1/2 hidden h-12 w-12 -translate-x-1/2 -translate-y-1/2 place-items-center border border-sand/35 bg-cream/12 text-sand shadow-xl backdrop-blur sm:grid">
                        <?php echo ph_icon('scales', 'text-2xl leading-none'); ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="servicos" class="bg-bordo py-20 text-cream md:py-28">
            <div class="mx-auto max-w-7xl px-5 lg:px-8">
                <div class="mx-auto mb-12 max-w-3xl text-center reveal">
                    <h2 class="font-serif text-3xl leading-tight md:text-5xl">Serviços jurídicos.</h2>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <?php
                    $services = [
                        ['Rescisão Trabalhista', 'Verbas rescisórias, multas e irregularidades no encerramento do contrato.'],
                        ['Assédio Moral', 'Análise de abusos, humilhações e perseguições no ambiente profissional.'],
                        ['Acidente de Trabalho', 'Afastamentos, estabilidade, indenizações e responsabilidade do empregador.'],
                        ['Doenças Ocupacionais', 'Casos de LER/DORT, burnout e adoecimentos relacionados ao trabalho.'],
                        ['Horas Extras', 'Jornada, intervalos, banco de horas e pagamentos não realizados.'],
                        ['Justa Causa', 'Avaliação da proporcionalidade e possibilidade de reversão.'],
                        ['Atendimento para Empresas', 'Consultoria preventiva e defesa trabalhista estratégica.'],
                        ['Cível e Previdenciário', 'Orientação complementar para proteger relações e benefícios.'],
                    ];
                    foreach ($services as $service):
                    ?>
                        <article class="soft-radius min-h-52 border border-[#6B181D] bg-bordoDeep/35 p-6 text-cream transition hover:-translate-y-1 hover:border-[#8A252B] reveal">
                            <p class="mb-8 text-[10px] font-bold uppercase tracking-[0.18em] text-sand/70">Serviço</p>
                            <h3 class="font-serif text-2xl leading-tight text-cream"><?php echo e($service[0]); ?></h3>
                            <p class="mt-4 text-xs leading-6 text-cream/62"><?php echo e($service[1]); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="diferenciais" class="bg-bordoDeep py-20 text-cream md:py-28">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 lg:grid-cols-[.95fr_1.05fr] lg:items-center lg:gap-16 lg:px-8">
                <div class="relative mx-auto w-full max-w-[32rem] reveal">
                    <div class="soft-radius absolute -right-3 top-16 h-[68%] w-14 bg-sand sm:-right-5"></div>
                    <div class="soft-radius relative overflow-hidden bg-bordo shadow-2xl shadow-bordoDeep/35">
                        <img src="image/foto4.png" alt="Estátua da justiça" class="aspect-[4/5] w-full object-cover object-center">
                        <div class="absolute inset-0 bg-gradient-to-t from-bordo/55 via-bordoDeep/12 to-transparent"></div>
                    </div>
                    <div class="soft-radius absolute left-0 top-1/2 hidden h-12 w-12 -translate-x-1/2 -translate-y-1/2 place-items-center bg-cream/14 text-sand shadow-xl backdrop-blur sm:grid">
                        <?php echo ph_icon('scales', 'text-2xl leading-none'); ?>
                    </div>
                    <div class="soft-radius absolute bottom-6 right-4 w-[17.5rem] max-w-[calc(100%-2rem)] bg-bordo/90 px-5 py-4 text-cream shadow-xl backdrop-blur-md sm:right-6">
                        <p class="font-serif text-4xl leading-none text-cream">+400</p>
                        <p class="mt-2 text-sm leading-5 text-cream/82">clientes atendidos com cuidado, técnica e proximidade.</p>
                    </div>
                </div>

                <div class="reveal">
                    <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.2em] text-sand">Diferenciais</p>
                    <h2 class="max-w-xl font-serif text-4xl leading-tight text-cream md:text-6xl">Atendimento humanizado, técnico e transparente.</h2>
                    <div class="mt-8 grid gap-5 sm:grid-cols-2">
                    <?php
                    $differentials = [
                        ['Atendimento humanizado', 'Condução próxima, clara e respeitosa em cada etapa.'],
                        ['Transparência', 'Orientação objetiva sobre caminhos, riscos e próximos passos.'],
                        ['Análise técnica individualizada', 'Cada caso é estudado conforme documentos, contexto e objetivo.'],
                        ['Atendimento online e presencial', 'Flexibilidade para iniciar o atendimento com agilidade.'],
                    ];
                    foreach ($differentials as $index => $item):
                    ?>
                        <article class="soft-radius min-h-52 border border-[#6B181D] bg-bordo/35 p-7 text-cream transition hover:-translate-y-1 hover:border-[#8A252B]">
                            <p class="font-serif text-4xl leading-none text-cream"><?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?>.</p>
                            <h3 class="mt-10 font-serif text-2xl leading-tight text-cream"><?php echo e($item[0]); ?></h3>
                            <p class="mt-3 text-sm leading-7 text-cream/62"><?php echo e($item[1]); ?></p>
                        </article>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="contato" class="bg-bordo py-20 text-cream md:py-28">
            <div class="mx-auto grid max-w-7xl gap-10 px-5 lg:grid-cols-2 lg:px-8">
                <div class="self-center reveal">
                    <p class="mb-5 text-[11px] font-bold uppercase tracking-[0.2em] text-cream/55">Contato</p>
                    <h2 class="font-serif text-4xl leading-tight md:text-6xl">Fale conosco.</h2>
                    <p class="mt-6 max-w-xl text-base leading-8 text-cream/75">Conte brevemente o que você precisa e nossa equipe retornará para entender seu caso, orientar os próximos passos e indicar a melhor forma de atendimento.</p>
                    <div class="mt-10 grid max-w-md grid-cols-1 gap-5 text-sm text-cream/70 sm:grid-cols-3">
                        <div><strong class="block font-serif text-4xl text-cream">03</strong> áreas jurídicas</div>
                        <div><strong class="block font-serif text-4xl text-cream">15</strong> anos de mercado</div>
                        <div><strong class="block font-serif text-4xl text-cream">+400</strong> clientes atendidos</div>
                    </div>
                </div>
                <form id="lead-form" class="soft-radius border border-[#6B181D] bg-bordoDeep/35 p-7 text-cream shadow-2xl reveal">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="website" id="form-website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <h3 class="font-serif text-2xl text-cream">Solicitar Análise de Caso</h3>
                    <p class="mt-2 text-xs text-cream/62">Preencha o formulário para iniciarmos seu atendimento de forma rápida.</p>
                    <div class="mt-6 grid gap-4">
                        <div>
                            <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.16em] text-sand/70">Nome completo</label>
                            <input id="form-name" type="text" required class="soft-radius w-full border border-[#6B181D] bg-bordoDeep/55 px-4 py-3 text-sm text-cream outline-none placeholder:text-cream/42 focus:border-[#8A252B]" placeholder="Seu nome">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.16em] text-sand/70">WhatsApp</label>
                                <input id="form-phone" type="tel" required class="soft-radius w-full border border-[#6B181D] bg-bordoDeep/55 px-4 py-3 text-sm text-cream outline-none placeholder:text-cream/42 focus:border-[#8A252B]" placeholder="(00) 00000-0000">
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.16em] text-sand/70">Área de interesse</label>
                                <select id="form-area" class="soft-radius w-full border border-[#6B181D] bg-bordoDeep/55 px-4 py-3 text-sm text-cream outline-none focus:border-[#8A252B]">
                                    <option>Trabalhista (Trabalhador)</option>
                                    <option>Empresarial / Preventiva</option>
                                    <option>Cível / Família</option>
                                    <option>Previdenciário</option>
                                </select>
                            </div>
                        </div>
                        <div>
                                <label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.16em] text-sand/70">Mensagem / relato breve</label>
                                <textarea id="form-message" rows="5" class="soft-radius w-full border border-[#6B181D] bg-bordoDeep/55 px-4 py-3 text-sm text-cream outline-none placeholder:text-cream/42 focus:border-[#8A252B]" placeholder="Como podemos ajudar?"></textarea>
                            </div>
                        <button type="submit" class="soft-radius bg-wine px-6 py-4 text-xs font-bold uppercase tracking-[0.16em] text-cream transition hover:bg-wineDark">Enviar solicitação de atendimento</button>
                        <p id="form-status" class="hidden text-sm leading-relaxed"></p>
                    </div>
                </form>
            </div>
        </section>

        <section id="duvidas" class="bg-bordoDeep py-20 text-cream md:py-28">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 lg:grid-cols-[.82fr_1.18fr] lg:gap-20 lg:px-8">
                <aside class="reveal lg:sticky lg:top-28 lg:self-start">
                    <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.2em] text-sand">FAQ</p>
                    <h2 class="max-w-sm font-serif text-4xl leading-[1.05] text-cream md:text-6xl">Ficou com alguma dúvida?</h2>
                    <p class="mt-6 max-w-xs text-sm leading-7 text-cream/62">Confira as perguntas frequentes e tire suas dúvidas.</p>
                </aside>
                <div class="reveal">
                    <?php
                    $faqs = [
                        ['Preciso pagar para entrar com uma ação trabalhista?', 'Depende da situação econômica, dos pedidos e da estratégia do caso. A legislação trabalhista prevê regras sobre custas, honorários e justiça gratuita; por isso, antes de qualquer medida, avaliamos documentos, riscos e possibilidades de contratação para que a decisão seja tomada com clareza.'],
                        ['Posso agir mesmo após sair da empresa?', 'Sim. Em regra, créditos trabalhistas podem ser cobrados em até 2 anos após o fim do contrato, respeitado o limite de 5 anos para trás, conforme a Constituição Federal e a CLT. Como alguns direitos têm regras específicas, o ideal é analisar datas, documentos de rescisão e histórico do vínculo o quanto antes.'],
                        ['Trabalhei sem carteira assinada. Tenho direitos?', 'Pode ter, se estiverem presentes os requisitos da relação de emprego: trabalho por pessoa física, com pessoalidade, habitualidade, subordinação e pagamento. Nesses casos, é possível discutir reconhecimento de vínculo, anotação em carteira, FGTS, férias, 13º salário, horas extras e demais verbas cabíveis, conforme as provas.'],
                        ['Como saber se minha rescisão foi paga corretamente?', 'A conferência passa pelo TRCT, comprovantes de pagamento, extrato de FGTS, aviso-prévio, saldo de salário, férias vencidas e proporcionais com 1/3, 13º salário proporcional, multa do FGTS quando cabível e prazo legal de pagamento. Pequenas diferenças de cálculo podem gerar valores relevantes.'],
                        ['Assédio moral no trabalho pode gerar indenização?', 'Pode, quando há condutas abusivas, repetitivas ou graves que atinjam a dignidade, a saúde emocional ou a reputação do trabalhador. Mensagens, testemunhas, metas abusivas, humilhações públicas e registros médicos podem ajudar na análise, mas cada caso exige cuidado para identificar prova, dano e responsabilidade.'],
                        ['Acidente de trabalho garante estabilidade?', 'Em muitos casos, o acidente pode gerar estabilidade, indenização, emissão de CAT, afastamento previdenciário e outros direitos. A Lei nº 8.213/91 trata do acidente ocorrido pelo exercício do trabalho e da estabilidade de 12 meses em situações acidentárias, mas a conclusão depende do nexo com o trabalho, afastamento e documentação médica.'],
                        ['Doença ocupacional também pode ser considerada acidente de trabalho?', 'Sim. Doença profissional ou doença do trabalho pode ser equiparada a acidente quando houver relação com a atividade exercida ou com as condições do ambiente laboral. Casos de LER/DORT, problemas de coluna, perda auditiva, burnout e outras doenças exigem avaliação de laudos, exames, função desempenhada e histórico de exposição.'],
                        ['Tenho direito a horas extras?', 'A Constituição prevê adicional mínimo de 50% sobre a hora normal, e a CLT disciplina jornada, prorrogação, controle de ponto, intervalos e banco de horas. Para avaliar o direito, verificamos horários reais, registros, mensagens, escalas, intervalo intrajornada, trabalho em feriados e valores já pagos em holerite.'],
                        ['Posso reverter uma justa causa?', 'Pode ser possível quando a empresa não comprova falta grave, aplica punição desproporcional, demora para punir, ignora gradação de penalidades ou usa fundamento inadequado. A CLT lista hipóteses de justa causa, mas a validade depende de prova robusta e análise da conduta atribuída ao trabalhador.'],
                        ['Empresas podem contratar assessoria trabalhista?', 'Sim. A assessoria preventiva ajuda a reduzir riscos em contratos, jornada, banco de horas, férias, políticas internas, desligamentos, gestão de documentos e defesa em reclamações trabalhistas. A atuação considera CLT, Constituição, normas coletivas e a realidade operacional da empresa.'],
                    ];
                    foreach ($faqs as $index => $faq):
                    ?>
                        <details class="border-b border-cream/10 py-5 first:border-t" data-faq>
                            <summary class="grid cursor-pointer list-none grid-cols-[1fr_28px] items-center gap-5 outline-none">
                                <span class="font-serif text-xl leading-tight text-cream md:text-2xl">
                                    <span class="mr-1 text-base text-cream/80"><?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?>.</span>
                                    <?php echo e($faq[0]); ?>
                                </span>
                                <span class="faq-icon grid h-7 w-7 place-items-center self-center text-cream/85" aria-hidden="true"><?php echo ph_icon('caret-down', 'text-xl leading-none'); ?></span>
                            </summary>
                            <div class="faq-content">
                                <div class="faq-content-inner">
                                    <p class="max-w-2xl pt-4 text-sm leading-7 text-cream/62"><?php echo e($faq[1]); ?></p>
                                </div>
                            </div>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="blog" class="bg-bordo py-20 text-cream md:py-28">
            <div class="mx-auto max-w-7xl px-5 lg:px-8">
                <div class="mb-12 flex flex-col justify-between gap-6 md:flex-row md:items-end reveal">
                    <div>
                        <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.2em] text-sand">Blog</p>
                        <h2 class="font-serif text-4xl leading-tight text-cream md:text-5xl">Conteúdo jurídico.</h2>
                    </div>
                    <a href="blog.php" class="soft-radius inline-flex items-center justify-center border border-sand px-6 py-3 text-xs font-bold uppercase tracking-[0.16em] text-sand transition hover:bg-sand hover:text-wineDark">Ver todos</a>
                </div>
                <?php if ($blog_posts): ?>
                    <div class="grid gap-5 md:grid-cols-3">
                        <?php foreach ($blog_posts as $post): ?>
                            <article class="soft-radius border border-[#6B181D] bg-bordoDeep/35 p-6 transition hover:-translate-y-1 hover:border-[#8A252B] reveal">
                                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-sand/70"><?php echo e(date('d-m-Y', strtotime($post['published_at']))); ?></p>
                                <h3 class="mt-5 font-serif text-2xl leading-tight text-cream"><?php echo e($post['title']); ?></h3>
                                <p class="mt-4 text-sm leading-6 text-cream/62"><?php echo e($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 150) . '...'); ?></p>
                                <a href="post.php?slug=<?php echo urlencode($post['slug']); ?>" class="mt-6 inline-flex text-xs font-bold uppercase tracking-[0.16em] text-sand">Ler artigo</a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
                <a href="#sobre" class="hover:text-cream">Quem sou eu?</a>
                <a href="#servicos" class="hover:text-cream">Serviços</a>
                <a href="#diferenciais" class="hover:text-cream">Diferenciais</a>
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

        document.querySelectorAll('[data-faq]').forEach((item) => {
            item.addEventListener('toggle', () => {
                if (!item.open) return;
                document.querySelectorAll('[data-faq]').forEach((other) => {
                    if (other !== item) other.open = false;
                });
            });
        });

        const phoneInput = document.getElementById('form-phone');
        phoneInput?.addEventListener('input', (event) => {
            let value = event.target.value.replace(/\D/g, '').slice(0, 11);
            if (value.length > 2) value = value.replace(/^(\d{2})(\d)/, '($1) $2');
            if (value.length > 7) value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            event.target.value = value;
        });

        const leadForm = document.getElementById('lead-form');
        const formStatus = document.getElementById('form-status');
        leadForm?.addEventListener('submit', async (event) => {
            event.preventDefault();
            const button = leadForm.querySelector('button[type="submit"]');
            const payload = new FormData();
            payload.append('name', document.getElementById('form-name').value);
            payload.append('phone', document.getElementById('form-phone').value);
            payload.append('area', document.getElementById('form-area').value);
            payload.append('message', document.getElementById('form-message').value);
            payload.append('website', document.getElementById('form-website').value);
            payload.append('csrf_token', leadForm.querySelector('input[name="csrf_token"]')?.value || '');

            button.disabled = true;
            button.textContent = 'Enviando...';
            formStatus.className = 'text-sm text-cream/62';
            formStatus.textContent = 'Enviando sua solicitação.';

            try {
                const response = await fetch('contact-submit.php', { method: 'POST', body: payload });
                const result = await response.json();
                if (!response.ok || !result.ok) throw new Error(result.message || 'Erro ao enviar.');
                formStatus.className = 'text-sm text-wine';
                formStatus.textContent = result.message;
                leadForm.reset();
            } catch (error) {
                formStatus.className = 'text-sm text-red-700';
                formStatus.textContent = error.message || 'Não foi possível enviar agora.';
            } finally {
                button.disabled = false;
                button.textContent = 'Enviar solicitação de atendimento';
            }
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


