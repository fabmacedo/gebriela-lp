<?php
require_once __DIR__ . '/../includes/auth.php';
$admin = current_admin();
$settings = get_site_settings();
security_headers(true);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin - Gabriela Pita</title>
<?php render_favicon_links($settings); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bellefair&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<?php render_phosphor_icons(); ?>
    <style>
        @font-face {
            font-family: "Taken by Vultures";
            src: url("../font/Taken%20by%20Vultures%20Demo.otf") format("opentype");
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        :root {
            --bg: #3F070A;
            --panel: #4A080B;
            --panel-dark: #260305;
            --field: #2F0507;
            --field-hover: #3A0608;
            --line: rgba(244,237,228,.14);
            --gold: #D7C2A8;
            --text: #F4EDE4;
            --muted: rgba(244,237,228,.64);
            --wine-dark: #2F130D;
            --danger: #ef4444;
        }
        * { box-sizing: border-box; }
        html { min-height: 100%; }
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            font-family: "Playfair Display", Georgia, serif;
            font-size: 17px;
            line-height: 1.42;
            background:
                radial-gradient(circle at 90% -10%, rgba(215,194,168,.14), transparent 30%),
                linear-gradient(180deg, var(--bg) 0%, var(--panel-dark) 100%);
            color: var(--text);
        }
        input, textarea, select, button { font-family: inherit; }
        a { color: inherit; text-decoration: none; }
        .topbar { border-bottom: 1px solid var(--line); background: rgba(38,3,5,.92); backdrop-filter: blur(18px); }
        .topbar-inner { max-width: 1280px; margin: 0 auto; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { display: inline-flex; align-items: center; gap: 10px; }
        .brand-tag {
            display: inline-flex;
            align-items: center;
            min-height: 26px;
            border: 1px solid rgba(215,194,168,.35);
            border-radius: 999px;
            background: rgba(215,194,168,.1);
            color: var(--gold);
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 500;
            line-height: 1;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .brand-name {
            font-family: "Bellefair", Georgia, serif;
            font-size: 26px;
            font-weight: 400;
            letter-spacing: .035em;
            line-height: .9;
            text-transform: uppercase;
        }
        .nav { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .nav a, .btn { border: 1px solid var(--line); background: rgba(255,255,255,.03); color: var(--text); padding: 10px 14px; border-radius: 8px; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; justify-content: center; }
        .nav a:hover, .btn:hover { border-color: rgba(215,194,168,.45); }
        .btn-primary { background: var(--gold); border-color: var(--gold); color: var(--wine-dark); font-weight: 500; }
        .btn-danger { color: var(--wine-dark); border-color: rgba(248,113,113,.8); background: #f87171; font-weight: 500; }
        .btn-danger:hover { border-color: #fb9a9a; background: #fb9a9a; }
        .btn-ghost { background: transparent; }
        .wrap { width: 100%; max-width: 1280px; margin: 0 auto; padding: 28px 20px 56px; flex: 1 0 auto; }
        .card { background: var(--panel); border: 1px solid var(--line); border-radius: 10px; padding: 22px; }
        .card[id] { scroll-margin-top: 18px; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
        .stack { display: grid; gap: 16px; }
        h1 { margin: 0 0 18px; font-size: 28px; }
        h2 { margin: 0 0 14px; font-size: 20px; }
        label { display: block; color: var(--muted); font-size: 13px; margin: 0 0 7px; }
        input, textarea, select { width: 100%; border: 1px solid var(--line); background: var(--field); color: var(--text); border-radius: 8px; padding: 12px; font-size: 15px; }
        input:focus, textarea:focus, select:focus { outline: none; border-color: rgba(215,194,168,.65); box-shadow: 0 0 0 3px rgba(215,194,168,.12); }
        .password-field { position: relative; }
        .password-field input { padding-right: 48px; }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 8px;
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            transform: translateY(-50%);
            border: 1px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            padding: 0;
        }
        .password-toggle:hover,
        .password-toggle:focus { color: var(--gold); border-color: rgba(215,194,168,.35); outline: none; }
        .password-toggle i { display: block; font-size: 18px; line-height: 1; }
        select {
            appearance: none;
            background-color: var(--field);
            background-image:
                linear-gradient(45deg, transparent 50%, var(--gold) 50%),
                linear-gradient(135deg, var(--gold) 50%, transparent 50%),
                linear-gradient(to right, rgba(244,237,228,.12), rgba(244,237,228,.12));
            background-position:
                calc(100% - 22px) 50%,
                calc(100% - 16px) 50%,
                calc(100% - 46px) 50%;
            background-size: 6px 6px, 6px 6px, 1px 24px;
            background-repeat: no-repeat;
            cursor: pointer;
            padding-right: 58px;
            transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
        }
        select:hover { border-color: rgba(215,194,168,.42); background-color: var(--field-hover); }
        select option { background: var(--panel-dark); color: var(--text); padding: 12px; }
        select option:checked { background: var(--gold); color: var(--wine-dark); font-weight: 500; }
        select option:hover { background: rgba(215,194,168,.22); color: var(--text); }
        .select-field { position: relative; }
        .select-field select {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            pointer-events: none;
        }
        .custom-select-button {
            width: 100%;
            min-height: 46px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--field);
            color: var(--text);
            padding: 12px 14px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            text-align: left;
            transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
        }
        .custom-select-button:hover,
        .custom-select-button:focus,
        .select-field.is-open .custom-select-button {
            border-color: rgba(215,194,168,.65);
            box-shadow: 0 0 0 3px rgba(215,194,168,.12);
            outline: none;
        }
        .custom-select-caret { flex: 0 0 auto; color: var(--gold); font-size: 16px; line-height: 1; transition: rotate .18s ease; }
        .select-field.is-open .custom-select-caret { rotate: 180deg; }
        .custom-select-options {
            position: absolute;
            left: 0;
            right: 0;
            top: calc(100% + 6px);
            z-index: 80;
            display: none;
            gap: 4px;
            max-height: 240px;
            overflow: auto;
            border: 1px solid rgba(215,194,168,.28);
            border-radius: 8px;
            background: var(--panel-dark);
            box-shadow: 0 18px 44px rgba(0,0,0,.36);
            padding: 6px;
        }
        .select-field.is-open .custom-select-options { display: grid; }
        .custom-select-option {
            width: 100%;
            border: 0;
            border-radius: 5px;
            background: transparent;
            color: var(--text);
            cursor: pointer;
            padding: 10px 12px;
            text-align: left;
            font-size: 15px;
        }
        .custom-select-option:hover,
        .custom-select-option:focus {
            background: rgba(215,194,168,.12);
            color: var(--text);
            outline: none;
        }
        .custom-select-option.is-selected {
            background: rgba(215,194,168,.2);
            color: var(--text);
            font-weight: 500;
        }
        textarea { min-height: 140px; resize: vertical; }
        table { width: 100%; border-collapse: collapse; overflow: hidden; }
        th, td { border-bottom: 1px solid var(--line); padding: 12px 10px; text-align: left; vertical-align: top; font-size: 14px; }
        th { color: var(--muted); font-weight: 500; }
        .posts-table th, .posts-table td { vertical-align: middle; }
        .posts-table th:nth-child(2),
        .posts-table td:nth-child(2) { width: 210px; }
        .posts-table th:nth-child(3),
        .posts-table td:nth-child(3) { width: 150px; text-align: center; white-space: nowrap; }
        .posts-table th:nth-child(4),
        .posts-table td:nth-child(4) { width: 190px; text-align: right; }
        .post-title-cell { display: grid; gap: 4px; min-width: 0; }
        .post-title-cell strong,
        .post-title-cell span { overflow-wrap: anywhere; }
        .post-status-form { margin: 0; }
        .post-date { display: inline-flex; min-height: 40px; align-items: center; }
        .post-actions { justify-content: flex-end; flex-wrap: nowrap; }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .notice { border: 1px solid rgba(34,197,94,.35); background: rgba(34,197,94,.12); padding: 12px 14px; border-radius: 8px; margin-bottom: 18px; color: #bbf7d0; }
        .error { border-color: rgba(239,68,68,.4); background: rgba(239,68,68,.12); color: #fecaca; }
        .muted { color: var(--muted); }
        .field-span-2 { grid-column: span 2; }
        .media-block-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .media-upload-card { display: grid; align-content: start; gap: 14px; border: 1px solid var(--line); background: rgba(255,255,255,.025); border-radius: 10px; padding: 18px; }
        .media-upload-card h3 { margin: 0; font-size: 18px; }
        .media-upload-card p { margin: 6px 0 0; }
        .upload-dropzone {
            min-height: 168px;
            display: grid;
            place-items: center;
            align-content: center;
            gap: 8px;
            border: 1px dashed rgba(215,194,168,.38);
            background: linear-gradient(180deg, rgba(215,194,168,.08), rgba(255,255,255,.025));
            border-radius: 10px;
            padding: 22px;
            text-align: center;
            cursor: pointer;
            transition: border-color .18s ease, background .18s ease, box-shadow .18s ease;
        }
        .upload-dropzone:hover,
        .upload-dropzone.is-dragging {
            border-color: rgba(215,194,168,.82);
            background: rgba(215,194,168,.12);
            box-shadow: 0 0 0 3px rgba(215,194,168,.12);
        }
        .upload-dropzone input { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }
        .upload-dropzone strong { color: var(--text); font-size: 16px; }
        .upload-dropzone small { max-width: 420px; color: var(--muted); font-size: 13px; line-height: 1.35; }
        .upload-dropzone em { color: var(--gold); font-size: 13px; font-style: normal; overflow-wrap: anywhere; }
        .upload-icon { width: 42px; height: 42px; display: grid; place-items: center; border-radius: 50%; border: 1px solid rgba(215,194,168,.45); color: var(--gold); font-size: 26px; line-height: 1; }
        .favicon-preview { display: flex; align-items: center; gap: 12px; min-height: 46px; border: 1px solid var(--line); background: var(--field); border-radius: 8px; padding: 10px 12px; color: var(--muted); font-size: 14px; }
        .favicon-preview img { width: 28px; height: 28px; object-fit: contain; border-radius: 5px; background: #fff; padding: 2px; }
        .favicon-preview span { overflow-wrap: anywhere; }
        .media-preview { min-height: 168px; display: grid; gap: 10px; border: 1px solid var(--line); background: var(--field); border-radius: 10px; padding: 12px; color: var(--muted); font-size: 14px; }
        .media-preview-compact { min-height: 92px; }
        .media-preview img { width: 100%; max-height: 132px; object-fit: contain; border-radius: 8px; background: var(--panel-dark); }
        .media-preview span { overflow-wrap: anywhere; }
        .media-preview.is-empty { place-items: center; }
        .media-preview-placeholder { width: 100%; min-height: 120px; display: grid; place-items: center; border: 1px dashed var(--line); border-radius: 8px; color: var(--muted); }
        .status-toggle { display: inline-flex; align-items: center; gap: 10px; border: 0; background: transparent; color: var(--text); padding: 0; cursor: pointer; font-size: 13px; }
        .status-pill { position: relative; width: 44px; height: 24px; border-radius: 999px; background: rgba(148,163,184,.35); border: 1px solid var(--line); transition: background .2s ease, border-color .2s ease; }
        .status-pill::after { content: ""; position: absolute; width: 18px; height: 18px; top: 2px; left: 3px; border-radius: 999px; background: #fff; transition: transform .2s ease; }
        .status-toggle.is-published .status-pill { background: rgba(215,194,168,.92); border-color: rgba(215,194,168,.95); }
        .status-toggle.is-published .status-pill::after { transform: translateX(19px); background: var(--wine-dark); }
        .status-label { color: var(--muted); min-width: 70px; text-align: left; }
        .status-toggle.is-published .status-label { color: var(--gold); }
        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: none;
            place-items: center;
            padding: 20px;
            background: rgba(38,3,5,.68);
            backdrop-filter: blur(10px);
        }
        .modal-overlay.is-open { display: grid; }
        .modal-dialog {
            width: min(480px, 100%);
            border: 1px solid rgba(248,113,113,.35);
            border-radius: 12px;
            background: var(--panel);
            box-shadow: 0 24px 80px rgba(0,0,0,.45);
            padding: 24px;
        }
        .modal-dialog h2 { margin-bottom: 10px; }
        .modal-dialog p { margin: 0; color: var(--muted); }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; flex-wrap: wrap; }
        .admin-footer { margin-top: auto; border-top: 1px solid var(--line); background: rgba(38,3,5,.92); color: var(--muted); }
        .admin-footer-inner { max-width: 1280px; margin: 0 auto; padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; gap: 14px; font-size: 13px; }
        .admin-footer-brand,
        .admin-version { display: inline-flex; align-items: center; gap: 8px; }
        .admin-footer-heart { color: #f87171; font-size: 15px; line-height: 1; }
        .admin-footer strong { color: var(--text); font-weight: 400; }
        .admin-version { border: 1px solid var(--line); border-radius: 999px; padding: 6px 10px; background: rgba(255,255,255,.025); color: var(--gold); font-weight: 500; }
        .login { min-height: 100vh; display: grid; place-items: center; padding: 20px; }
        .login .card { width: min(420px, 100%); }
        @media (max-width: 760px) {
            .topbar-inner { align-items: flex-start; flex-direction: column; }
            .grid { grid-template-columns: 1fr; }
            .grid-3 { grid-template-columns: 1fr; }
            .media-block-grid { grid-template-columns: 1fr; }
            .field-span-2 { grid-column: span 1; }
            table, thead, tbody, tr, th, td { display: block; }
            thead { display: none; }
            td { padding: 10px 0; }
            tr { border-bottom: 1px solid var(--line); padding: 12px 0; }
            .posts-table th:nth-child(2),
            .posts-table td:nth-child(2),
            .posts-table th:nth-child(3),
            .posts-table td:nth-child(3),
            .posts-table th:nth-child(4),
            .posts-table td:nth-child(4) { width: auto; text-align: left; }
            .post-actions { justify-content: flex-start; flex-wrap: wrap; }
            .admin-footer-inner { align-items: flex-start; flex-direction: column; }
        }
    </style>
<?php render_jost_weight_cap_styles(); ?>
</head>
<body>
<?php if ($admin): ?>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="index.php">
                <span class="brand-tag">Admin</span>
                <span class="brand-name">Gabriela Pita</span>
            </a>
            <nav class="nav">
                <a href="settings.php">Dados</a>
                <a href="seo.php">SEO</a>
                <a href="email.php">E-mail</a>
                <a href="posts.php">Blog</a>
                <a href="../index.php" target="_blank">Ver site</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
    </header>
<?php endif; ?>

