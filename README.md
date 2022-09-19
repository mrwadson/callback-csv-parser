## Callback CSV Parser

Parsing CSV file (using callbacks) into new CSV file with changed data and new header.

## Requirements

- PHP >= 7.0

## Usage

1. Copy `config.sample.php` to `config.php`, `parse.sample.php` to `parse.php`:
    ```shell
    cp config.sample.php config.php # setup values for current project
    cp parse.sample.php parse.php # edit script parse.php for your purpose
    ```
2. In `parse.php` can be used three callback functions (see `parse.sample.php`) for parsing CSV file:
   - Every row (`rowCallback`)
   - Header (`headCallback`)
   - Whole result (`resultCallback`)
3. Run php script:
    ```shell
    php parse.php # generate new result.csv file
    ```
Will generate new CSV file: `data/{result}` file, where `{result}` - result filename (set in the configuration file `config.php`).
