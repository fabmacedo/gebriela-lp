<?php
require_once __DIR__ . '/includes/mailer.php';
require_once __DIR__ . '/includes/security.php';

security_headers(false);
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método não permitido.']);
    exit;
}

$honeypot = trim($_POST['website'] ?? '');
if ($honeypot !== '') {
    echo json_encode(['ok' => true, 'message' => 'Solicitação enviada com sucesso.']);
    exit;
}

if (!verify_csrf_token()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'Requisicao invalida. Atualize a pagina e tente novamente.']);
    exit;
}

$rateKey = rate_limit_key('contact-submit');
if (is_rate_limited($rateKey, 5, 600)) {
    http_response_code(429);
    echo json_encode(['ok' => false, 'message' => 'Muitas tentativas. Aguarde alguns minutos e tente novamente.']);
    exit;
}
register_rate_limit_attempt($rateKey, 600);

$settings = get_site_settings();
$allowedAreas = [
    'Acidente de trabalho',
    'Doença física relacionada ao trabalho',
    'Adoecimento emocional relacionado ao trabalho',
    'Benefício do INSS ou CAT',
    'Dispensa após acidente ou adoecimento',
    'Outra situação relacionada à saúde no trabalho',
];

$name = trim_limited((string) ($_POST['name'] ?? ''), 120);
$phone = trim_limited((string) ($_POST['phone'] ?? ''), 30);
$area = trim_limited((string) ($_POST['area'] ?? ''), 80);
$message = trim_limited((string) ($_POST['message'] ?? ''), 2500);
$phoneDigits = only_numbers($phone);

if ($name === '' || strlen($phoneDigits) < 10) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Informe nome e WhatsApp.']);
    exit;
}

if (!in_array($area, $allowedAreas, true)) {
    $area = 'Não informado';
}

$subject = 'Novo atendimento pelo site - ' . $name;
$body = "Novo pedido de atendimento recebido pelo site.\n\n";
$body .= "Nome: {$name}\n";
$body .= "WhatsApp: {$phone}\n";
$body .= "Área de interesse: {$area}\n";
$body .= "Mensagem: " . ($message !== '' ? $message : 'Sem mensagem adicional.') . "\n\n";
$body .= "Data: " . date('d-m-Y H:i') . "\n";

try {
    send_smtp_mail($settings, $subject, $body);
    echo json_encode(['ok' => true, 'message' => 'Solicitação enviada com sucesso.']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Não foi possível enviar agora. Tente novamente pelo WhatsApp.']);
}
