<?php

require_once __DIR__ . '/db.php';

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function only_numbers(string $value): string
{
    return preg_replace('/\D+/', '', $value) ?? '';
}

function render_phosphor_icons(): void
{
    echo '    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/light/style.css">' . "\n";
    echo '    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/fill/style.css">' . "\n";
}

function render_jost_weight_cap_styles(): void
{
    echo <<<'HTML'
    <style>
        body .font-bold,
        body .font-semibold,
        body strong,
        body b,
        body button,
        body label,
        body th,
        body:not(.font-sans) h1,
        body:not(.font-sans) h2,
        body:not(.font-sans) h3,
        body:not(.font-sans) h4,
        body .btn,
        body .btn-primary,
        body .btn-danger,
        body .brand-tag,
        body .custom-select-button,
        body .custom-select-option.is-selected,
        body select option:checked,
        body .status-toggle.is-published .status-label,
        body .admin-version {
            font-weight: 500;
        }
        body .font-serif.font-semibold { font-weight: 600; }
        body .font-serif.font-bold { font-weight: 700; }
        body .site-logo,
        body .site-logo *,
        body .brand-name,
        body .brand-name * { font-weight: 400; }
    </style>
HTML;
    echo "\n";
}

function ph_icon(string $name, string $classes = 'text-base leading-none shrink-0', string $attributes = 'aria-hidden="true"'): string
{
    $safeName = preg_replace('/[^a-z0-9-]/i', '', $name) ?? '';
    $class = trim('ph-light ph-' . $safeName . ' ' . $classes);
    $attrs = trim($attributes);

    return '<i class="' . e($class) . '"' . ($attrs !== '' ? ' ' . $attrs : '') . '></i>';
}

function get_site_settings(): array
{
    $defaults = [
        'whatsapp_raw' => '5574981405295',
        'whatsapp_friendly' => '(74) 98140-5295',
        'email_contato' => 'contato@gabrielapitaadvogados.com.br',
        'oab_registro' => 'OAB/BA 123.456',
        'nome_escritorio' => 'Gabriela Pita Advogados Associados',
        'endereco_local' => 'Senhor do Bonfim - BA',
        'google_reviews_url' => 'https://share.google/f0CHbeOnC5QMY2l4R',
        'hero_image_url' => 'image/foto-hero.png',
        'favicon_url' => '',
        'smtp_enabled' => '1',
        'smtp_host' => 'mail.gabrielapitaadvogados.com.br',
        'smtp_port' => '587',
        'smtp_encryption' => 'tls',
        'smtp_username' => 'contato@gabrielapitaadvogados.com.br',
        'smtp_password' => '',
        'smtp_from_email' => 'contato@gabrielapitaadvogados.com.br',
        'smtp_from_name' => 'Gabriela Pita Advogados Associados',
        'smtp_to_email' => 'contato@gabrielapitaadvogados.com.br',
        'seo_site_url' => 'https://trabalhista.gabrielapitaadvogados.com.br/',
        'seo_home_title' => 'Acidente de Trabalho e Doença Ocupacional | Gabriela Pita',
        'seo_home_description' => 'Orientação jurídica humanizada para trabalhadores que sofreram acidente de trabalho ou enfrentam doença física ou emocional relacionada ao trabalho.',
        'seo_home_keywords' => 'acidente de trabalho, doença ocupacional, burnout relacionado ao trabalho, LER DORT, CAT, estabilidade acidentária, auxílio-acidente, advogado trabalhista',
        'seo_author' => 'Gabriela Pita Advogados Associados',
        'seo_robots' => 'index, follow',
        'seo_og_title' => 'Acidente de Trabalho e Doença Ocupacional | Gabriela Pita',
        'seo_og_description' => 'Informação clara e atendimento humanizado para compreender a relação entre trabalho, saúde e possíveis direitos.',
        'seo_og_image' => 'image/foto-hero.png',
        'seo_twitter_card' => 'summary_large_image',
        'seo_locale' => 'pt_BR',
        'seo_schema_type' => 'LegalService',
        'seo_area_served' => 'Senhor do Bonfim, Bahia, Brasil',
        'seo_business_description' => 'Escritório de advocacia com atuação em acidente de trabalho e doença ocupacional, oferecendo atendimento humanizado e análise individual.',
    ];

    $pdo = db();
    if (!$pdo) {
        $defaults['whatsapp_raw'] = only_numbers($defaults['whatsapp_raw']);
        $defaults['whatsapp_link'] = 'https://wa.me/' . $defaults['whatsapp_raw'];
        return $defaults;
    }

    try {
        $rows = $pdo->query('SELECT setting_key, setting_value FROM site_settings')->fetchAll();
        foreach ($rows as $row) {
            if (array_key_exists($row['setting_key'], $defaults)) {
                $defaults[$row['setting_key']] = $row['setting_value'];
            }
        }
    } catch (Throwable $e) {
        return $defaults;
    }

    $defaults['whatsapp_raw'] = only_numbers($defaults['whatsapp_raw']);
    $defaults['whatsapp_link'] = 'https://wa.me/' . $defaults['whatsapp_raw'];

    return $defaults;
}

function site_base_url(array $settings): string
{
    $configured = trim($settings['seo_site_url'] ?? '');
    if ($configured !== '') {
        return rtrim($configured, '/');
    }

    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host === '' || !preg_match('/^[a-z0-9.-]+(?::[0-9]{1,5})?$/i', $host)) {
        return '';
    }

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $scheme . '://' . $host;
}

function absolute_site_url(array $settings, string $path = ''): string
{
    if (preg_match('/^https?:\/\//i', $path)) {
        return $path;
    }

    $base = site_base_url($settings);
    if ($base === '') {
        return $path;
    }

    return $base . '/' . ltrim($path, '/');
}

function render_seo_meta(array $settings, array $seo = []): void
{
    $title = $seo['title'] ?? $settings['seo_home_title'];
    $description = $seo['description'] ?? $settings['seo_home_description'];
    $keywords = $seo['keywords'] ?? $settings['seo_home_keywords'];
    $robots = $seo['robots'] ?? $settings['seo_robots'];
    $author = $seo['author'] ?? $settings['seo_author'];
    $type = $seo['type'] ?? 'website';
    $image = $seo['image'] ?? ($settings['seo_og_image'] ?: $settings['hero_image_url']);
    $canonical = absolute_site_url($settings, $seo['canonical'] ?? '');
    $url = $seo['url'] ?? $canonical;
    $ogTitle = $seo['og_title'] ?? ($settings['seo_og_title'] ?: $title);
    $ogDescription = $seo['og_description'] ?? ($settings['seo_og_description'] ?: $description);
    $twitterTitle = $seo['twitter_title'] ?? $ogTitle;
    $twitterDescription = $seo['twitter_description'] ?? $ogDescription;
    $twitterCard = $seo['twitter_card'] ?? $settings['seo_twitter_card'];
    $locale = $seo['locale'] ?? $settings['seo_locale'];

    echo '    <title>' . e($title) . "</title>\n";
    echo '    <meta name="description" content="' . e($description) . "\">\n";
    if (trim($keywords) !== '') {
        echo '    <meta name="keywords" content="' . e($keywords) . "\">\n";
    }
    echo '    <meta name="author" content="' . e($author) . "\">\n";
    echo '    <meta name="robots" content="' . e($robots) . "\">\n";
    if ($canonical !== '') {
        echo '    <link rel="canonical" href="' . e($canonical) . "\">\n";
    }
    echo '    <meta property="og:locale" content="' . e($locale) . "\">\n";
    echo '    <meta property="og:type" content="' . e($type) . "\">\n";
    echo '    <meta property="og:title" content="' . e($ogTitle) . "\">\n";
    echo '    <meta property="og:description" content="' . e($ogDescription) . "\">\n";
    if ($url !== '') {
        echo '    <meta property="og:url" content="' . e($url) . "\">\n";
    }
    echo '    <meta property="og:site_name" content="' . e($settings['nome_escritorio']) . "\">\n";
    if ($image !== '') {
        echo '    <meta property="og:image" content="' . e(absolute_site_url($settings, $image)) . "\">\n";
    }
    echo '    <meta name="twitter:card" content="' . e($twitterCard) . "\">\n";
    echo '    <meta name="twitter:title" content="' . e($twitterTitle) . "\">\n";
    echo '    <meta name="twitter:description" content="' . e($twitterDescription) . "\">\n";
    if ($image !== '') {
        echo '    <meta name="twitter:image" content="' . e(absolute_site_url($settings, $image)) . "\">\n";
    }
}

function render_favicon_links(array $settings): void
{
    $favicon = trim($settings['favicon_url'] ?? '');
    if ($favicon === '') {
        return;
    }

    $type = strtolower(pathinfo(parse_url($favicon, PHP_URL_PATH) ?: $favicon, PATHINFO_EXTENSION)) === 'png'
        ? 'image/png'
        : 'image/x-icon';

    echo '    <link rel="icon" type="' . e($type) . '" href="' . e(absolute_site_url($settings, $favicon)) . "\">\n";
}

function render_legal_schema(array $settings): void
{
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => $settings['seo_schema_type'] ?: 'LegalService',
        'name' => $settings['nome_escritorio'],
        'url' => site_base_url($settings),
        'image' => absolute_site_url($settings, $settings['seo_og_image'] ?: $settings['hero_image_url']),
        'description' => $settings['seo_business_description'],
        'email' => $settings['email_contato'],
        'telephone' => $settings['whatsapp_friendly'],
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $settings['endereco_local'],
            'addressCountry' => 'BR',
        ],
        'areaServed' => $settings['seo_area_served'],
        'sameAs' => array_values(array_filter([
            $settings['google_reviews_url'] ?? '',
        ])),
    ];

    echo '    <script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "</script>\n";
}
