<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (string) $_POST['delete_id'];
    $quotes = load_quotes();
    $quotes = array_values(array_filter($quotes, static function ($quote) use ($deleteId) {
        return ($quote['id'] ?? '') !== $deleteId;
    }));

    if (save_quotes($quotes)) {
        flash_set('success', 'تم حذف الطلب');
    } else {
        flash_set('error', 'تعذر حذف الطلب');
    }

    redirect('quotes.php');
}

$quotes = array_reverse(load_quotes());
admin_layout_start('طلبات عروض الأسعار', 'quotes');
?>
<div class="panel">
    <?php if (!$quotes): ?>
        <p class="empty">لا توجد طلبات حالياً.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>الجوال</th>
                    <th>نوع المركبة</th>
                    <th>طريقة النقل</th>
                    <th>من</th>
                    <th>إلى</th>
                    <th>الموعد</th>
                    <th>وقت الطلب</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($quotes as $quote): ?>
                    <tr>
                        <td><?= e($quote['phone'] ?? '') ?></td>
                        <td><?= e($quote['vehicle_type'] ?? '') ?></td>
                        <td><?= e($quote['transport_method'] ?? '') ?></td>
                        <td><?= e($quote['from_city'] ?? '') ?></td>
                        <td><?= e($quote['to_city'] ?? '') ?></td>
                        <td><?= e($quote['transport_date'] ?? '') ?></td>
                        <td><?= e(isset($quote['created_at']) ? date('Y-m-d H:i', strtotime($quote['created_at'])) : '') ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('هل تريد حذف هذا الطلب؟');">
                                <input type="hidden" name="delete_id" value="<?= e($quote['id'] ?? '') ?>">
                                <button class="btn-danger" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php
admin_layout_end();
