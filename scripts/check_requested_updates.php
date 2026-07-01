<?php

$root = dirname(__DIR__);

function read_project_file(string $relativePath): string
{
    global $root;

    $path = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    $content = file_get_contents($path);
    if ($content === false) {
        fwrite(STDERR, "Nao foi possivel ler {$relativePath}.\n");
        exit(1);
    }

    return $content;
}

function require_contains(string $content, string $needle, string $label, array &$failures): void
{
    if (!str_contains($content, $needle)) {
        $failures[] = "{$label}: texto esperado ausente.";
    }
}

function require_not_contains(string $content, string $needle, string $label, array &$failures): void
{
    if (str_contains($content, $needle)) {
        $failures[] = "{$label}: texto proibido encontrado.";
    }
}

function require_ordered_occurrence(string $content, array $needles, string $label, array &$failures): void
{
    $offset = 0;
    foreach ($needles as $needle) {
        $position = strpos($content, $needle, $offset);
        if ($position === false) {
            $failures[] = "{$label}: item fora de ordem ou ausente: {$needle}.";
            return;
        }

        $offset = $position + strlen($needle);
    }
}

$expectedTitle = 'Nenhum trabalho deveria custar a sua saúde mental.';
$expectedDescription = 'Se você vive sob pressão excessiva, metas abusivas, assédio ou um ambiente que tem afetado sua saúde emocional, saiba que essa situação pode gerar direitos trabalhistas.';
$expectedOab = 'OAB - 27344';

$failures = [];

require_once $root . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'site.php';
if (!function_exists('normalize_oab_registro')) {
    $failures[] = 'Normalizacao de OAB: funcao ausente.';
} elseif (normalize_oab_registro('OAB/BA 123.456') !== $expectedOab) {
    $failures[] = 'Normalizacao de OAB: valor antigo do banco nao vira OAB correta.';
}

$index = read_project_file('index.php');
require_contains($index, $expectedTitle, 'Hero titulo', $failures);
require_contains($index, $expectedDescription, 'Hero descricao', $failures);
require_not_contains($index, 'Seu trabalho deixou marcas na sua saúde?', 'Hero titulo antigo', $failures);
require_not_contains($index, 'Quando uma lesão, uma dor persistente ou um adoecimento começa a afetar sua rotina', 'Hero descricao antiga', $failures);
require_ordered_occurrence($index, [
    "['Assédio moral no trabalho'",
    "['Ansiedade, burnout e depressão'",
    "['Burnout e saúde emocional'",
    "['Doença agravada pelo trabalho'",
    "['LER/DORT, dores e limitações'",
    "['Problemas de coluna e lesões'",
    "['Perda auditiva e exposições'",
    "['Dispensa após adoecimento'",
    "['Acidente durante o trabalho'",
], 'Ordem dos cards de situações', $failures);
require_contains($index, 'Humilhações, perseguições, cobranças abusivas, isolamento ou constrangimentos repetitivos que podem comprometer a saúde mental do trabalhador.', 'Card assedio moral', $failures);
require_contains($index, 'Adoecimento relacionado à pressão excessiva, metas abusivas, jornadas exaustivas ou outras condições do ambiente de trabalho.', 'Card ansiedade burnout depressao', $failures);

$site = read_project_file('includes/site.php');
require_contains($site, "'oab_registro' => '{$expectedOab}'", 'Default OAB', $failures);

$database = read_project_file('database.sql');
require_contains($database, "('oab_registro', '{$expectedOab}')", 'Seed OAB', $failures);
require_not_contains($database, 'OAB/BA 123.456', 'OAB placeholder no seed', $failures);

$cpanel = read_project_file('.cpanel.yml');
require_contains($cpanel, 'DEPLOYPATH=$HOME/trabalhista.gabrielapitaadvogados.com.br/', 'Deploy path do subdominio', $failures);
require_contains($cpanel, '/bin/cp config/.htaccess $DEPLOYPATH/config/.htaccess', 'Deploy protege config', $failures);
require_not_contains($cpanel, 'config/database.php', 'Deploy de credenciais', $failures);
require_not_contains($cpanel, '/bin/cp -R config', 'Deploy da pasta config inteira', $failures);

$trackedConfig = [];
$exitCode = 0;
exec('git -C ' . escapeshellarg($root) . ' ls-files config/database.php', $trackedConfig, $exitCode);
if ($exitCode !== 0) {
    $failures[] = 'Nao foi possivel consultar arquivos rastreados pelo Git.';
} elseif ($trackedConfig !== []) {
    $failures[] = 'config/database.php ainda esta rastreado pelo Git.';
}

if ($failures !== []) {
    fwrite(STDERR, "Atualizacoes solicitadas ainda pendentes:\n");
    fwrite(STDERR, implode("\n", $failures) . "\n");
    exit(1);
}

echo "Atualizacoes solicitadas verificadas.\n";
