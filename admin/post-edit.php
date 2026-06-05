<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/sitemap-generator.php';
require_admin();

$pdo = db();
$id = (int) ($_GET['id'] ?? 0);
$error = '';
$post = [
    'title' => '',
    'slug' => '',
    'excerpt' => '',
    'content' => '',
    'status' => 'draft',
    'published_at' => date('Y-m-d\TH:i'),
];

function make_slug(string $title): string
{
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
    $slug = strtolower($slug ?: $title);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';
    $slug = trim($slug, '-');

    return $slug ?: 'post';
}

function normalize_post_datetime(string $value): string
{
    if ($value === '') {
        return date('Y-m-d H:i:s');
    }

    $date = DateTimeImmutable::createFromFormat('Y-m-d\TH:i', $value);
    $errors = DateTimeImmutable::getLastErrors();
    $hasErrors = $errors !== false && ((int) $errors['warning_count'] > 0 || (int) $errors['error_count'] > 0);

    if (!$date || $hasErrors) {
        throw new RuntimeException('Data de publicacao invalida.');
    }

    return $date->format('Y-m-d H:i:s');
}

if ($pdo && $id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM blog_posts WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $loaded = $stmt->fetch();
    if ($loaded) {
        $post = $loaded;
        $post['published_at'] = $post['published_at'] ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : date('Y-m-d\TH:i');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf();

    if (!$pdo) {
        $error = 'Banco não conectado. Confira config/database.php.';
    } else {
        $post = [
            'title' => trim_limited((string) ($_POST['title'] ?? ''), 180),
            'slug' => trim_limited((string) ($_POST['slug'] ?? ''), 190),
            'excerpt' => trim_limited((string) ($_POST['excerpt'] ?? ''), 2000),
            'content' => trim_limited((string) ($_POST['content'] ?? ''), 200000),
            'status' => ($_POST['status'] ?? '') === 'published' ? 'published' : 'draft',
            'published_at' => trim_limited((string) ($_POST['published_at'] ?? ''), 16),
        ];

        if ($post['title'] === '' || $post['content'] === '') {
            $error = 'Informe título e conteúdo.';
        } else {
            try {
                $post['slug'] = $post['slug'] !== '' ? make_slug($post['slug']) : make_slug($post['title']);
                $publishedAt = $post['status'] === 'published' ? normalize_post_datetime($post['published_at']) : null;

                if ($id > 0) {
                    $stmt = $pdo->prepare(
                        'UPDATE blog_posts
                         SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, status = :status, published_at = :published_at
                         WHERE id = :id'
                    );
                    $stmt->execute([
                        'title' => $post['title'],
                        'slug' => $post['slug'],
                        'excerpt' => $post['excerpt'],
                        'content' => $post['content'],
                        'status' => $post['status'],
                        'published_at' => $publishedAt,
                        'id' => $id,
                    ]);
                } else {
                    $stmt = $pdo->prepare(
                        'INSERT INTO blog_posts (title, slug, excerpt, content, status, published_at)
                         VALUES (:title, :slug, :excerpt, :content, :status, :published_at)'
                    );
                    $stmt->execute([
                        'title' => $post['title'],
                        'slug' => $post['slug'],
                        'excerpt' => $post['excerpt'],
                        'content' => $post['content'],
                        'status' => $post['status'],
                        'published_at' => $publishedAt,
                    ]);
                }

                write_sitemap_xml();
                header('Location: posts.php?saved=1');
                exit;
            } catch (Throwable $e) {
                $error = 'Não foi possível salvar. Verifique se o slug já está em uso.';
            }
        }
    }
}
?>
<?php include __DIR__ . '/_header.php'; ?>
<main class="wrap">
    <h1><?php echo $id > 0 ? 'Editar post' : 'Novo post'; ?></h1>
    <?php if ($error): ?><div class="notice error"><?php echo e($error); ?></div><?php endif; ?>

    <form class="card stack" method="post">
        <?php echo csrf_field(); ?>
        <div class="grid">
            <div>
                <label for="title">Título</label>
                <input id="title" name="title" type="text" value="<?php echo e($post['title']); ?>" required>
            </div>
            <div>
                <label for="slug">Slug</label>
                <input id="slug" name="slug" type="text" value="<?php echo e($post['slug']); ?>" placeholder="gerado automaticamente se vazio">
            </div>
        </div>
        <div>
            <label for="excerpt">Resumo</label>
            <textarea id="excerpt" name="excerpt" style="min-height:90px;"><?php echo e($post['excerpt']); ?></textarea>
        </div>
        <div>
            <label for="content">Conteúdo</label>
            <textarea id="content" name="content" required><?php echo e($post['content']); ?></textarea>
        </div>
        <div class="grid">
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="draft" <?php echo $post['status'] === 'draft' ? 'selected' : ''; ?>>Rascunho</option>
                    <option value="published" <?php echo $post['status'] === 'published' ? 'selected' : ''; ?>>Publicado</option>
                </select>
            </div>
            <div>
                <label for="published_at">Data de publicação</label>
                <input id="published_at" name="published_at" type="datetime-local" value="<?php echo e($post['published_at']); ?>">
            </div>
        </div>
        <div class="actions">
            <button class="btn btn-primary" type="submit">Salvar post</button>
            <a class="btn" href="posts.php">Cancelar</a>
        </div>
    </form>
</main>
<?php include __DIR__ . '/_footer.php'; ?>
