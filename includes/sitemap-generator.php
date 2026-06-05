<?php

require_once __DIR__ . '/site.php';

function sitemap_xml_escape(string $value): string
{
    return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

function build_sitemap_xml(): string
{
    $settings = get_site_settings();
    $baseUrl = site_base_url($settings) ?: 'https://gabrielapitaadv.com.br';
    $today = date('Y-m-d');
    $urls = [
        [
            'loc' => $baseUrl . '/',
            'lastmod' => $today,
            'changefreq' => 'weekly',
            'priority' => '1.0',
        ],
        [
            'loc' => $baseUrl . '/blog.php',
            'lastmod' => $today,
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ],
    ];

    $pdo = db();
    if ($pdo) {
        try {
            $stmt = $pdo->query(
                'SELECT slug, DATE(COALESCE(published_at, updated_at, created_at)) AS lastmod
                 FROM blog_posts
                 WHERE status = "published"
                 ORDER BY published_at DESC, created_at DESC'
            );

            foreach ($stmt->fetchAll() as $post) {
                $urls[] = [
                    'loc' => $baseUrl . '/post.php?slug=' . rawurlencode($post['slug']),
                    'lastmod' => $post['lastmod'] ?: $today,
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                ];
            }
        } catch (Throwable $e) {
            // Mantém o sitemap básico disponível mesmo se o banco falhar.
        }
    }

    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    foreach ($urls as $url) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . sitemap_xml_escape($url['loc']) . "</loc>\n";
        $xml .= "    <lastmod>" . sitemap_xml_escape($url['lastmod']) . "</lastmod>\n";
        $xml .= "    <changefreq>" . sitemap_xml_escape($url['changefreq']) . "</changefreq>\n";
        $xml .= "    <priority>" . sitemap_xml_escape($url['priority']) . "</priority>\n";
        $xml .= "  </url>\n";
    }

    $xml .= "</urlset>\n";

    return $xml;
}

function write_sitemap_xml(): bool
{
    return @file_put_contents(__DIR__ . '/../sitemap.xml', build_sitemap_xml(), LOCK_EX) !== false;
}
