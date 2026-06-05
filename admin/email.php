<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/mailer.php';
require_once __DIR__ . '/_settings_helpers.php';
require_admin();

$pdo = db();
$saved = false;
$error = '';
$smtpTested = false;
$smtpTestOk = false;
$smtpTestMessage = '';
$settings = get_site_settings();
$smtpFields = admin_smtp_fields();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf();

    if (!$pdo) {
        $error = 'Banco não conectado. Confira config/database.php.';
    } else {
        try {
            $submittedSettings = admin_collect_settings($settings, $smtpFields);

            if (($_POST['form_action'] ?? 'save') === 'test_smtp') {
                $smtpTested = true;
                test_smtp_connection($submittedSettings);
                $smtpTestOk = true;
                $smtpTestMessage = 'Conexão SMTP testada com sucesso.';
                $settings = $submittedSettings;
            } else {
                admin_save_settings($pdo, $submittedSettings, $smtpFields);
                $saved = true;
                $settings = get_site_settings();
            }
        } catch (Throwable $e) {
            if (($_POST['form_action'] ?? 'save') === 'test_smtp') {
                $smtpTested = true;
                $smtpTestOk = false;
                $smtpTestMessage = 'Falha no teste SMTP: ' . $e->getMessage();
                if (isset($submittedSettings)) {
                    $settings = $submittedSettings;
                }
            } else {
                $error = 'Não foi possível salvar as configurações de e-mail.';
            }
        }
    }
}
?>
<?php include __DIR__ . '/_header.php'; ?>
<main class="wrap">
    <h1>E-mail do formulário</h1>
    <?php if ($saved): ?><div class="notice">Configurações de e-mail salvas com sucesso.</div><?php endif; ?>
    <?php if ($smtpTested): ?><div class="notice <?php echo $smtpTestOk ? '' : 'error'; ?>"><?php echo e($smtpTestMessage); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="notice error"><?php echo e($error); ?></div><?php endif; ?>

    <form class="stack" method="post">
        <?php echo csrf_field(); ?>
        <section class="card stack">
            <div>
                <h2>SMTP do formulário</h2>
                <p class="muted">Dados usados para enviar por e-mail as solicitações feitas no formulário do site.</p>
            </div>

            <div class="grid">
                <div>
                    <label for="smtp_enabled">Ativar envio SMTP</label>
                    <select id="smtp_enabled" name="smtp_enabled">
                        <option value="1" <?php echo ($settings['smtp_enabled'] ?? '0') === '1' ? 'selected' : ''; ?>>Ativado</option>
                        <option value="0" <?php echo ($settings['smtp_enabled'] ?? '0') !== '1' ? 'selected' : ''; ?>>Desativado</option>
                    </select>
                </div>
                <div>
                    <label for="smtp_host">Servidor SMTP</label>
                    <input id="smtp_host" name="smtp_host" type="text" value="<?php echo e($settings['smtp_host'] ?? ''); ?>" placeholder="smtp.seudominio.com.br">
                </div>
                <div>
                    <label for="smtp_port">Porta SMTP</label>
                    <input id="smtp_port" name="smtp_port" type="number" value="<?php echo e($settings['smtp_port'] ?? '587'); ?>" min="1" max="65535">
                </div>
                <div>
                    <label for="smtp_encryption">Segurança</label>
                    <select id="smtp_encryption" name="smtp_encryption">
                        <option value="tls" <?php echo ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : ''; ?>>TLS / STARTTLS</option>
                        <option value="ssl" <?php echo ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                        <option value="none" <?php echo ($settings['smtp_encryption'] ?? '') === 'none' ? 'selected' : ''; ?>>Nenhuma</option>
                    </select>
                </div>
                <div>
                    <label for="smtp_username">Usuário SMTP</label>
                    <input id="smtp_username" name="smtp_username" type="text" value="<?php echo e($settings['smtp_username'] ?? ''); ?>" autocomplete="username">
                </div>
                <div>
                    <label for="smtp_password">Senha SMTP</label>
                    <input id="smtp_password" name="smtp_password" type="password" value="" autocomplete="new-password" placeholder="Deixe em branco para manter a senha atual">
                </div>
                <div>
                    <label for="smtp_from_email">E-mail remetente</label>
                    <input id="smtp_from_email" name="smtp_from_email" type="email" value="<?php echo e($settings['smtp_from_email'] ?? ''); ?>" placeholder="site@seudominio.com.br">
                </div>
                <div>
                    <label for="smtp_from_name">Nome do remetente</label>
                    <input id="smtp_from_name" name="smtp_from_name" type="text" value="<?php echo e($settings['smtp_from_name'] ?? ''); ?>">
                </div>
                <div class="field-span-2">
                    <label for="smtp_to_email">E-mail que recebe os formulários</label>
                    <input id="smtp_to_email" name="smtp_to_email" type="email" value="<?php echo e($settings['smtp_to_email'] ?? ''); ?>" placeholder="contato@seudominio.com.br">
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit" name="form_action" value="save">Salvar e-mail</button>
                <button class="btn" type="submit" name="form_action" value="test_smtp">Testar conexão SMTP</button>
                <span class="muted" style="align-self:center;font-size:13px;">O teste verifica conexão, segurança e autenticação. Não salva os dados.</span>
            </div>
        </section>
    </form>
</main>
<?php include __DIR__ . '/_footer.php'; ?>
