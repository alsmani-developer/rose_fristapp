<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_admin();

$config = load_config();
$quotes = load_quotes();
$count = count($quotes);
$latest = array_slice(array_reverse($quotes), 0, 5);

admin_layout_start('لوحة التحكم', 'dashboard');
?>
<div class="stats-grid">
    <div class="stat-card">
        <span>طلبات عروض الأسعار</span>
        <strong><?= $count ?></strong>
    </div>
    <div class="stat-card">
        <span>الهاتف الحالي</span>
        <strong class="stat-text"><?= e($config['contact']['phone']) ?></strong>
    </div>
    <div class="stat-card">
        <span>واتساب</span>
        <strong class="stat-text"><?= e($config['contact']['whatsapp']) ?></strong>
    </div>
</div>

<div class="panel">
    <div class="panel-head">
        <h2>أحدث الطلبات</h2>
        <a href="quotes.php">عرض الكل</a>
    </div>
    <?php if (!$latest): ?>
        <p class="empty">لا توجد طلبات بعد.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>الجوال</th>
                    <th>من</th>
                    <th>إلى</th>
                    <th>الموعد</th>
                    <th>التاريخ</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($latest as $quote): ?>
                    <tr>
                        <td><?= e($quote['phone'] ?? '') ?></td>
                        <td><?= e($quote['from_city'] ?? '') ?></td>
                        <td><?= e($quote['to_city'] ?? '') ?></td>
                        <td><?= e($quote['transport_date'] ?? '') ?></td>
                        <td><?= e(isset($quote['created_at']) ? date('Y-m-d H:i', strtotime($quote['created_at'])) : '') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="quick-links">
    <a href="banner.php">تعديل البنر</a>
    <a href="gallery.php">أعمالنا</a>
    <a href="contact.php">قنوات التواصل</a>
    <a href="social.php">السوشال ميديا</a>
    <a href="quotes.php">إدارة الطلبات</a>
</div>
<?php
admin_layout_end();
