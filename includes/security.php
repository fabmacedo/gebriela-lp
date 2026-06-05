<?php

function is_https_request(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function security_headers(bool $admin = false): void
{
    if (headers_sent()) {
        return;
    }

    header_remove('X-Powered-By');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

    if ($admin) {
        header('X-Robots-Tag: noindex, nofollow', false);
        header("Content-Security-Policy: default-src 'self'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; script-src 'self' 'unsafe-inline'; base-uri 'self'; frame-ancestors 'self'; form-action 'self'");
    }
}

function start_secure_session(string $name = 'gabriela_site'): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    if (session_status() === PHP_SESSION_DISABLED) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_name($name);
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => is_https_request(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function csrf_token(): string
{
    start_secure_session();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf_token(): bool
{
    start_secure_session();
    $token = $_POST['csrf_token'] ?? '';

    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function require_valid_csrf(): void
{
    if (!verify_csrf_token()) {
        http_response_code(403);
        exit('Requisição inválida.');
    }
}

function rate_limit_key(string $scope): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    return $scope . ':' . hash('sha256', $ip . '|' . $agent);
}

function rate_limit_attempts(string $key, int $windowSeconds): array
{
    start_secure_session();

    $now = time();
    $attempts = $_SESSION['rate_limits'][$key] ?? [];
    if (!is_array($attempts)) {
        $attempts = [];
    }

    $attempts = array_values(array_filter($attempts, static function ($timestamp) use ($now, $windowSeconds): bool {
        return is_int($timestamp) && ($now - $timestamp) < $windowSeconds;
    }));

    $_SESSION['rate_limits'][$key] = $attempts;

    return $attempts;
}

function is_rate_limited(string $key, int $maxAttempts, int $windowSeconds): bool
{
    return count(rate_limit_attempts($key, $windowSeconds)) >= $maxAttempts;
}

function register_rate_limit_attempt(string $key, int $windowSeconds): void
{
    $attempts = rate_limit_attempts($key, $windowSeconds);
    $attempts[] = time();
    $_SESSION['rate_limits'][$key] = $attempts;
}

function clear_rate_limit(string $key): void
{
    start_secure_session();
    unset($_SESSION['rate_limits'][$key]);
}

function trim_limited(string $value, int $limit): string
{
    $value = trim($value);
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $limit, 'UTF-8');
    }

    return substr($value, 0, $limit);
}
