<?php

declare(strict_types=1);

function base_path(string $path = ''): string
{
    $root = dirname(__DIR__);
    return $path === '' ? $root : $root . DIRECTORY_SEPARATOR . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
}

function asset_url(string $relativePath): string
{
    $fullPath = base_path($relativePath);
    $version = is_file($fullPath) ? filemtime($fullPath) : time();
    return $relativePath . '?v=' . $version;
}

function data_path(string $path = ''): string
{
    return base_path('data' . ($path !== '' ? '/' . ltrim($path, '/') : ''));
}

function config_path(): string
{
    return data_path('config.json');
}

function quotes_path(): string
{
    return data_path('quotes.json');
}

function read_json(string $path, $default = [])
{
    if (!is_file($path)) {
        return $default;
    }

    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') {
        return $default;
    }

    $data = json_decode($raw, true);
    return json_last_error() === JSON_ERROR_NONE ? $data : $default;
}

function write_json(string $path, $data): bool
{
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }

    $tmp = $path . '.tmp';
    if (file_put_contents($tmp, $json, LOCK_EX) === false) {
        return false;
    }

    return rename($tmp, $path);
}

function load_config(): array
{
    $config = read_json(config_path(), []);
    $config = normalize_config($config);
    return array_replace_recursive(default_config(), $config);
}

function normalize_config(array $config): array
{
    $textKeys = ['title', 'title_highlight', 'subtitle', 'feature_1', 'feature_2', 'feature_3', 'cta_text'];
    if (isset($config['banner']) && is_array($config['banner'])) {
        $banner = $config['banner'];
        if (!isset($banner['ar']) || !is_array($banner['ar'])) {
            $banner['ar'] = [];
            foreach ($textKeys as $key) {
                if (isset($banner[$key])) {
                    $banner['ar'][$key] = $banner[$key];
                    unset($banner[$key]);
                }
            }
        } else {
            foreach ($textKeys as $key) {
                unset($banner[$key]);
            }
        }
        if (!isset($banner['en']) || !is_array($banner['en'])) {
            $banner['en'] = default_config()['banner']['en'];
        }
        $config['banner'] = $banner;
    }

    if (isset($config['contact']) && is_array($config['contact'])) {
        $contact = $config['contact'];
        foreach (['address', 'cta_banner_text'] as $key) {
            if (isset($contact[$key])) {
                if (!isset($contact['ar']) || !is_array($contact['ar'])) {
                    $contact['ar'] = [];
                }
                $contact['ar'][$key] = $contact[$key];
                unset($contact[$key]);
            }
        }
        if (!isset($contact['en']) || !is_array($contact['en'])) {
            $contact['en'] = default_config()['contact']['en'];
        }
        if (!isset($contact['ar']) || !is_array($contact['ar'])) {
            $contact['ar'] = default_config()['contact']['ar'];
        }
        $config['contact'] = $contact;
    }

    return $config;
}

function default_config(): array
{
    return [
        'admin_password' => 'admin123',
        'notification_email' => 'info@rosevip.sa',
        'banner' => [
            'image' => '',
            'ar' => [
                'title' => 'نقل سيارتك',
                'title_highlight' => 'بكل أمان واحترافية',
                'subtitle' => 'خدمات نقل السيارات داخل المملكة العربية السعودية',
                'feature_1' => 'عناية واهتمام من الاستلام حتى التسليم',
                'feature_2' => 'سرعة في التسليم وفي الوقت المحدد',
                'feature_3' => 'أمان كامل على سيارتك',
                'cta_text' => 'اطلب نقل سيارتك الآن',
            ],
            'en' => [
                'title' => 'Transport your car',
                'title_highlight' => 'with total safety and professionalism',
                'subtitle' => 'Car transport services across the Kingdom of Saudi Arabia',
                'feature_1' => 'Care and attention from pickup to delivery',
                'feature_2' => 'Fast delivery, always on time',
                'feature_3' => 'Complete safety for your car',
                'cta_text' => 'Request your car transport now',
            ],
        ],
        'contact' => [
            'phone' => '0501234567',
            'whatsapp' => '0501234567',
            'email' => 'info@rosevip.sa',
            'cta_image' => '',
            'ar' => [
                'address' => 'المملكة العربية السعودية',
                'cta_banner_text' => 'سيارتك تستحق الأفضل — تواصل معنا الآن واحصل على أفضل خدمة نقل',
            ],
            'en' => [
                'address' => 'Kingdom of Saudi Arabia',
                'cta_banner_text' => 'Your car deserves the best — contact us now for the best transport service',
            ],
        ],
        'social' => [
            'instagram' => 'https://instagram.com/',
            'x' => 'https://x.com/',
            'snapchat' => 'https://snapchat.com/',
            'tiktok' => 'https://tiktok.com/',
        ],
    ];
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash_set(string $type, string $message): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function whatsapp_link(string $number, string $text = ''): string
{
    $digits = preg_replace('/\D+/', '', $number) ?? '';
    if (str_starts_with($digits, '05') && strlen($digits) === 10) {
        $digits = '966' . substr($digits, 1);
    }
    $url = 'https://wa.me/' . $digits;
    if ($text !== '') {
        $url .= '?text=' . rawurlencode($text);
    }
    return $url;
}

function phone_tel(string $number): string
{
    $digits = preg_replace('/\D+/', '', $number) ?? '';
    return 'tel:+' . (str_starts_with($digits, '0') ? '966' . substr($digits, 1) : $digits);
}

function public_upload_url(?string $filename): string
{
    if (!$filename) {
        return 'assets/img/hero-banner.png';
    }
    return 'serve-upload.php?f=' . rawurlencode(basename($filename));
}

function cta_image_url(?string $filename): string
{
    if (!$filename) {
        return 'assets/img/cta-banner.png';
    }
    return 'serve-upload.php?f=' . rawurlencode(basename($filename));
}

function save_config(array $config): bool
{
    return write_json(config_path(), $config);
}

function load_quotes(): array
{
    $quotes = read_json(quotes_path(), []);
    return is_array($quotes) ? $quotes : [];
}

function save_quotes(array $quotes): bool
{
    return write_json(quotes_path(), array_values($quotes));
}

function notify_new_quote(array $quote, array $config): bool
{
    $to = trim((string) ($config['notification_email'] ?? $config['contact']['email'] ?? ''));
    if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $subject = '=?UTF-8?B?' . base64_encode('طلب عرض سعر جديد - Rose VIP') . '?=';

    $vehicleType = (string) ($quote['vehicle_type'] ?? '');
    $transportMethod = (string) ($quote['transport_method'] ?? '');
    $fromCity = (string) ($quote['from_city'] ?? '');
    $toCity = (string) ($quote['to_city'] ?? '');
    $phone = (string) ($quote['phone'] ?? '');
    $transportDate = (string) ($quote['transport_date'] ?? '');
    $createdAt = (string) ($quote['created_at'] ?? '');

    $body = "تفاصيل الطلب الجديد:\n\n"
        . "نوع المركبة: {$vehicleType}\n"
        . "طريقة النقل: {$transportMethod}\n"
        . "من: {$fromCity}\n"
        . "إلى: {$toCity}\n"
        . "رقم الجوال: {$phone}\n"
        . "موعد النقل: {$transportDate}\n"
        . "وقت الإرسال: {$createdAt}\n";

    $headers = "MIME-Version: 1.0\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\n"
        . "From: Rose VIP <no-reply@" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ">\r\n";

    return @mail($to, $subject, $body, $headers);
}

function gallery_path(): string
{
    return data_path('gallery.json');
}

function load_gallery(): array
{
    $items = read_json(gallery_path(), []);
    if (!is_array($items)) {
        return [];
    }

    usort($items, static function ($a, $b) {
        return ((int) ($a['sort'] ?? 0)) <=> ((int) ($b['sort'] ?? 0));
    });

    return array_values($items);
}

function save_gallery(array $items): bool
{
    return write_json(gallery_path(), array_values($items));
}
