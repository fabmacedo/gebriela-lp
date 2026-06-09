<?php

$root = dirname(__DIR__);
$extensions = ['php', 'sql', 'xml'];
$excludedPaths = [
    '.git',
    '.superpowers',
    'docs',
    'scripts/check_lp_focus.php',
];
$forbiddenPatterns = [
    '/\bblog_posts\b/i' => 'referência à tabela blog_posts',
    '/\bblog\.php\b/i' => 'referência à rota blog.php',
    '/\bpost\.php\b/i' => 'referência à rota post.php',
    '/\bposts\.php\b/i' => 'referência à administração de posts',
    '/\bpost-edit\.php\b/i' => 'referência à edição de posts',
    '/\bseo_blog_(?:title|description)\b/i' => 'configuração SEO do Blog',
    '/\bseo_post_title_suffix\b/i' => 'configuração SEO de posts',
    '/#sobre\b/i' => 'link para a seção removida #sobre',
    '/Quem sou eu\?/iu' => 'texto removido "Quem sou eu?"',
];

$violations = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if (!$file->isFile()) {
        continue;
    }

    $relativePath = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
    $extension = strtolower($file->getExtension());
    if (!in_array($extension, $extensions, true)) {
        continue;
    }

    foreach ($excludedPaths as $excludedPath) {
        if ($relativePath === $excludedPath || str_starts_with($relativePath, $excludedPath . '/')) {
            continue 2;
        }
    }

    $lines = file($file->getPathname());
    if ($lines === false) {
        continue;
    }

    foreach ($lines as $lineNumber => $line) {
        foreach ($forbiddenPatterns as $pattern => $label) {
            if (preg_match($pattern, $line) === 1) {
                $violations[] = sprintf(
                    '%s:%d - %s',
                    $relativePath,
                    $lineNumber + 1,
                    $label
                );
            }
        }
    }
}

if ($violations !== []) {
    fwrite(STDERR, "A LP ainda contém referências removidas:\n");
    fwrite(STDERR, implode("\n", $violations) . "\n");
    exit(1);
}

echo "LP focada: nenhuma referência ativa ao Blog ou à seção removida.\n";
