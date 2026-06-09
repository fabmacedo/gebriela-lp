<?php

function render_front_header_styles(): void
{
    echo <<<'HTML'
        .site-logo, .site-logo * { font-family: 'Bellefair', Georgia, serif !important; font-weight: 400; }
        .site-logo {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            line-height: 1;
        }
        .site-header-logo {
            display: block;
            width: 225px;
            height: auto;
            max-height: 62px;
            object-fit: contain;
            object-position: left center;
        }
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
        .floating-header {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 20px 62px rgba(18, 7, 5, .22), inset 0 1px 0 rgba(255, 255, 255, .48);
        }
        .floating-header::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 0;
            background:
                linear-gradient(90deg, #fff, #fff);
            background-color: #fff;
            -webkit-backdrop-filter: none;
            backdrop-filter: none;
        }
        .floating-header > * {
            position: relative;
            z-index: 1;
        }
        .floating-header nav a:hover {
            color: #3F070A;
        }
        .site-header {
            transition: transform .28s ease, opacity .22s ease;
            will-change: transform, opacity;
        }
        .site-header.is-hidden {
            opacity: 0;
            pointer-events: none;
            transform: translateY(calc(-100% - 32px));
        }
        @media (max-width: 767px) {
            .site-header-logo {
                width: 180px;
                max-height: 54px;
            }
            .floating-header {
                background: #fff;
            }
            .floating-header::before {
                background:
                    linear-gradient(90deg, #fff, #fff);
                background-color: #fff;
            }
        }
HTML;
    echo "\n";
}

function render_front_header(array $settings, bool $isHome = false): void
{
    $logoHref = $isHome ? '#inicio' : 'index.php#inicio';
    $sectionPrefix = $isHome ? '#' : 'index.php#';
    $whatsappLink = $settings['whatsapp_link'] ?? '';
    $officeName = $settings['nome_escritorio'] ?? 'Gabriela Pita Advogados Associados';
    ?>
    <header id="site-header" class="site-header fixed inset-x-0 top-4 z-50 px-4 sm:top-6">
        <div class="floating-header mx-auto flex min-h-16 max-w-7xl items-center justify-between gap-6 border border-bordo/15 px-5 py-3 lg:min-h-20 lg:px-8">
            <div class="flex items-center gap-3 sm:gap-4">
                <button id="menu-btn" class="soft-radius grid h-10 w-10 shrink-0 place-items-center border border-bordo/25 bg-white text-bordo lg:hidden" aria-label="Abrir menu">
                    <?php echo ph_icon('list', 'text-2xl leading-none'); ?>
                </button>
                <a href="<?php echo e($logoHref); ?>" class="site-logo text-wineDark" aria-label="<?php echo e($officeName); ?> Home">
                    <img src="image/logo-gabriela-pita.png" alt="" class="site-header-logo">
                </a>
            </div>
            <nav class="ml-auto hidden items-center gap-7 text-[11px] font-bold uppercase tracking-[0.18em] text-wineDark/80 lg:flex">
                <a class="transition hover:text-sand" href="<?php echo e($sectionPrefix); ?>situacoes">Situações atendidas</a>
                <a class="transition hover:text-sand" href="<?php echo e($sectionPrefix); ?>direitos">Entenda seus direitos</a>
                <a class="transition hover:text-sand" href="<?php echo e($sectionPrefix); ?>duvidas">Dúvidas</a>
            </nav>
            <a href="<?php echo e($whatsappLink); ?>" target="_blank" rel="noopener" class="whatsapp-cta soft-radius hidden items-center gap-2 border border-bordo bg-bordo px-5 py-3 text-[11px] font-bold uppercase tracking-[0.16em] text-cream transition hover:border-wineDark hover:bg-wineDark sm:inline-flex">
                Conversar sobre meu caso
            </a>
        </div>
        <div id="mobile-menu" class="mx-auto mt-3 hidden max-w-7xl rounded-[10px] border border-bordo/15 bg-white px-5 py-5 shadow-2xl lg:hidden">
            <nav class="grid gap-4 text-sm font-semibold text-wineDark">
                <a href="<?php echo e($sectionPrefix); ?>situacoes">Situações atendidas</a>
                <a href="<?php echo e($sectionPrefix); ?>direitos">Entenda seus direitos</a>
                <a href="<?php echo e($sectionPrefix); ?>duvidas">Dúvidas</a>
                <a href="<?php echo e($sectionPrefix); ?>contato">Entre em contato</a>
                <a href="<?php echo e($whatsappLink); ?>" target="_blank" rel="noopener" class="whatsapp-cta soft-radius inline-flex items-center justify-center gap-2 border border-bordo bg-bordo px-5 py-3 text-xs font-bold uppercase tracking-[0.16em] text-cream transition hover:border-wineDark hover:bg-wineDark">Conversar sobre meu caso</a>
            </nav>
        </div>
    </header>
    <?php
}

function render_front_header_script(): void
{
    echo <<<'HTML'
    <script>
        (() => {
            const root = document.documentElement;
            if (root.dataset.frontHeaderReady === '1') return;
            root.dataset.frontHeaderReady = '1';

            const siteHeader = document.getElementById('site-header');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuButton = document.getElementById('menu-btn');

            menuButton?.addEventListener('click', () => {
                mobileMenu?.classList.toggle('hidden');
            });

            if (!siteHeader) return;

            let lastScrollY = Math.max(window.scrollY, 0);
            let headerTicking = false;
            const topRevealLimit = 40;
            const downTolerance = 8;

            const updateHeaderVisibility = () => {
                const currentScrollY = Math.max(window.scrollY, 0);
                const scrollDelta = currentScrollY - lastScrollY;

                if (currentScrollY <= topRevealLimit) {
                    siteHeader.classList.remove('is-hidden');
                } else if (scrollDelta > downTolerance) {
                    siteHeader.classList.add('is-hidden');
                    mobileMenu?.classList.add('hidden');
                } else if (scrollDelta < 0) {
                    siteHeader.classList.remove('is-hidden');
                }

                lastScrollY = currentScrollY;
                headerTicking = false;
            };

            window.addEventListener('scroll', () => {
                if (headerTicking) return;
                headerTicking = true;
                window.requestAnimationFrame(updateHeaderVisibility);
            }, { passive: true });
        })();
    </script>
HTML;
    echo "\n";
}
