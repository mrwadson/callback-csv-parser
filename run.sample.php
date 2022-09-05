<?php

use App\Csv;

$rowFn = function ($row, Csv $obj)  {
    // $obj - current CSV file
    return $row;
};

$headFn = function () {
    return ['head1', 'head2', 'head3'];
};

require_once __DIR__ . '/bootstrap.php';
