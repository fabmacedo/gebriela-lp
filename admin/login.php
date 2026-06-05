<?php
require_once __DIR__ . '/../includes/auth.php';

if (current_admin()) {
    header('Location: index.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf();

    $username = trim_limited((string) ($_POST['username'] ?? ''), 80);
    $password = (string) ($_POST['password'] ?? '');
    $loginRateKey = rate_limit_key('admin-login:' . strtolower($username));

    if (is_rate_limited($loginRateKey, 5, 600)) {
        $error = 'Muitas tentativas de login. Aguarde alguns minutos e tente novamente.';
    } elseif (attempt_login($username, $password)) {
        clear_rate_limit($loginRateKey);
        header('Location: index.php');
        exit;
    } else {
        register_rate_limit_attempt($loginRateKey, 600);
        $error = 'Usuario ou senha invalidos. Verifique tambem se o banco foi configurado.';
    }
}
?>
<?php include __DIR__ . '/_header.php'; ?>
<main class="login">
    <form class="card stack" method="post">
        <?php echo csrf_field(); ?>
        <div>
            <h1>Entrar no admin</h1>
        </div>
        <?php if ($error): ?>
            <div class="notice error"><?php echo e($error); ?></div>
        <?php endif; ?>
        <div>
            <label for="username">Usuario</label>
            <input id="username" name="username" type="text" value="<?php echo e($username); ?>" required autofocus autocomplete="username">
        </div>
        <div>
            <label for="password">Senha</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
        </div>
        <button class="btn btn-primary" type="submit">Entrar</button>
    </form>
</main>
<?php include __DIR__ . '/_footer.php'; ?>
