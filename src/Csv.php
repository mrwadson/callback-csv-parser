<?php

namespace App;

use RuntimeException;

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

    public function open(string $file, bool $header = false, string $mode = 'rb'): Csv
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

    public function parse(callable $callable = null): Csv
    {
        if (!$this->handle) {
            throw new RuntimeException('File is not opened for read');
        }

        while (($row = fgetcsv($this->handle)) !== false) {
            if ($this->header) {
                $row = array_combine($this->header, $row);
            }

            if ($callable) {
                if ($newRow = $callable($row, $this)) {
                    if (is_array($newRow)) {
                        $this->result[] = $newRow;
                    } else {
                        $this->result[] = [$newRow];
                    }
                }
            } else {
                $this->result[] = $row;
            }
        }

        return $this;
    }

    public function result(string $file = null, callable $headCallback = null, callable $resultCallback = null): ? array
    {
        $handle = null;
        if ($file) {
            $handle = fopen($file, 'wb');
            if (!$this->handle) {
                throw new RuntimeException('Unable to open file ' . $file);
            }
        }
        if ($headCallback) {
            $headerResult = $headCallback();
            if (!is_bool($headerResult)) {
                $this->header = $headerResult;
            }
        }
        if ($file && $this->header) {
            fputcsv($handle, $this->header);
        }

        if ($resultCallback) {
            $rows = $resultCallback($this->result);
        } else {
            $rows = $this->result;
        }

        if ($file) {
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            return null;
        }

        return $rows;
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
