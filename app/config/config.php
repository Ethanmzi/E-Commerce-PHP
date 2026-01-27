<?php
/**
 * Configuration principale.
 *
 * Pour un usage local, tu peux créer `app/config/config.local.php`
 * (ignoré par git) pour surcharger les valeurs.
 */

return [
    'app' => [
        'name' => 'PHP E-commerce',
        'base_url' => '',
    ],
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'php_ecommerce',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'security' => [
        'csrf_key' => 'change-me-in-production',
    ],
];
