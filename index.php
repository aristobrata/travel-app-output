<?php

define('BASE_PATH', __DIR__);

$_docRoot  = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
$_appPath  = rtrim(str_replace('\\', '/', __DIR__), '/');
$_subfolder = str_replace($_docRoot, '', $_appPath);
define('SUBFOLDER', $_subfolder ?: '');

// Config
require BASE_PATH . '/config/app.php';
date_default_timezone_set('Asia/Jakarta');

$appConfig = require BASE_PATH . '/config/app.php';
if ($appConfig['debug'] ?? false) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Autoloader PSR-4
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) return;
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = BASE_PATH . '/app/' . $relative . '.php';
    if (file_exists($file)) require $file;
});

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

define('APP_CONFIG_LOADED', true);

require BASE_PATH . '/public/index.php';
