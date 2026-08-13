<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/i18n.php';

init_language();

$config = load_config();
