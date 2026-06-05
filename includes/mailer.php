<?php

require_once __DIR__ . '/site.php';

function smtp_read($socket): string
{
    $data = '';
    while (($line = fgets($socket, 515)) !== false) {
        $data .= $line;
        if (strlen($line) >= 4 && $line[3] === ' ') {
            break;
        }
    }
    return $data;
}

function smtp_code(string $response): int
{
    return (int) substr($response, 0, 3);
}

function smtp_command($socket, string $command, array $expected): string
{
    fwrite($socket, $command . "\r\n");
    $response = smtp_read($socket);
    if (!in_array(smtp_code($response), $expected, true)) {
        throw new RuntimeException('SMTP retornou: ' . trim($response));
    }
    return $response;
}

function smtp_header(string $value): string
{
    return str_replace(["\r", "\n"], '', $value);
}

function smtp_address(string $email): string
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
}

function smtp_body(string $body): string
{
    $body = str_replace(["\r\n", "\r"], "\n", $body);
    $lines = explode("\n", $body);
    foreach ($lines as &$line) {
        if (isset($line[0]) && $line[0] === '.') {
            $line = '.' . $line;
        }
    }
    return implode("\r\n", $lines);
}

function send_smtp_mail(array $settings, string $subject, string $body, ?string $replyTo = null): void
{
    if (($settings['smtp_enabled'] ?? '0') !== '1') {
        throw new RuntimeException('SMTP não está ativado no painel.');
    }

    $host = trim($settings['smtp_host'] ?? '');
    $port = (int) ($settings['smtp_port'] ?? 587);
    $encryption = strtolower(trim($settings['smtp_encryption'] ?? 'tls'));
    $username = trim($settings['smtp_username'] ?? '');
    $password = (string) ($settings['smtp_password'] ?? '');
    $fromEmail = smtp_address(trim($settings['smtp_from_email'] ?: $username));
    $fromName = smtp_header(trim($settings['smtp_from_name'] ?? 'Site'));
    $toEmail = smtp_address(trim($settings['smtp_to_email'] ?: $settings['email_contato']));
    $replyTo = $replyTo ? smtp_address($replyTo) : '';

    if ($host === '' || !$port || $fromEmail === '' || $toEmail === '') {
        throw new RuntimeException('Configuração SMTP incompleta.');
    }

    $target = ($encryption === 'ssl' ? 'ssl://' : '') . $host;
    $socket = fsockopen($target, $port, $errno, $errstr, 20);
    if (!$socket) {
        throw new RuntimeException('Não foi possível conectar ao SMTP: ' . $errstr);
    }

    stream_set_timeout($socket, 20);

    try {
        $response = smtp_read($socket);
        if (smtp_code($response) !== 220) {
            throw new RuntimeException('SMTP não aceitou conexão: ' . trim($response));
        }

        smtp_command($socket, 'EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'), [250]);

        if ($encryption === 'tls') {
            smtp_command($socket, 'STARTTLS', [220]);
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new RuntimeException('Falha ao iniciar TLS no SMTP.');
            }
            smtp_command($socket, 'EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'), [250]);
        }

        if ($username !== '' || $password !== '') {
            smtp_command($socket, 'AUTH LOGIN', [334]);
            smtp_command($socket, base64_encode($username), [334]);
            smtp_command($socket, base64_encode($password), [235]);
        }

        smtp_command($socket, 'MAIL FROM:<' . $fromEmail . '>', [250]);
        smtp_command($socket, 'RCPT TO:<' . $toEmail . '>', [250, 251]);
        smtp_command($socket, 'DATA', [354]);

        $headers = [
            'From: ' . ($fromName !== '' ? '=?UTF-8?B?' . base64_encode($fromName) . '?= <' . $fromEmail . '>' : $fromEmail),
            'To: <' . $toEmail . '>',
            'Subject: =?UTF-8?B?' . base64_encode(smtp_header($subject)) . '?=',
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
        ];

        if ($replyTo !== '') {
            $headers[] = 'Reply-To: <' . $replyTo . '>';
        }

        fwrite($socket, implode("\r\n", $headers) . "\r\n\r\n" . smtp_body($body) . "\r\n.\r\n");
        $response = smtp_read($socket);
        if (smtp_code($response) !== 250) {
            throw new RuntimeException('SMTP não aceitou a mensagem: ' . trim($response));
        }

        smtp_command($socket, 'QUIT', [221]);
    } finally {
        fclose($socket);
    }
}

function test_smtp_connection(array $settings): void
{
    if (($settings['smtp_enabled'] ?? '0') !== '1') {
        throw new RuntimeException('SMTP não está ativado no painel.');
    }

    $host = trim($settings['smtp_host'] ?? '');
    $port = (int) ($settings['smtp_port'] ?? 587);
    $encryption = strtolower(trim($settings['smtp_encryption'] ?? 'tls'));
    $username = trim($settings['smtp_username'] ?? '');
    $password = (string) ($settings['smtp_password'] ?? '');

    if ($host === '' || !$port) {
        throw new RuntimeException('Informe servidor e porta SMTP.');
    }

    $target = ($encryption === 'ssl' ? 'ssl://' : '') . $host;
    $socket = fsockopen($target, $port, $errno, $errstr, 20);
    if (!$socket) {
        throw new RuntimeException('Não foi possível conectar ao SMTP: ' . $errstr);
    }

    stream_set_timeout($socket, 20);

    try {
        $response = smtp_read($socket);
        if (smtp_code($response) !== 220) {
            throw new RuntimeException('SMTP não aceitou conexão: ' . trim($response));
        }

        smtp_command($socket, 'EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'), [250]);

        if ($encryption === 'tls') {
            smtp_command($socket, 'STARTTLS', [220]);
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new RuntimeException('Falha ao iniciar TLS no SMTP.');
            }
            smtp_command($socket, 'EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'), [250]);
        }

        if ($username !== '' || $password !== '') {
            smtp_command($socket, 'AUTH LOGIN', [334]);
            smtp_command($socket, base64_encode($username), [334]);
            smtp_command($socket, base64_encode($password), [235]);
        }

        smtp_command($socket, 'QUIT', [221]);
    } finally {
        fclose($socket);
    }
}
