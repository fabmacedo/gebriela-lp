<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/sitemap-generator.php';
require_admin();

$pdo = db();
$error = '';
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;

function admin_posts_redirect(string $query = ''): void
{
    $page = max(1, (int) ($_POST['page'] ?? $_GET['page'] ?? 1));
    $suffix = $query !== '' ? '&' . $query : '';
    header('Location: posts.php?page=' . $page . $suffix);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle_status') {
    require_valid_csrf();

    if ($pdo) {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare(
                'UPDATE blog_posts
                 SET status = IF(status = "published", "draft", "published"),
                     published_at = CASE
                        WHEN status = "published" THEN published_at
                        WHEN published_at IS NULL THEN NOW()
                        ELSE published_at
                     END
                 WHERE id = :id'
            );
            $stmt->execute(['id' => $id]);
            write_sitemap_xml();
            admin_posts_redirect('status=1');
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    require_valid_csrf();

    if ($pdo) {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('DELETE FROM blog_posts WHERE id = :id');
            $stmt->execute(['id' => $id]);
            write_sitemap_xml();
            admin_posts_redirect('deleted=1');
        }
    }
}

$posts = [];
$totalPosts = 0;
$totalPages = 1;

if (!$pdo) {
    $error = 'Banco não conectado. Confira config/database.php.';
} else {
    try {
        $totalPosts = (int) $pdo->query('SELECT COUNT(*) FROM blog_posts')->fetchColumn();
        $totalPages = max(1, (int) ceil($totalPosts / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $stmt = $pdo->prepare(
            'SELECT id, title, slug, status, published_at, updated_at
             FROM blog_posts
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $posts = $stmt->fetchAll();
    } catch (Throwable $e) {
        $error = 'Não foi possível listar os posts.';
    }
}
?>
<?php include __DIR__ . '/_header.php'; ?>
<main class="wrap">
    <div style="display:flex;justify-content:space-between;gap:16px;align-items:center;flex-wrap:wrap;margin-bottom:18px;">
        <div>
            <h1 style="margin:0;">Blog</h1>
            <p class="muted" style="margin:8px 0 0;">Mostrando até 10 posts por página. Total: <?php echo (int) $totalPosts; ?>.</p>
        </div>
        <a class="btn btn-primary" href="post-edit.php">Novo post</a>
    </div>

    <?php if (isset($_GET['saved'])): ?><div class="notice">Post salvo com sucesso.</div><?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?><div class="notice">Post excluído com sucesso.</div><?php endif; ?>
    <?php if (isset($_GET['status'])): ?><div class="notice">Status do post atualizado.</div><?php endif; ?>
    <?php if ($error): ?><div class="notice error"><?php echo e($error); ?></div><?php endif; ?>

    <section class="card">
        <?php if (!$posts): ?>
            <p class="muted">Nenhum post cadastrado ainda.</p>
        <?php else: ?>
            <table class="posts-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Status</th>
                        <th>Publicado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td>
                                <div class="post-title-cell">
                                <strong><?php echo e($post['title']); ?></strong>
                                </div>
                            </td>
                            <td>
                                <form class="post-status-form" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="action" value="toggle_status">
                                    <input type="hidden" name="id" value="<?php echo (int) $post['id']; ?>">
                                    <input type="hidden" name="page" value="<?php echo (int) $page; ?>">
                                    <button class="status-toggle <?php echo $post['status'] === 'published' ? 'is-published' : ''; ?>" type="submit" title="Alternar entre publicado e rascunho">
                                        <span class="status-pill" aria-hidden="true"></span>
                                        <span class="status-label"><?php echo $post['status'] === 'published' ? 'Publicado' : 'Rascunho'; ?></span>
                                    </button>
                                </form>
                            </td>
                            <td><span class="post-date"><?php echo $post['published_at'] ? e(date('d-m-Y', strtotime($post['published_at']))) : '-'; ?></span></td>
                            <td>
                                <div class="actions post-actions">
                                    <a class="btn" href="post-edit.php?id=<?php echo (int) $post['id']; ?>">Editar</a>
                                    <button
                                        class="btn btn-danger"
                                        type="button"
                                        data-delete-post
                                        data-post-id="<?php echo (int) $post['id']; ?>"
                                        data-post-title="<?php echo e($post['title']); ?>"
                                    >Excluir</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
                <nav class="actions" style="justify-content:center;margin-top:22px;" aria-label="Paginação de posts">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn <?php echo $i === $page ? 'btn-primary' : ''; ?>" href="posts.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</main>
<div class="modal-overlay" id="delete-modal" aria-hidden="true">
    <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <h2 id="delete-modal-title">Excluir post</h2>
        <p>Tem certeza que deseja excluir <strong id="delete-post-title"></strong>? Essa ação não poderá ser desfeita.</p>
        <form method="post" id="delete-post-form">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" id="delete-post-id" value="">
            <input type="hidden" name="page" value="<?php echo (int) $page; ?>">
            <div class="modal-actions">
                <button class="btn btn-ghost" type="button" data-close-delete-modal>Cancelar</button>
                <button class="btn btn-danger" type="submit">Excluir post</button>
            </div>
        </form>
    </div>
</div>
<script>
    const deleteModal = document.getElementById('delete-modal');
    const deletePostId = document.getElementById('delete-post-id');
    const deletePostTitle = document.getElementById('delete-post-title');
    const closeDeleteModal = () => {
        deleteModal?.classList.remove('is-open');
        deleteModal?.setAttribute('aria-hidden', 'true');
    };

    document.querySelectorAll('[data-delete-post]').forEach((button) => {
        button.addEventListener('click', () => {
            deletePostId.value = button.dataset.postId || '';
            deletePostTitle.textContent = button.dataset.postTitle || 'este post';
            deleteModal?.classList.add('is-open');
            deleteModal?.setAttribute('aria-hidden', 'false');
        });
    });

    document.querySelectorAll('[data-close-delete-modal]').forEach((button) => {
        button.addEventListener('click', closeDeleteModal);
    });

    deleteModal?.addEventListener('click', (event) => {
        if (event.target === deleteModal) closeDeleteModal();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeDeleteModal();
    });
</script>
<?php include __DIR__ . '/_footer.php'; ?>
