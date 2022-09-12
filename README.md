## Callback CSV Parser

Parsing CSV file (using callbacks) into new CSV file with changed data and new header.

## Required

- PHP >= 7.1

## Usage

1. Copy `config.sample.php` to `config.php`, `run.sample.php` to `run.php`:
    ```shell
    cp config.sample.php config.php # setup values for current project
    cp run.sample.php run.php # edit script run.php for your purpose
    ```
2. In `run.php` can be used three callback functions (see `run.sample.php`) for parsing csv file:
   - Every row (`rowCallback`)
   - Header (`headCallback`)
   - Whole result (`resultCallback`)
3. Run php script:
    ```shell
    php run.php # generate new result.csv file
    ```
Will generate new CSV file: `data/result` file, where `result` - result filename (set in the configuration file `config.php`).
