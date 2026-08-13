<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_admin();

$config = load_config();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config['social']['instagram'] = trim((string) ($_POST['instagram'] ?? ''));
    $config['social']['x'] = trim((string) ($_POST['x'] ?? ''));
    $config['social']['snapchat'] = trim((string) ($_POST['snapchat'] ?? ''));
    $config['social']['tiktok'] = trim((string) ($_POST['tiktok'] ?? ''));

    foreach (['instagram', 'x', 'snapchat', 'tiktok'] as $key) {
        $url = $config['social'][$key];
        if ($url !== '' && !filter_var($url, FILTER_VALIDATE_URL)) {
            flash_set('error', 'رابط غير صحيح في أحد الحقول');
            redirect('social.php');
        }
    }

    if (save_config($config)) {
        flash_set('success', 'تم تحديث حسابات السوشال ميديا');
    } else {
        flash_set('error', 'تعذر الحفظ');
    }

    redirect('social.php');
}

$social = $config['social'];
admin_layout_start('حسابات السوشال ميديا', 'social');
?>
<form class="admin-form" method="post">
    <div class="form-row">
        <label for="instagram">Instagram</label>
        <input type="url" id="instagram" name="instagram" value="<?= e($social['instagram']) ?>" placeholder="https://instagram.com/...">
    </div>
    <div class="form-row">
        <label for="x">X (Twitter)</label>
        <input type="url" id="x" name="x" value="<?= e($social['x']) ?>" placeholder="https://x.com/...">
    </div>
    <div class="form-row">
        <label for="snapchat">Snapchat</label>
        <input type="url" id="snapchat" name="snapchat" value="<?= e($social['snapchat']) ?>" placeholder="https://snapchat.com/...">
    </div>
    <div class="form-row">
        <label for="tiktok">TikTok</label>
        <input type="url" id="tiktok" name="tiktok" value="<?= e($social['tiktok']) ?>" placeholder="https://tiktok.com/...">
    </div>
    <button type="submit">حفظ التغييرات</button>
</form>
<?php
admin_layout_end();
