## Callback CSV Parser

Parsing CSV file (using callbacks) into new CSV file with changed data and new (updated) header.

## Requirements

- PHP >= 7.0

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

## Multiple usage

For every new project just copy `parse.example.php` to `parse-my-project.php` and use it for your purpose.
For setting config variable see below.

And just run your parse php file:

```shell
php parse-my-project.php # will process input file from the $config['input']
```

## Config

Configuration parameters are set in the array (in the `$config` variable) directly in the `parse.php` file.

If you're working with multiple files, it's best to create a directory inside in the `data` directory. So your input and result files
will be located by example at `data/my-project/input.csv` and `data/my-project/result.csv` files.
