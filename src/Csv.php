<?php

namespace App;

use Exception;

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

    private array $result = [];

    /**
     * @throws Exception
     */
    public function open(string $file, bool $header = true, string $mode = 'r'): Csv
    {
        $this->handle = fopen($file, $mode);
        if (!$this->handle) {
            throw new Exception('Unable to open file ' . $file);
        }

        if ($header) {
            $this->header = fgetcsv($this->handle);
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function parse(callable $callable): Csv
    {
        if (!$this->handle) {
            throw new Exception('File is not opened for read');
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

    /**
     * @throws Exception
     */
    public function put(string $file, callable $header)
    {
        $handle = fopen($file, 'w');
        if (!$this->handle) {
            throw new Exception('Unable to open file ' . $file);
        }
        if ($header) {
            $this->header = $header();
        }
        if ($this->header) {
            fputcsv($handle, $this->header);
        }
        foreach ($this->result as $row) {
            fputcsv($handle, $row);
        }
    }

    public function getRow(string $key)
    {
        return $this->result[$key] ?? null;
    }

    public function setRow(string $key, array $row)
    {
        $this->result[$key] = $row;
    }

    public function __destruct()
    {
        $this->close();
    }

    private function close()
    {
        if ($this->handle) {
            fclose($this->handle);
            $this->handle = null;
        }
    }
}
