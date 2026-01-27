<?php

declare(strict_types=1);

date_default_timezone_set('Europe/Paris');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$config = require __DIR__ . '/config.php';
$localConfigPath = __DIR__ . '/config.local.php';
if (is_file($localConfigPath)) {
    $local = require $localConfigPath;
    $config = array_replace_recursive($config, $local);
}

require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/flash.php';
require_once __DIR__ . '/../lib/csrf.php';
require_once __DIR__ . '/../lib/validation.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/database.php';
