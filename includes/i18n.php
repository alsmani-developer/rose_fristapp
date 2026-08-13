<?php

declare(strict_types=1);

function current_lang(): string
{
    return $_SESSION['lang'] ?? 'ar';
}

function lang_dir(): string
{
    return current_lang() === 'en' ? 'ltr' : 'rtl';
}

function lang_attr(): string
{
    return current_lang();
}

function is_rtl(): bool
{
    return current_lang() !== 'en';
}

function init_language(): void
{
    if (isset($_GET['lang'])) {
        $lang = strtolower((string) $_GET['lang']);
        if (in_array($lang, ['ar', 'en'], true)) {
            $_SESSION['lang'] = $lang;
        }
    }

    if (!isset($_SESSION['lang'])) {
        $_SESSION['lang'] = 'ar';
    }
}

function translations(): array
{
    static $cache = [];
    $lang = current_lang();
    if (isset($cache[$lang])) {
        return $cache[$lang];
    }

    $file = __DIR__ . '/../lang/' . $lang . '.php';
    $data = is_file($file) ? require $file : [];
    $cache[$lang] = is_array($data) ? $data : [];
    return $cache[$lang];
}

function __(string $key): string
{
    $all = translations();
    return (string) ($all[$key] ?? $key);
}

function switch_lang_url(string $lang): string
{
    $params = $_GET;
    $params['lang'] = $lang;
    $query = http_build_query($params);
    $path = strtok((string) ($_SERVER['REQUEST_URI'] ?? '/'), '?') ?: '/';
    return $path . ($query !== '' ? '?' . $query : '');
}

function localized(array $section, string $key, string $fallback = ''): string
{
    $lang = current_lang();
    if (isset($section[$lang]) && is_array($section[$lang]) && array_key_exists($key, $section[$lang])) {
        return (string) $section[$lang][$key];
    }
    if (isset($section['ar']) && is_array($section['ar']) && array_key_exists($key, $section['ar'])) {
        return (string) $section['ar'][$key];
    }
    if (array_key_exists($key, $section)) {
        return (string) $section[$key];
    }
    return $fallback;
}
