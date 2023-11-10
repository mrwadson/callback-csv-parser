## Callback CSV Parser

Parsing CSV file (using callbacks) into new CSV file with changed data and new (updated) header.

## Requirements

- PHP >= 7.1

## Usage

1. Copy `parse.example.php` to `parse.php`:
    ```shell
    cp parse.example.php parse.php # and edit script parse.php for your purpose
    ```
2. In `parse.php` can be used three callback functions (see `parse.sample.php`) for parsing CSV file:
   - Every row (`rowCallback`)
   - Header (`headCallback`)
   - Whole result (`resultCallback`)
3. Run php script:
    ```shell
    php parse.php # generate new result.csv file
    ```
Will generate new CSV file: `data/result.csv` file from the input file `data/input.csv`, 
where `input.csv` and `result.csv` - csv filenames (set in the configuration array `$config`).

## Parsing result

To get the result of parsing a CSV file do not include or just comment in the config `result` param:

```php
<?php

use App\Process;

require_once __DIR__ . '/autoload.php';

$config = [
    'input' => __DIR__ . '/data/input.csv',
    // 'result' => __DIR__ . '/data/result.csv',
    'first_row_is_header' => true, # parse first row as header
    'include_header_in_result' => false # don't include header row in result
];

$result = Process::init($config)();

print_r($result);
```

In this case, the result file will not be written.

## Parsers files

For every new project just copy `parse.example.php` to `parse-my-project.php` and use it for your purpose.

```shell

cp parse.example.php parse-my-project.php 
mkdir data/my-project # create dir for the config files (see section bellow)
```

And just run your parse php file:

```shell
php parse-my-project.php # will process input file from the $config['input']
```

Where is `my-project` - custom name of the project.

Or (for automatize) use prompted shell script:

```shell
read -p "Enter project name: " project_name && \
cp parse.example.php parse-$project_name.php && mkdir data/$project_name && \
sed -i "s#/data/#/data/$project_name/#" parse-$project_name.php
```

## Config

Configuration parameters are set in the array (in the `$config` variable) directly in the `parse.php` file. 

There are:
- `input` - CSV file for parsing
- `result` - output result CSV file after parsing
- `first_row_is_header` - the first line is the header
- `include_header_in_result` - include/exclude the header in the result CSV file (only if `first_row_is_header` is `true`)

If you're working with multiple files, it's best to create a directory inside in the `data` directory. So your input and result files
will be located by example at `data/my-project/input.csv` and `data/my-project/result.csv` files.
