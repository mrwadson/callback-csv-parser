<?php

use App\Filter;
use App\Process;

require_once __DIR__ . '/autoload.php';

Process::init()
    ->setHeadCallback(static function () {
        return ['head1', 'head2', 'head3'];
    })->setRowCallback(static function (array $row) {
        foreach ($row as $item) {
            if (Filter::include(['some', 'bad', 'words'], $item)) {
                return null;
            }
        }
        return $row;
    })->setResultCallback(static function (array $rows) {
        ksort($rows);
        return $rows;
    })();
