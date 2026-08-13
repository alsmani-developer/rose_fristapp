<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_admin();

$gallery = load_gallery();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'add') {
        if (empty($_FILES['image']['name']) || ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            flash_set('error', 'يرجى اختيار صورة للرفع');
            redirect('gallery.php');
        }

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
            redirect('gallery.php');
        }

        if (!isset($allowed[$mime])) {
            flash_set('error', 'صيغة الصورة غير مدعومة');
            redirect('gallery.php');
        }

        $filename = 'gallery_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
        $destDir = data_path('uploads');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        if (!move_uploaded_file($tmp, $destDir . DIRECTORY_SEPARATOR . $filename)) {
            flash_set('error', 'تعذر رفع الصورة');
            redirect('gallery.php');
        }

        $maxSort = 0;
        foreach ($gallery as $item) {
            $maxSort = max($maxSort, (int) ($item['sort'] ?? 0));
        }

        $gallery[] = [
            'id' => bin2hex(random_bytes(8)),
            'image' => $filename,
            'caption_ar' => trim((string) ($_POST['caption_ar'] ?? '')),
            'caption_en' => trim((string) ($_POST['caption_en'] ?? '')),
            'sort' => $maxSort + 1,
            'created_at' => date('c'),
        ];

        if (save_gallery($gallery)) {
            flash_set('success', 'تمت إضافة الصورة بنجاح');
        } else {
            flash_set('error', 'تعذر حفظ المعرض');
        }

        redirect('gallery.php');
    }

    if ($action === 'update') {
        $id = (string) ($_POST['id'] ?? '');
        $found = false;
        foreach ($gallery as &$item) {
            if (($item['id'] ?? '') !== $id) {
                continue;
            }
            $item['caption_ar'] = trim((string) ($_POST['caption_ar'] ?? ''));
            $item['caption_en'] = trim((string) ($_POST['caption_en'] ?? ''));
            $item['sort'] = (int) ($_POST['sort'] ?? ($item['sort'] ?? 0));
            $found = true;
            break;
        }
        unset($item);

        if (!$found) {
            flash_set('error', 'الصورة غير موجودة');
            redirect('gallery.php');
        }

        if (save_gallery($gallery)) {
            flash_set('success', 'تم تحديث الصورة');
        } else {
            flash_set('error', 'تعذر الحفظ');
        }

        redirect('gallery.php');
    }

    if ($action === 'delete') {
        $id = (string) ($_POST['id'] ?? '');
        $newGallery = [];
        foreach ($gallery as $item) {
            if (($item['id'] ?? '') === $id) {
                $image = (string) ($item['image'] ?? '');
                if ($image !== '') {
                    $path = data_path('uploads/' . basename($image));
                    if (is_file($path)) {
                        unlink($path);
                    }
                }
                continue;
            }
            $newGallery[] = $item;
        }

        if (save_gallery($newGallery)) {
            flash_set('success', 'تم حذف الصورة');
        } else {
            flash_set('error', 'تعذر الحذف');
        }

        redirect('gallery.php');
    }

    flash_set('error', 'إجراء غير معروف');
    redirect('gallery.php');
}

$gallery = load_gallery();
admin_layout_start('أعمالنا / معرض الصور', 'gallery');
?>
<form class="admin-form admin-form-wide" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">
    <h3 style="margin-bottom:1rem;">إضافة صورة جديدة</h3>
    <div class="form-row">
        <label for="image">الصورة</label>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif" required>
        <small>JPG/PNG/WEBP بحد أقصى 5MB — المقاس المقترح 1200×900</small>
    </div>
    <div class="lang-panels">
        <div class="lang-panel">
            <h3>وصف عربي (اختياري)</h3>
            <div class="form-row">
                <input type="text" name="caption_ar" placeholder="مثال: نقل سيارة فاخرة من الرياض إلى جدة">
            </div>
        </div>
        <div class="lang-panel">
            <h3>English caption (optional)</h3>
            <div class="form-row">
                <input type="text" name="caption_en" placeholder="e.g. Luxury car transport Riyadh to Jeddah">
            </div>
        </div>
    </div>
    <button type="submit">إضافة إلى المعرض</button>
</form>

<div class="panel" style="margin-top:1.25rem;">
    <div class="panel-head">
        <h2>الصور الحالية (<?= count($gallery) ?>)</h2>
    </div>

    <?php if (!$gallery): ?>
        <p class="empty">لا توجد صور بعد. أضف أول صورة من النموذج أعلاه.</p>
    <?php else: ?>
        <div class="gallery-admin-grid">
            <?php foreach ($gallery as $item): ?>
                <article class="gallery-admin-card">
                    <img src="../<?= e(public_upload_url($item['image'] ?? '')) ?>" alt="">
                    <form method="post">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= e($item['id'] ?? '') ?>">
                        <div class="form-row">
                            <label>الوصف عربي</label>
                            <input type="text" name="caption_ar" value="<?= e($item['caption_ar'] ?? '') ?>">
                        </div>
                        <div class="form-row">
                            <label>English caption</label>
                            <input type="text" name="caption_en" value="<?= e($item['caption_en'] ?? '') ?>">
                        </div>
                        <div class="form-row">
                            <label>الترتيب</label>
                            <input type="number" name="sort" value="<?= e((string) ($item['sort'] ?? 0)) ?>">
                        </div>
                        <div class="gallery-admin-actions">
                            <button type="submit">حفظ</button>
                        </div>
                    </form>
                    <form method="post" onsubmit="return confirm('حذف هذه الصورة؟');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= e($item['id'] ?? '') ?>">
                        <button class="btn-danger" type="submit">حذف</button>
                    </form>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php
admin_layout_end();
