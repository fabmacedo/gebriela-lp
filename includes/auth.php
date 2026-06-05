<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/site.php';
require_once __DIR__ . '/security.php';

function start_admin_session(): void
{
    start_secure_session('gabriela_admin');
}

function current_admin(): ?array
{
    start_admin_session();

    if (isset($_SESSION['admin_last_activity']) && time() - (int) $_SESSION['admin_last_activity'] > 1800) {
        logout_admin();
        return null;
    }

    if (isset($_SESSION['admin_user'])) {
        $_SESSION['admin_last_activity'] = time();
    }

    return $_SESSION['admin_user'] ?? null;
}

function require_admin(): void
{
    if (!current_admin()) {
        header('Location: login.php');
        exit;
    }
}

function attempt_login(string $username, string $password): bool
{
    $pdo = db();
    if (!$pdo) {
        return false;
    }

    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }

    start_admin_session();
    session_regenerate_id(true);
    $_SESSION['admin_user'] = [
        'id' => (int) $user['id'],
        'username' => $user['username'],
    ];
    $_SESSION['admin_last_activity'] = time();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    return true;
}

function logout_admin(): void
{
    start_admin_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => (bool) $params['secure'],
            'httponly' => (bool) $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();
}
