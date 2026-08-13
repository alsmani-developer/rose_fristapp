<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_admin();

$config = load_config();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config['contact']['phone'] = trim((string) ($_POST['phone'] ?? ''));
    $config['contact']['whatsapp'] = trim((string) ($_POST['whatsapp'] ?? ''));
    $config['contact']['email'] = trim((string) ($_POST['email'] ?? ''));
    $config['contact']['ar']['address'] = trim((string) ($_POST['ar_address'] ?? ''));
    $config['contact']['ar']['cta_banner_text'] = trim((string) ($_POST['ar_cta_banner_text'] ?? ''));
    $config['contact']['en']['address'] = trim((string) ($_POST['en_address'] ?? ''));
    $config['contact']['en']['cta_banner_text'] = trim((string) ($_POST['en_cta_banner_text'] ?? ''));

    if ($config['contact']['phone'] === '' || $config['contact']['whatsapp'] === '' || $config['contact']['email'] === '') {
        flash_set('error', 'الهاتف والواتساب والبريد مطلوبة');
        redirect('contact.php');
    }

    if (!filter_var($config['contact']['email'], FILTER_VALIDATE_EMAIL)) {
        flash_set('error', 'البريد الإلكتروني غير صحيح');
        redirect('contact.php');
    }

    if (save_config($config)) {
        flash_set('success', 'تم تحديث قنوات التواصل');
    } else {
        flash_set('error', 'تعذر الحفظ');
    }

    redirect('contact.php');
}

$contact = $config['contact'];
$ar = $contact['ar'] ?? [];
$en = $contact['en'] ?? [];
admin_layout_start('قنوات التواصل', 'contact');
?>
<form class="admin-form admin-form-wide" method="post">
    <div class="form-row">
        <label for="phone">رقم الهاتف</label>
        <input type="text" id="phone" name="phone" value="<?= e($contact['phone'] ?? '') ?>" required>
    </div>
    <div class="form-row">
        <label for="whatsapp">رقم واتساب</label>
        <input type="text" id="whatsapp" name="whatsapp" value="<?= e($contact['whatsapp'] ?? '') ?>" required>
    </div>
    <div class="form-row">
        <label for="email">البريد الإلكتروني</label>
        <input type="email" id="email" name="email" value="<?= e($contact['email'] ?? '') ?>" required>
    </div>

    <div class="lang-panels">
        <div class="lang-panel">
            <h3>العربية</h3>
            <div class="form-row">
                <label for="ar_address">العنوان</label>
                <input type="text" id="ar_address" name="ar_address" value="<?= e($ar['address'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="ar_cta_banner_text">نص بانر التواصل</label>
                <textarea id="ar_cta_banner_text" name="ar_cta_banner_text" rows="3" required><?= e($ar['cta_banner_text'] ?? '') ?></textarea>
                <small>افصل العنوان عن الوصف بشرطة —</small>
            </div>
        </div>
        <div class="lang-panel">
            <h3>English</h3>
            <div class="form-row">
                <label for="en_address">Address</label>
                <input type="text" id="en_address" name="en_address" value="<?= e($en['address'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="en_cta_banner_text">CTA banner text</label>
                <textarea id="en_cta_banner_text" name="en_cta_banner_text" rows="3" required><?= e($en['cta_banner_text'] ?? '') ?></textarea>
                <small>Separate title and subtitle with —</small>
            </div>
        </div>
    </div>

    <button type="submit">حفظ التغييرات</button>
</form>
<?php
admin_layout_end();
