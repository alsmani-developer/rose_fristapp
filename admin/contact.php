<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_admin();

$config = load_config();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config['contact']['phone'] = trim((string) ($_POST['phone'] ?? ''));
    $config['contact']['whatsapp'] = trim((string) ($_POST['whatsapp'] ?? ''));
    $config['contact']['email'] = trim((string) ($_POST['email'] ?? ''));
    $config['notification_email'] = trim((string) ($_POST['notification_email'] ?? ''));
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

    if ($config['notification_email'] === '' || !filter_var($config['notification_email'], FILTER_VALIDATE_EMAIL)) {
        flash_set('error', 'بريد استلام إشعارات الطلبات غير صحيح');
        redirect('contact.php');
    }

    if (!empty($_POST['remove_cta_image'])) {
        $old = (string) ($config['contact']['cta_image'] ?? '');
        if ($old !== '') {
            $oldPath = data_path('uploads/' . basename($old));
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }
        $config['contact']['cta_image'] = '';
    }

    if (!empty($_FILES['cta_image']['name']) && ($_FILES['cta_image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $tmp = (string) $_FILES['cta_image']['tmp_name'];
        $size = (int) ($_FILES['cta_image']['size'] ?? 0);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmp) ?: '';
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        if ($size > 5 * 1024 * 1024) {
            flash_set('error', 'حجم صورة الفوتر يجب ألا يتجاوز 5MB');
            redirect('contact.php');
        }

        if (!isset($allowed[$mime])) {
            flash_set('error', 'صيغة صورة الفوتر غير مدعومة');
            redirect('contact.php');
        }

        $filename = 'cta_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
        $destDir = data_path('uploads');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        $dest = $destDir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($tmp, $dest)) {
            flash_set('error', 'تعذر رفع صورة الفوتر');
            redirect('contact.php');
        }

        $old = (string) ($config['contact']['cta_image'] ?? '');
        if ($old !== '' && $old !== $filename) {
            $oldPath = data_path('uploads/' . basename($old));
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        $config['contact']['cta_image'] = $filename;
    }

    if (save_config($config)) {
        flash_set('success', 'تم تحديث قنوات التواصل وصورة الفوتر');
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
<form class="admin-form admin-form-wide" method="post" enctype="multipart/form-data">
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
    <div class="form-row">
        <label for="notification_email">بريد استلام إشعارات الطلبات</label>
        <input type="email" id="notification_email" name="notification_email" value="<?= e($config['notification_email'] ?? '') ?>" required>
        <small>يصلك بريد تلقائي كل مرة يرسل فيها عميل طلب عرض سعر</small>
    </div>

    <div class="form-row">
        <label for="cta_image">صورة بانر الفوتر / التواصل</label>
        <div class="preview">
            <img src="../<?= e(cta_image_url($contact['cta_image'] ?? '')) ?>" alt="صورة الفوتر الحالية">
            <?php if (!empty($contact['cta_image'])): ?>
                <label class="checkbox">
                    <input type="checkbox" name="remove_cta_image" value="1">
                    حذف الصورة الحالية واستخدام الافتراضية
                </label>
            <?php endif; ?>
        </div>
        <input type="file" id="cta_image" name="cta_image" accept="image/jpeg,image/png,image/webp,image/gif">
        <small>المقاس المقترح: 1920×700 — JPG/PNG/WEBP بحد أقصى 5MB</small>
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
