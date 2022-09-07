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
   Can be used three callback functions (see `run.sample.php`) for parsing csv file:
   - every row (`rowCallback`)
   - header (`headCallback`)
   - whole result (`resultCallback`)
3. And run php script:
    ```shell
    php run.php
    ```
Will generate new CSV file: `data/result` file, where `result` - result filename (set in the configuration file `config.php`).
