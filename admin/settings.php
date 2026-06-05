<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/_settings_helpers.php';
require_admin();

$pdo = db();
$saved = false;
$error = '';
$settings = get_site_settings();
$officeFields = admin_office_fields();

function upload_error_message(string $label): string
{
    return 'Não foi possível receber o arquivo de ' . $label . '.';
}

function extension_from_upload(string $field): string
{
    $originalName = strtolower((string) ($_FILES[$field]['name'] ?? ''));
    return strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
}

function tmp_name_from_upload(string $field, string $label): string
{
    $tmpName = (string) ($_FILES[$field]['tmp_name'] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        throw new RuntimeException(upload_error_message($label));
    }

    return $tmpName;
}

function mime_from_upload(string $tmpName): string
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if (!$finfo) {
        return '';
    }

    $mime = finfo_file($finfo, $tmpName) ?: '';
    finfo_close($finfo);

    return strtolower($mime);
}

function is_valid_ico_file(string $tmpName): bool
{
    $handle = fopen($tmpName, 'rb');
    if (!$handle) {
        return false;
    }

    $signature = fread($handle, 4);
    fclose($handle);

    return $signature === "\x00\x00\x01\x00" || $signature === "\x00\x00\x02\x00";
}

function ensure_image_directory(): string
{
    $imageDir = __DIR__ . '/../image';
    if (!is_dir($imageDir)) {
        mkdir($imageDir, 0755, true);
    }

    return $imageDir;
}

function handle_favicon_upload(): ?string
{
    if (empty($_FILES['favicon_file']) || ($_FILES['favicon_file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES['favicon_file']['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException(upload_error_message('favicon'));
    }

    if ((int) $_FILES['favicon_file']['size'] > 1024 * 1024) {
        throw new RuntimeException('Envie um favicon com até 1 MB.');
    }

    $extension = extension_from_upload('favicon_file');
    if (!in_array($extension, ['ico', 'png'], true)) {
        throw new RuntimeException('O favicon precisa ser um arquivo ICO ou PNG.');
    }

    $tmpName = tmp_name_from_upload('favicon_file', 'favicon');
    $mime = mime_from_upload($tmpName);
    if ($extension === 'png') {
        $imageInfo = @getimagesize($tmpName);
        if (!$imageInfo || (int) $imageInfo[2] !== IMAGETYPE_PNG || $mime !== 'image/png') {
            throw new RuntimeException('O arquivo PNG enviado não é válido.');
        }
    } elseif (!is_valid_ico_file($tmpName) || ($mime !== '' && !in_array($mime, ['image/x-icon', 'image/vnd.microsoft.icon', 'image/ico', 'application/octet-stream'], true))) {
        throw new RuntimeException('O arquivo ICO enviado nao e valido.');
    }

    $imageDir = ensure_image_directory();
    $targetPath = $imageDir . '/favicon.' . $extension;
    if (!move_uploaded_file($tmpName, $targetPath)) {
        throw new RuntimeException('Não foi possível salvar o favicon.');
    }

    @chmod($targetPath, 0644);

    $oldExtension = $extension === 'png' ? 'ico' : 'png';
    $oldPath = $imageDir . '/favicon.' . $oldExtension;
    if (is_file($oldPath)) {
        @unlink($oldPath);
    }

    return 'image/favicon.' . $extension;
}

function handle_share_image_upload(): ?string
{
    if (empty($_FILES['share_image_file']) || ($_FILES['share_image_file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES['share_image_file']['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException(upload_error_message('imagem de compartilhamento'));
    }

    if ((int) $_FILES['share_image_file']['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Envie uma imagem de compartilhamento com até 2 MB.');
    }

    $extension = extension_from_upload('share_image_file');
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($extension, $allowedExtensions, true)) {
        throw new RuntimeException('A imagem de compartilhamento precisa ser JPG, PNG ou WebP.');
    }

    $tmpName = tmp_name_from_upload('share_image_file', 'imagem de compartilhamento');
    $mime = mime_from_upload($tmpName);
    $imageInfo = @getimagesize($tmpName);
    if (!$imageInfo) {
        throw new RuntimeException('A imagem de compartilhamento enviada não é válida.');
    }

    [$width, $height, $type] = $imageInfo;
    $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
    if (defined('IMAGETYPE_WEBP')) {
        $allowedTypes[] = IMAGETYPE_WEBP;
    }
    if (!in_array((int) $type, $allowedTypes, true)) {
        throw new RuntimeException('A imagem de compartilhamento precisa ser JPG, PNG ou WebP.');
    }

    $expectedMimes = [
        IMAGETYPE_JPEG => ['image/jpeg'],
        IMAGETYPE_PNG => ['image/png'],
    ];
    if (defined('IMAGETYPE_WEBP')) {
        $expectedMimes[IMAGETYPE_WEBP] = ['image/webp'];
    }
    if ($mime !== '' && !in_array($mime, $expectedMimes[(int) $type] ?? [], true)) {
        throw new RuntimeException('O tipo real da imagem nao confere com o arquivo enviado.');
    }

    if ($width < 600 || $height < 315) {
        throw new RuntimeException('Use uma imagem de compartilhamento com pelo menos 600x315 px. O ideal é 1200x630 px.');
    }

    $extension = $extension === 'jpeg' ? 'jpg' : $extension;
    $imageDir = ensure_image_directory();
    $targetPath = $imageDir . '/social-share.' . $extension;
    if (!move_uploaded_file($tmpName, $targetPath)) {
        throw new RuntimeException('Não foi possível salvar a imagem de compartilhamento.');
    }

    @chmod($targetPath, 0644);

    foreach (['jpg', 'png', 'webp'] as $oldExtension) {
        $oldPath = $imageDir . '/social-share.' . $oldExtension;
        if ($oldExtension !== $extension && is_file($oldPath)) {
            @unlink($oldPath);
        }
    }

    return 'image/social-share.' . $extension;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf();

    if (!$pdo) {
        $error = 'Banco não conectado. Confira config/database.php.';
    } else {
        try {
            $submittedSettings = admin_collect_settings($settings, $officeFields);
            $faviconUrl = handle_favicon_upload();
            $shareImageUrl = handle_share_image_upload();
            $fieldsToSave = $officeFields;
            if ($faviconUrl !== null) {
                $submittedSettings['favicon_url'] = $faviconUrl;
                $fieldsToSave['favicon_url'] = 'Favicon';
            }
            if ($shareImageUrl !== null) {
                $submittedSettings['seo_og_image'] = $shareImageUrl;
                $fieldsToSave['seo_og_image'] = 'Imagem de compartilhamento';
            }
            admin_save_settings($pdo, $submittedSettings, $fieldsToSave);
            $saved = true;
            $settings = get_site_settings();
        } catch (Throwable $e) {
            $error = $e instanceof RuntimeException ? $e->getMessage() : 'Não foi possível salvar os dados do site.';
        }
    }
}
?>
<?php include __DIR__ . '/_header.php'; ?>
<main class="wrap">
    <h1>Dados do site</h1>
    <?php if ($saved): ?><div class="notice">Dados salvos com sucesso.</div><?php endif; ?>
    <?php if ($error): ?><div class="notice error"><?php echo e($error); ?></div><?php endif; ?>

    <form class="stack" method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <section class="card stack">
            <div>
                <h2>Dados do escritório</h2>
                <p class="muted">Informações usadas nos botões, rodapé e formulário do site.</p>
            </div>

            <div class="grid">
                <?php foreach ($officeFields as $key => $label): ?>
                    <div class="<?php echo $key === 'google_reviews_url' ? 'field-span-2' : ''; ?>">
                        <label for="<?php echo e($key); ?>"><?php echo e($label); ?></label>
                        <input
                            id="<?php echo e($key); ?>"
                            name="<?php echo e($key); ?>"
                            type="<?php echo $key === 'email_contato' ? 'email' : 'text'; ?>"
                            value="<?php echo e($settings[$key] ?? ''); ?>"
                            <?php if ($key === 'whatsapp_raw'): ?>data-phone-raw inputmode="numeric" autocomplete="tel"<?php endif; ?>
                            <?php if ($key === 'whatsapp_friendly'): ?>data-phone-friendly inputmode="tel" autocomplete="tel"<?php endif; ?>
                        >
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="card stack">
            <div>
                <h2>Imagens do site</h2>
                <p class="muted">Arquivos usados no ícone da aba do navegador e nas prévias de WhatsApp e redes sociais.</p>
            </div>
            <div class="media-block-grid">
                <div class="media-upload-card">
                    <div>
                        <h3>Favicon</h3>
                        <p class="muted">Ícone exibido na aba do navegador.</p>
                    </div>
                    <label class="upload-dropzone" for="favicon_file" data-upload-zone>
                        <input id="favicon_file" name="favicon_file" type="file" accept=".ico,.png,image/png,image/x-icon,image/vnd.microsoft.icon" data-upload-input data-upload-target="favicon-preview">
                        <span class="upload-icon" aria-hidden="true"><?php echo ph_icon('upload-simple', ''); ?></span>
                        <strong>Clique ou arraste o favicon</strong>
                        <small>ICO ou PNG. Ideal: quadrado, 32x32 px ou 512x512 px. Máximo: 1 MB.</small>
                        <em data-upload-filename>Nenhum arquivo selecionado</em>
                    </label>
                    <div>
                        <label>Favicon atual</label>
                        <?php if (!empty($settings['favicon_url'])): ?>
                            <div class="favicon-preview">
                                <img id="favicon-preview" src="../<?php echo e($settings['favicon_url']); ?>" alt="Favicon atual">
                                <span><?php echo e($settings['favicon_url']); ?></span>
                            </div>
                        <?php else: ?>
                            <div class="media-preview is-empty media-preview-compact">
                                <div id="favicon-preview" class="media-preview-placeholder">Sem favicon</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="media-upload-card">
                    <div>
                        <h3>WhatsApp e redes sociais</h3>
                        <p class="muted">Imagem usada nas prévias de compartilhamento.</p>
                    </div>
                    <label class="upload-dropzone" for="share_image_file" data-upload-zone>
                        <input id="share_image_file" name="share_image_file" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" data-upload-input data-upload-target="share-preview">
                        <span class="upload-icon" aria-hidden="true"><?php echo ph_icon('upload-simple', ''); ?></span>
                        <strong>Clique ou arraste a imagem</strong>
                        <small>JPG, PNG ou WebP. Ideal: 1200x630 px, proporção 1.91:1. Mínimo: 600x315 px. Máximo: 2 MB.</small>
                        <em data-upload-filename>Nenhum arquivo selecionado</em>
                    </label>
                    <div>
                        <label>Imagem atual de compartilhamento</label>
                        <?php if (!empty($settings['seo_og_image'])): ?>
                            <div class="media-preview">
                                <img id="share-preview" src="../<?php echo e($settings['seo_og_image']); ?>" alt="Imagem atual de compartilhamento">
                                <span><?php echo e($settings['seo_og_image']); ?></span>
                            </div>
                        <?php else: ?>
                            <div class="media-preview is-empty">
                                <div id="share-preview" class="media-preview-placeholder">Sem imagem</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <div>
            <button class="btn btn-primary" type="submit">Salvar dados</button>
        </div>
    </form>
</main>
<script>
    function formatFriendlyPhone(value) {
        const digits = value.replace(/\D/g, '').slice(0, 11);
        if (digits.length <= 2) return digits;
        if (digits.length <= 6) return `(${digits.slice(0, 2)}) ${digits.slice(2)}`;
        if (digits.length <= 10) return `(${digits.slice(0, 2)}) ${digits.slice(2, 6)}-${digits.slice(6)}`;
        return `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`;
    }

    function formatRawPhone(value) {
        return value.replace(/\D/g, '').slice(0, 13);
    }

    document.querySelectorAll('[data-phone-raw]').forEach((input) => {
        input.value = formatRawPhone(input.value);
        input.addEventListener('input', () => {
            input.value = formatRawPhone(input.value);
        });
    });

    document.querySelectorAll('[data-phone-friendly]').forEach((input) => {
        input.value = formatFriendlyPhone(input.value);
        input.addEventListener('input', () => {
            input.value = formatFriendlyPhone(input.value);
        });
    });

    document.querySelectorAll('[data-upload-zone]').forEach((zone) => {
        const input = zone.querySelector('[data-upload-input]');
        const fileName = zone.querySelector('[data-upload-filename]');

        const setPreview = (file) => {
            if (!file) return;
            if (fileName) fileName.textContent = file.name;

            const previewId = input?.dataset.uploadTarget;
            const preview = previewId ? document.getElementById(previewId) : null;
            if (!preview || !file.type.startsWith('image/')) return;

            const url = URL.createObjectURL(file);
            if (preview.tagName.toLowerCase() === 'img') {
                preview.src = url;
                return;
            }

            const image = document.createElement('img');
            image.id = preview.id;
            image.alt = preview.textContent || 'Prévia da imagem';
            image.src = url;
            preview.replaceWith(image);
        };

        input?.addEventListener('change', () => {
            setPreview(input.files?.[0]);
        });

        ['dragenter', 'dragover'].forEach((eventName) => {
            zone.addEventListener(eventName, (event) => {
                event.preventDefault();
                zone.classList.add('is-dragging');
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            zone.addEventListener(eventName, (event) => {
                event.preventDefault();
                zone.classList.remove('is-dragging');
            });
        });

        zone.addEventListener('drop', (event) => {
            const file = event.dataTransfer?.files?.[0];
            if (!file || !input) return;

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            setPreview(file);
        });
    });
</script>
<?php include __DIR__ . '/_footer.php'; ?>
