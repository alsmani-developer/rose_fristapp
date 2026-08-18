<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$messages = [
    'ar' => [
        'method' => 'طريقة الطلب غير مسموحة',
        'vehicle' => 'نوع المركبة مطلوب',
        'transport' => 'طريقة النقل مطلوبة',
        'from' => 'مدينة الاستلام مطلوبة',
        'to' => 'مدينة التسليم مطلوبة',
        'same' => 'مدينة الاستلام والتسليم يجب أن تكونا مختلفتين',
        'phone' => 'رقم الجوال غير صحيح',
        'date' => 'موعد النقل غير صحيح',
        'date_past' => 'موعد النقل يجب أن يكون اليوم أو بعده',
        'save_fail' => 'تعذر حفظ الطلب، حاول لاحقاً',
        'success' => 'تم استلام طلبك بنجاح، سنتواصل معك قريباً',
    ],
    'en' => [
        'method' => 'Method not allowed',
        'vehicle' => 'Vehicle type is required',
        'transport' => 'Transport method is required',
        'from' => 'Pickup city is required',
        'to' => 'Delivery city is required',
        'same' => 'Pickup and delivery cities must be different',
        'phone' => 'Invalid mobile number',
        'date' => 'Invalid transport date',
        'date_past' => 'Transport date must be today or later',
        'save_fail' => 'Could not save the request, please try again later',
        'success' => 'Your request was received successfully. We will contact you soon',
    ],
];

$lang = current_lang();
$msg = $messages[$lang] ?? $messages['ar'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => $msg['method']], JSON_UNESCAPED_UNICODE);
    exit;
}

$vehicleType = trim((string) ($_POST['vehicle_type'] ?? ''));
$transportMethod = trim((string) ($_POST['transport_method'] ?? ''));
$fromCity = trim((string) ($_POST['from_city'] ?? ''));
$toCity = trim((string) ($_POST['to_city'] ?? ''));
$phone = trim((string) ($_POST['phone'] ?? ''));
$transportDate = trim((string) ($_POST['transport_date'] ?? ''));

$errors = [];

if ($vehicleType === '') {
    $errors[] = $msg['vehicle'];
}
if ($transportMethod === '') {
    $errors[] = $msg['transport'];
}
if ($fromCity === '') {
    $errors[] = $msg['from'];
}
if ($toCity === '') {
    $errors[] = $msg['to'];
}
if ($fromCity !== '' && $toCity !== '' && $fromCity === $toCity) {
    $errors[] = $msg['same'];
}
if (!preg_match('/^05[0-9]{8}$/', $phone)) {
    $errors[] = $msg['phone'];
}
if ($transportDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $transportDate)) {
    $errors[] = $msg['date'];
} else {
    $today = new DateTimeImmutable('today');
    $selected = DateTimeImmutable::createFromFormat('Y-m-d', $transportDate);
    if (!$selected || $selected < $today) {
        $errors[] = $msg['date_past'];
    }
}

if ($errors) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => $errors[0]], JSON_UNESCAPED_UNICODE);
    exit;
}

$quotes = load_quotes();
$quotes[] = [
    'id' => bin2hex(random_bytes(8)),
    'vehicle_type' => $vehicleType,
    'transport_method' => $transportMethod,
    'from_city' => $fromCity,
    'to_city' => $toCity,
    'phone' => $phone,
    'transport_date' => $transportDate,
    'lang' => $lang,
    'created_at' => date('c'),
];

if (!save_quotes($quotes)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => $msg['save_fail']], JSON_UNESCAPED_UNICODE);
    exit;
}

notify_new_quote(end($quotes), load_config());

echo json_encode(['ok' => true, 'message' => $msg['success']], JSON_UNESCAPED_UNICODE);
