<?php

namespace App;

use RuntimeException;
use function fclose;

class Csv
{
    /**
     * @var array|false|null
     */
    private $header;

    /**
     * @var false|resource
     */
    private $handle;

    /**
     * @var array
     */
    private $result = [];

    public function open(string $file, bool $header = true, string $mode = 'rb'): Csv
    {
        $this->handle = fopen($file, $mode);
        if (!$this->handle) {
            throw new RuntimeException('Unable to open file ' . $file);
        }

        if ($header) {
            $this->header = fgetcsv($this->handle);
        }

        return $this;
    }

    public function parse(callable $callable): Csv
    {
        if (!$this->handle) {
            throw new RuntimeException('File is not opened for read');
        }
        while (($row = fgetcsv($this->handle)) !== false) {
            if ($this->header) {
                $row = array_combine($this->header, $row);
            }
            if ($newRow = $callable($row, $this)) {
                $this->result[] = $newRow;
            }
        }

        return $this;
    }

    public function put(string $file, callable $header = null, callable $result = null): void
    {
        $handle = fopen($file, 'wb');
        if (!$this->handle) {
            throw new RuntimeException('Unable to open file ' . $file);
        }
        if ($header) {
            $this->header = $header();
        }
        if ($this->header) {
            fputcsv($handle, $this->header);
        }

        if ($result) {
            $rows = $result($this->result);
        } else {
            $rows = $this->result;
        }
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
    }

    public function getRow(string $key)
    {
        return $this->result[$key] ?? null;
    }

    public function setRow(string $key, array $row): void
    {
        $this->result[$key] = $row;
    }

    public function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
            $this->handle = null;
        }
    }
}
