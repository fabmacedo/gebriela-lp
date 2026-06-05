<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/sitemap-generator.php';
require_once __DIR__ . '/_settings_helpers.php';
require_admin();

$pdo = db();
$saved = false;
$error = '';
$settings = get_site_settings();
$seoFields = admin_seo_fields();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf();

    if (!$pdo) {
        $error = 'Banco não conectado. Confira config/database.php.';
    } else {
        try {
            $submittedSettings = admin_collect_settings($settings, $seoFields);
            admin_save_settings($pdo, $submittedSettings, $seoFields);
            $saved = true;
            $settings = get_site_settings();
            write_sitemap_xml();
        } catch (Throwable $e) {
            $error = 'Não foi possível salvar as configurações de SEO.';
        }
    }
}
?>
<?php include __DIR__ . '/_header.php'; ?>
<main class="wrap">
    <h1>SEO do site</h1>
    <?php if ($saved): ?><div class="notice">SEO salvo com sucesso.</div><?php endif; ?>
    <?php if ($error): ?><div class="notice error"><?php echo e($error); ?></div><?php endif; ?>

    <form class="stack" method="post">
        <?php echo csrf_field(); ?>
        <section class="card stack">
            <div>
                <h2>Metatags e compartilhamento</h2>
                <p class="muted">Configurações usadas nos títulos, descrições, indexação, redes sociais e dados estruturados das páginas.</p>
            </div>

            <div class="grid">
                <div>
                    <label for="seo_site_url">URL principal do site</label>
                    <input id="seo_site_url" name="seo_site_url" type="url" value="<?php echo e($settings['seo_site_url'] ?? ''); ?>" placeholder="https://gabrielapitaadv.com.br">
                </div>
                <div>
                    <label for="seo_author">Autor</label>
                    <input id="seo_author" name="seo_author" type="text" value="<?php echo e($settings['seo_author'] ?? ''); ?>">
                </div>
                <div class="field-span-2">
                    <label for="seo_home_title">Título SEO da home</label>
                    <input id="seo_home_title" name="seo_home_title" type="text" value="<?php echo e($settings['seo_home_title'] ?? ''); ?>">
                </div>
                <div class="field-span-2">
                    <label for="seo_home_description">Descrição SEO da home</label>
                    <textarea id="seo_home_description" name="seo_home_description"><?php echo e($settings['seo_home_description'] ?? ''); ?></textarea>
                </div>
                <div class="field-span-2">
                    <label for="seo_home_keywords">Palavras-chave</label>
                    <textarea id="seo_home_keywords" name="seo_home_keywords"><?php echo e($settings['seo_home_keywords'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label for="seo_blog_title">Título SEO do blog</label>
                    <input id="seo_blog_title" name="seo_blog_title" type="text" value="<?php echo e($settings['seo_blog_title'] ?? ''); ?>">
                </div>
                <div>
                    <label for="seo_post_title_suffix">Sufixo dos títulos dos posts</label>
                    <input id="seo_post_title_suffix" name="seo_post_title_suffix" type="text" value="<?php echo e($settings['seo_post_title_suffix'] ?? ''); ?>">
                </div>
                <div class="field-span-2">
                    <label for="seo_blog_description">Descrição SEO do blog</label>
                    <textarea id="seo_blog_description" name="seo_blog_description"><?php echo e($settings['seo_blog_description'] ?? ''); ?></textarea>
                </div>
                <div>
                    <label for="seo_robots">Robots</label>
                    <select id="seo_robots" name="seo_robots">
                        <option value="index, follow" <?php echo ($settings['seo_robots'] ?? 'index, follow') === 'index, follow' ? 'selected' : ''; ?>>Indexar e seguir links</option>
                        <option value="noindex, follow" <?php echo ($settings['seo_robots'] ?? '') === 'noindex, follow' ? 'selected' : ''; ?>>Não indexar, seguir links</option>
                        <option value="noindex, nofollow" <?php echo ($settings['seo_robots'] ?? '') === 'noindex, nofollow' ? 'selected' : ''; ?>>Não indexar nem seguir links</option>
                    </select>
                </div>
                <div>
                    <label for="seo_twitter_card">Twitter Card</label>
                    <select id="seo_twitter_card" name="seo_twitter_card">
                        <option value="summary_large_image" <?php echo ($settings['seo_twitter_card'] ?? 'summary_large_image') === 'summary_large_image' ? 'selected' : ''; ?>>Imagem grande</option>
                        <option value="summary" <?php echo ($settings['seo_twitter_card'] ?? '') === 'summary' ? 'selected' : ''; ?>>Resumo simples</option>
                    </select>
                </div>
                <div>
                    <label for="seo_locale">Idioma Open Graph</label>
                    <input id="seo_locale" name="seo_locale" type="text" value="<?php echo e($settings['seo_locale'] ?? 'pt_BR'); ?>" placeholder="pt_BR">
                </div>
                <div>
                    <label for="seo_schema_type">Tipo de schema</label>
                    <select id="seo_schema_type" name="seo_schema_type">
                        <option value="LegalService" <?php echo ($settings['seo_schema_type'] ?? 'LegalService') === 'LegalService' ? 'selected' : ''; ?>>Serviço jurídico</option>
                        <option value="Attorney" <?php echo ($settings['seo_schema_type'] ?? '') === 'Attorney' ? 'selected' : ''; ?>>Advogada / advogado</option>
                        <option value="Organization" <?php echo ($settings['seo_schema_type'] ?? '') === 'Organization' ? 'selected' : ''; ?>>Organização</option>
                    </select>
                </div>
                <div>
                    <label for="seo_area_served">Área atendida</label>
                    <input id="seo_area_served" name="seo_area_served" type="text" value="<?php echo e($settings['seo_area_served'] ?? ''); ?>">
                </div>
                <div class="field-span-2">
                    <label for="seo_og_title">Título de compartilhamento</label>
                    <input id="seo_og_title" name="seo_og_title" type="text" value="<?php echo e($settings['seo_og_title'] ?? ''); ?>">
                </div>
                <div class="field-span-2">
                    <label for="seo_og_description">Descrição de compartilhamento</label>
                    <textarea id="seo_og_description" name="seo_og_description"><?php echo e($settings['seo_og_description'] ?? ''); ?></textarea>
                </div>
                <div class="field-span-2">
                    <label for="seo_business_description">Descrição estruturada do escritório</label>
                    <textarea id="seo_business_description" name="seo_business_description"><?php echo e($settings['seo_business_description'] ?? ''); ?></textarea>
                </div>
            </div>
        </section>

        <div>
            <button class="btn btn-primary" type="submit">Salvar SEO</button>
        </div>
    </form>
</main>
<?php include __DIR__ . '/_footer.php'; ?>
