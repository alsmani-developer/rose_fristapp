<?php

declare(strict_types=1);

/**
 * Serve uploaded banner images when Apache denies /data/ parent access.
 * Usage: /serve-upload.php?f=filename.jpg
 */
require_once __DIR__ . '/includes/helpers.php';

$filename = basename((string) ($_GET['f'] ?? ''));
if ($filename === '' || preg_match('/[^a-zA-Z0-9._-]/', $filename)) {
    http_response_code(404);
    exit('Not found');
}

$path = data_path('uploads/' . $filename);
if (!is_file($path)) {
    http_response_code(404);
    exit('Not found');
}

$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$types = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'webp' => 'image/webp',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
];

if (!isset($types[$ext])) {
    http_response_code(403);
    exit('Forbidden');
}

header('Content-Type: ' . $types[$ext]);
header('Content-Length: ' . (string) filesize($path));
header('Cache-Control: public, max-age=86400');
readfile($path);
exit;
