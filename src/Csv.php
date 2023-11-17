<?php

namespace mrwadson;

use RuntimeException;

class Csv
{
    /**
     * @var array
     */
    private $header;

    /**
     * @var bool
     */
    private $includeHeaderInResult;

    /**
     * @var false|resource
     */
    private $handle;

    /**
     * @var array
     */
    private $result = [];

    /**
     * Open CSV file
     *
     * @param string $file
     * @param bool $firstRowIsHeader
     * @param bool $includeHeaderInResult
     * @param string $mode
     *
     * @return $this
     */
    public function open(string $file, bool $firstRowIsHeader = false, bool $includeHeaderInResult = false, string $mode = 'rb'): Csv
    {
        $this->handle = fopen($file, $mode);
        if (!$this->handle) {
            throw new RuntimeException('Unable to open file ' . $file);
        }

        if ($firstRowIsHeader) {
            $this->header = fgetcsv($this->handle);
        }

        $this->includeHeaderInResult = $includeHeaderInResult;

        return $this;
    }

    /**
     * Run parse process with callback passed
     *
     * @param callable|null $callable
     *
     * @return $this
     */
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

    /**
     * Process result with callbacks and get result rows
     *
     * @param string|null $file
     * @param callable|null $headCallback
     * @param callable|null $resultCallback
     *
     * @return array|null
     */
    public function result(string $file = null, callable $headCallback = null, callable $resultCallback = null): ? array
    {
        $handle = null;
        if ($file) {
            $handle = fopen($file, 'wb');
            if (!$this->handle) {
                throw new RuntimeException('Unable to open file ' . $file);
            }
        }
        if ($this->header && $headCallback) {
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

        if ($this->header && $this->includeHeaderInResult) {
            array_unshift($rows, $this->header);
        }

        return $rows;
    }

    /**
     * Get row from the result
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function getRow(string $key)
    {
        return $this->result[$key] ?? null;
    }

    /**
     * Set row to the result
     *
     * @param string $key
     * @param array $row
     *
     * @return void
     */
    public function setRow(string $key, array $row): void
    {
        $this->result[$key] = $row;
    }

    /**
     * Destruct the class
     */
    public function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
            $this->handle = null;
        }
    }
}
