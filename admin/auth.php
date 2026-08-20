<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

function admin_logged_in(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function require_admin(): void
{
    if (!admin_logged_in()) {
        redirect('login.php');
    }
}

function admin_layout_start(string $title, string $active = ''): void
{
    $flash = flash_get();
    ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> | ROSE VIP Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../<?= e(asset_url('assets/css/admin.css')) ?>">
    <link rel="icon" href="../assets/img/logo-transparent.png" type="image/png">
</head>
<body class="admin-body">
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="../assets/img/logo-transparent.png" alt="ROSE VIP">
        <div>
            <strong>ROSE VIP</strong>
            <span>لوحة التحكم</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a class="<?= $active === 'dashboard' ? 'active' : '' ?>" href="index.php">الرئيسية</a>
        <a class="<?= $active === 'banner' ? 'active' : '' ?>" href="banner.php">البنر</a>
        <a class="<?= $active === 'gallery' ? 'active' : '' ?>" href="gallery.php">أعمالنا</a>
        <a class="<?= $active === 'contact' ? 'active' : '' ?>" href="contact.php">قنوات التواصل</a>
        <a class="<?= $active === 'social' ? 'active' : '' ?>" href="social.php">السوشال ميديا</a>
        <a class="<?= $active === 'quotes' ? 'active' : '' ?>" href="quotes.php">طلبات الأسعار</a>
        <a href="../index.php" target="_blank" rel="noopener">عرض الموقع</a>
        <a href="logout.php">تسجيل الخروج</a>
    </nav>
</aside>
<main class="admin-main">
    <header class="admin-top">
        <h1><?= e($title) ?></h1>
    </header>
    <div class="admin-content">
    <?php if ($flash): ?>
        <div class="admin-alert admin-alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>
    <?php
}

function admin_layout_end(): void
{
    ?>
    </div>
</main>
</body>
</html>
    <?php
}
