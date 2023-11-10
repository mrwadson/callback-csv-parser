<?php

use mrwadson\Csv;
use mrwadson\Filter;
use mrwadson\Process;

require_once __DIR__ . '/autoload.php';

$config = [
    'input' => __DIR__ . '/data/input.csv',
    'result' => __DIR__ . '/data/result.csv',
    'first_row_is_header' => true,
    'include_header_in_result' => false
];

Process::init($config)
    ->setHeadCallback(static function () {
        return ['head1', 'head2', 'head3'];
    })->setRowCallback(static function (array $row, CSV $csv) {
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
