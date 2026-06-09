<?php

$index = file_get_contents(dirname(__DIR__) . '/index.php');
if ($index === false) {
    fwrite(STDERR, "Não foi possível ler index.php.\n");
    exit(1);
}

$requiredStickyBlocks = ['documentos', 'duvidas'];

$missing = [];
foreach ($requiredStickyBlocks as $section) {
    $sectionPattern = '/<section id="' . preg_quote($section, '/') . '".*?<\/section>/s';
    if (preg_match($sectionPattern, $index, $matches) !== 1) {
        $missing[] = $section;
        continue;
    }

    if (!str_contains($matches[0], '<aside class="reveal lg:sticky lg:top-28 lg:self-start">')) {
        $missing[] = $section;
    }
}

if ($missing !== []) {
    fwrite(STDERR, 'Blocos sticky ausentes: ' . implode(', ', $missing) . "\n");
    exit(1);
}

$documentosPattern = '/<section id="documentos".*?<\/section>/s';
if (preg_match($documentosPattern, $index, $matches) !== 1 || !str_contains($matches[0], 'data-document-timeline')) {
    fwrite(STDERR, "A seção documentos deve usar o layout de linha do tempo.\n");
    exit(1);
}

if (str_contains($matches[0], 'md:static')) {
    fwrite(STDERR, "Os marcadores da linha do tempo de documentos devem permanecer alinhados na linha.\n");
    exit(1);
}

echo "Blocos laterais sticky verificados.\n";
