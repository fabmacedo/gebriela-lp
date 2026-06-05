<?php

$root = dirname(__DIR__);
$extensions = ['php', 'sql', 'md'];
$badPatterns = [
    'Ã¡',
    'Ã¢',
    'Ã£',
    'Ãª',
    'Ã©',
    'Ã­',
    'Ã³',
    'Ãµ',
    'Ãº',
    'Ã§',
    'Ã‡',
    'Â©',
    'Âº',
    'Âª',
    '�',
    'â˜',
    'deviva',
];

$issues = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if (!$file->isFile()) {
        continue;
    }

    $path = $file->getPathname();
    if (str_contains($path, DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR)) {
        continue;
    }
    if (basename($path) === 'TEXT_RULES.md' || basename($path) === 'check_text_quality.php') {
        continue;
    }

    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (!in_array($extension, $extensions, true)) {
        continue;
    }

    $content = file_get_contents($path);
    if ($content === false) {
        continue;
    }

    foreach ($badPatterns as $pattern) {
        if (str_contains($content, $pattern)) {
            $issues[] = str_replace($root . DIRECTORY_SEPARATOR, '', $path) . " contém possível problema: {$pattern}";
        }
    }
}

if ($issues) {
    echo implode(PHP_EOL, $issues) . PHP_EOL;
    exit(1);
}

echo "Textos verificados: nenhum problema comum de acentuação encontrado." . PHP_EOL;
