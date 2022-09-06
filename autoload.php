<?php

spl_autoload_register(static function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/src/';

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
