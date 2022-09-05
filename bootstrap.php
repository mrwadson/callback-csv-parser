<?php

use App\Csv;

$config = require_once __DIR__ . '/config.php';

/** @todo add to class Bootstrap */
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/src/';

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

$parser = new Csv();

try {
    /** @var callable $rowFn */
    /** @var callable $headFn */
    $parser->open($config['input'])
        ->parse($rowFn)
        ->put($config['result'], $headFn);
} catch (Exception $e) {
    die($e->getMessage());
}
