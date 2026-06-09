<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$pdo = db();
?>
<?php include __DIR__ . '/_header.php'; ?>
<main class="wrap">
    <h1>Painel administrativo</h1>
    <?php if (!$pdo): ?>
        <div class="notice error">Banco não conectado. Confira os dados em <strong>config/database.php</strong> e importe o arquivo <strong>database.sql</strong> no phpMyAdmin.</div>
    <?php endif; ?>
    <div class="grid">
        <section class="card stack">
            <h2>Dados do site</h2>
            <p class="muted">Edite WhatsApp, OAB, nome do escritório, endereço e links principais.</p>
            <a class="btn btn-primary" href="settings.php">Editar dados</a>
        </section>
        <section class="card stack">
            <h2>SEO</h2>
            <p class="muted">Configure títulos, descrições, palavras-chave, compartilhamento e dados estruturados.</p>
            <a class="btn btn-primary" href="seo.php">Editar SEO</a>
        </section>
        <section class="card stack">
            <h2>E-mail</h2>
            <p class="muted">Configure SMTP, remetente, destinatário e teste a conexão do envio dos formulários.</p>
            <a class="btn btn-primary" href="email.php">Editar e-mail</a>
        </section>
        <section class="card stack">
            <h2>Ver landing page</h2>
            <p class="muted">Confira o conteúdo público e a experiência de atendimento da página.</p>
            <a class="btn btn-primary" href="../index.php" target="_blank" rel="noopener">Abrir landing page</a>
        </section>
    </div>
</main>
<?php include __DIR__ . '/_footer.php'; ?>
