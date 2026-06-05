<?php

function admin_office_fields(): array
{
    return [
        'nome_escritorio' => 'Nome do escritorio',
        'whatsapp_raw' => 'WhatsApp somente numeros',
        'whatsapp_friendly' => 'WhatsApp para exibicao',
        'email_contato' => 'E-mail de contato',
        'oab_registro' => 'Registro OAB',
        'endereco_local' => 'Endereco / cidade',
    ];
}

function admin_seo_fields(): array
{
    return [
        'seo_site_url' => 'URL principal do site',
        'seo_home_title' => 'Titulo SEO da home',
        'seo_home_description' => 'Descricao SEO da home',
        'seo_home_keywords' => 'Palavras-chave',
        'seo_blog_title' => 'Titulo SEO do blog',
        'seo_blog_description' => 'Descricao SEO do blog',
        'seo_post_title_suffix' => 'Sufixo dos titulos dos posts',
        'seo_author' => 'Autor',
        'seo_robots' => 'Robots',
        'seo_og_title' => 'Titulo de compartilhamento',
        'seo_og_description' => 'Descricao de compartilhamento',
        'seo_twitter_card' => 'Twitter Card',
        'seo_locale' => 'Idioma Open Graph',
        'seo_schema_type' => 'Tipo de schema',
        'seo_area_served' => 'Area atendida',
        'seo_business_description' => 'Descricao estruturada do escritorio',
    ];
}

function admin_smtp_fields(): array
{
    return [
        'smtp_enabled' => 'Ativar envio SMTP',
        'smtp_host' => 'Servidor SMTP',
        'smtp_port' => 'Porta SMTP',
        'smtp_encryption' => 'Seguranca',
        'smtp_username' => 'Usuario SMTP',
        'smtp_password' => 'Senha SMTP',
        'smtp_from_email' => 'E-mail remetente',
        'smtp_from_name' => 'Nome do remetente',
        'smtp_to_email' => 'E-mail que recebe os formularios',
    ];
}

function admin_post_string(string $key, int $limit = 5000): string
{
    $value = $_POST[$key] ?? '';
    if (!is_scalar($value)) {
        return '';
    }

    return trim_limited((string) $value, $limit);
}

function admin_validate_http_url(string $value, string $label): string
{
    if ($value === '') {
        return '';
    }

    $url = filter_var($value, FILTER_VALIDATE_URL);
    $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));
    if (!$url || !in_array($scheme, ['http', 'https'], true)) {
        throw new RuntimeException($label . ' precisa ser uma URL http ou https valida.');
    }

    return $url;
}

function admin_validate_email_value(string $value, string $label): string
{
    if ($value === '') {
        return '';
    }

    $email = filter_var($value, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        throw new RuntimeException($label . ' precisa ser um e-mail valido.');
    }

    return $email;
}

function admin_validate_setting(string $key, string $value, array $settings): string
{
    switch ($key) {
        case 'whatsapp_raw':
            $value = only_numbers($value);
            if ($value !== '' && strlen($value) < 10) {
                throw new RuntimeException('Informe um WhatsApp com DDD.');
            }
            return trim_limited($value, 13);

        case 'email_contato':
        case 'smtp_from_email':
        case 'smtp_to_email':
            return admin_validate_email_value($value, $key);

        case 'seo_site_url':
            return admin_validate_http_url(rtrim($value, '/') . ($value !== '' ? '/' : ''), 'URL principal do site');

        case 'seo_robots':
            $allowed = ['index, follow', 'noindex, follow', 'noindex, nofollow'];
            return in_array($value, $allowed, true) ? $value : 'index, follow';

        case 'seo_twitter_card':
            return in_array($value, ['summary_large_image', 'summary'], true) ? $value : 'summary_large_image';

        case 'seo_locale':
            if ($value === '') {
                return 'pt_BR';
            }
            if (!preg_match('/^[a-z]{2}_[A-Z]{2}$/', $value)) {
                throw new RuntimeException('Idioma Open Graph deve seguir o formato pt_BR.');
            }
            return $value;

        case 'seo_schema_type':
            return in_array($value, ['LegalService', 'Attorney', 'Organization'], true) ? $value : 'LegalService';

        case 'smtp_enabled':
            return ($_POST['smtp_enabled'] ?? '0') === '1' ? '1' : '0';

        case 'smtp_port':
            $port = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 65535]]);
            if ($port === false) {
                throw new RuntimeException('Porta SMTP deve estar entre 1 e 65535.');
            }
            return (string) $port;

        case 'smtp_encryption':
            return in_array($value, ['tls', 'ssl', 'none'], true) ? $value : 'tls';

        case 'smtp_password':
            if ($value === '' && !empty($settings['smtp_password'])) {
                return (string) $settings['smtp_password'];
            }
            return trim_limited($value, 512);

        case 'smtp_host':
        case 'smtp_username':
        case 'smtp_from_name':
            return trim_limited($value, 255);
    }

    return trim_limited($value, 5000);
}

function admin_collect_settings(array $settings, array $fields): array
{
    $submittedSettings = $settings;

    foreach ($fields as $key => $label) {
        $submittedSettings[$key] = admin_validate_setting($key, admin_post_string($key), $settings);
    }

    return $submittedSettings;
}

function admin_save_settings(PDO $pdo, array $settings, array $fields): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO site_settings (setting_key, setting_value)
         VALUES (:setting_key, :setting_value)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );

    foreach ($settings as $key => $value) {
        if (!array_key_exists($key, $fields)) {
            continue;
        }

        $stmt->execute([
            'setting_key' => $key,
            'setting_value' => is_scalar($value) ? (string) $value : '',
        ]);
    }
}
