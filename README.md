## Template CSV Parser

Parsing CSV file into new CSV file with changed data and new header.

## Required

- PHP >= 7.4

## Usage

1. Copy `config.sample.php` to `config.php`:
    ```shell
    cp config.sample.php config.php
    # setup values for current project
    ```
2. Setup your php script `run.php`:
    ```shell
    cp run.sample.php run.php
    # edit script run.php for your purpose
    ```
   Used two callback functions (see `run.sample.php`) for parsing every row in csv file and generating new header for result CSV file.
3. And run php script:
    ```shell
    php run.php
    ```
Will generate new CSV file: `data/result` file, where `result` - file string in the `config.php` file.
