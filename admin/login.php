<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

if (admin_logged_in()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string) ($_POST['password'] ?? '');
    $config = load_config();

    if (hash_equals((string) $config['admin_password'], $password)) {
        $_SESSION['admin_logged_in'] = true;
        redirect('index.php');
    }

    $error = 'كلمة المرور غير صحيحة';
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | ROSE VIP Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="icon" href="../assets/img/logo-transparent.png" type="image/png">
</head>
<body class="login-body">
    <form class="login-card" method="post" action="">
        <img src="../assets/img/logo-transparent.png" alt="ROSE VIP">
        <h1>لوحة تحكم ROSE VIP</h1>
        <p>أدخل كلمة المرور للمتابعة</p>
        <?php if ($error): ?>
            <div class="admin-alert admin-alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <label for="password">كلمة المرور</label>
        <input type="password" id="password" name="password" required autofocus>
        <button type="submit">دخول</button>
    </form>
</body>
</html>
