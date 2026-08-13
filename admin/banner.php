<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_admin();

$config = load_config();
$textKeys = ['title', 'title_highlight', 'subtitle', 'feature_1', 'feature_2', 'feature_3', 'cta_text'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['ar', 'en'] as $lang) {
        if (!isset($config['banner'][$lang]) || !is_array($config['banner'][$lang])) {
            $config['banner'][$lang] = [];
        }
        foreach ($textKeys as $key) {
            $config['banner'][$lang][$key] = trim((string) ($_POST[$lang . '_' . $key] ?? ''));
        }
    }

    if (!empty($_POST['remove_image'])) {
        $old = (string) ($config['banner']['image'] ?? '');
        if ($old !== '') {
            $oldPath = data_path('uploads/' . basename($old));
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }
        $config['banner']['image'] = '';
    }

    if (!empty($_FILES['image']['name']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        $tmp = (string) $_FILES['image']['tmp_name'];
        $size = (int) ($_FILES['image']['size'] ?? 0);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmp) ?: '';
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        if ($size > 5 * 1024 * 1024) {
            flash_set('error', 'حجم الصورة يجب ألا يتجاوز 5MB');
            redirect('banner.php');
        }

        if (!isset($allowed[$mime])) {
            flash_set('error', 'صيغة الصورة غير مدعومة');
            redirect('banner.php');
        }

        $filename = 'banner_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
        $destDir = data_path('uploads');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        $dest = $destDir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($tmp, $dest)) {
            flash_set('error', 'تعذر رفع الصورة');
            redirect('banner.php');
        }

        $old = (string) ($config['banner']['image'] ?? '');
        if ($old !== '' && $old !== $filename) {
            $oldPath = data_path('uploads/' . basename($old));
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        $config['banner']['image'] = $filename;
    }

    if (save_config($config)) {
        flash_set('success', 'تم حفظ إعدادات البنر (عربي / إنجليزي) بنجاح');
    } else {
        flash_set('error', 'تعذر حفظ الإعدادات');
    }

    redirect('banner.php');
}

$banner = $config['banner'];
$ar = $banner['ar'] ?? [];
$en = $banner['en'] ?? [];
admin_layout_start('إدارة البنر', 'banner');
?>
<form class="admin-form admin-form-wide" method="post" enctype="multipart/form-data">
    <div class="lang-panels">
        <div class="lang-panel">
            <h3>العربية</h3>
            <div class="form-row">
                <label for="ar_title">العنوان الرئيسي</label>
                <input type="text" id="ar_title" name="ar_title" value="<?= e($ar['title'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="ar_title_highlight">العنوان المميز</label>
                <input type="text" id="ar_title_highlight" name="ar_title_highlight" value="<?= e($ar['title_highlight'] ?? '') ?>">
            </div>
            <div class="form-row">
                <label for="ar_subtitle">الوصف الفرعي</label>
                <input type="text" id="ar_subtitle" name="ar_subtitle" value="<?= e($ar['subtitle'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="ar_feature_1">الميزة 1</label>
                <input type="text" id="ar_feature_1" name="ar_feature_1" value="<?= e($ar['feature_1'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="ar_feature_2">الميزة 2</label>
                <input type="text" id="ar_feature_2" name="ar_feature_2" value="<?= e($ar['feature_2'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="ar_feature_3">الميزة 3</label>
                <input type="text" id="ar_feature_3" name="ar_feature_3" value="<?= e($ar['feature_3'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="ar_cta_text">نص زر الدعوة</label>
                <input type="text" id="ar_cta_text" name="ar_cta_text" value="<?= e($ar['cta_text'] ?? '') ?>" required>
            </div>
        </div>

        <div class="lang-panel">
            <h3>English</h3>
            <div class="form-row">
                <label for="en_title">Main title</label>
                <input type="text" id="en_title" name="en_title" value="<?= e($en['title'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="en_title_highlight">Highlight title</label>
                <input type="text" id="en_title_highlight" name="en_title_highlight" value="<?= e($en['title_highlight'] ?? '') ?>">
            </div>
            <div class="form-row">
                <label for="en_subtitle">Subtitle</label>
                <input type="text" id="en_subtitle" name="en_subtitle" value="<?= e($en['subtitle'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="en_feature_1">Feature 1</label>
                <input type="text" id="en_feature_1" name="en_feature_1" value="<?= e($en['feature_1'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="en_feature_2">Feature 2</label>
                <input type="text" id="en_feature_2" name="en_feature_2" value="<?= e($en['feature_2'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="en_feature_3">Feature 3</label>
                <input type="text" id="en_feature_3" name="en_feature_3" value="<?= e($en['feature_3'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <label for="en_cta_text">CTA button text</label>
                <input type="text" id="en_cta_text" name="en_cta_text" value="<?= e($en['cta_text'] ?? '') ?>" required>
            </div>
        </div>
    </div>

    <div class="form-row">
        <label for="image">صورة البنر (مشتركة للغتين)</label>
        <?php if (!empty($banner['image'])): ?>
            <div class="preview">
                <img src="../<?= e(public_upload_url($banner['image'])) ?>" alt="صورة البنر الحالية">
                <label class="checkbox">
                    <input type="checkbox" name="remove_image" value="1">
                    حذف الصورة الحالية واستخدام الافتراضية
                </label>
            </div>
        <?php endif; ?>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
        <small>اختياري — JPG/PNG/WEBP بحد أقصى 5MB</small>
    </div>
    <button type="submit">حفظ التغييرات</button>
</form>
<?php
admin_layout_end();
