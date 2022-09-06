<?php

use App\Csv;
use App\Process;

require_once __DIR__ . '/autoload.php';

Process::init()
    ->setHeadCallback(static function () {
        return ['head1', 'head2', 'head3'];
    })->setRowCallback(static function (array $row, Csv $obj) {
        // $obj - current CSV file
        return $row;
    })();
