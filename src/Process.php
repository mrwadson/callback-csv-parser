<?php

namespace App;

use RuntimeException;

class Process
{
    /**
     * @var callable|null
     */
    private $headCallback;

    /**
     * @var callable|null
     */
    private $rowCallback;

    /**
     * @var callable|null
     */
    private $resultCallback;

    /**
     * @var array
     */
    public $config;

    /**
     * @var self
     */
    private static $instance;

    public static function init(array $config = null, callable $rowCallable = null, callable $headCallable = null, callable $resultCallable = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($config, $rowCallable, $headCallable, $resultCallable);
        }
        return self::$instance;
    }

    public function setHeadCallback(callable $headCallback): self
    {
        $this->headCallback = $headCallback;
        return $this;
    }

    public function setRowCallback(callable $rowCallback): self
    {
        $this->rowCallback = $rowCallback;
        return $this;
    }

    public function setResultCallback(callable $resultCallback): self
    {
        $this->resultCallback = $resultCallback;
        return $this;
    }

    public function __invoke(): ?array
    {
        $parser = new Csv();
        return $parser->open($this->config['input'], isset($this->config['header']) && $this->config['header'])
            ->parse($this->rowCallback)
            ->result($this->config['result'] ?? null, $this->headCallback, $this->resultCallback);
    }

    private function __construct(array $config = null, callable $headCallback = null, callable $rowCallback = null, callable $resultCallback = null)
    {
        $this->config = $this->parseConfig($config);
        $this->headCallback = $headCallback;
        $this->rowCallback = $rowCallback;
        $this->resultCallback = $resultCallback;
    }

    private function parseConfig(array $config = null): array
    {
        $this->validateConfig($config);
        return $config;
    }

    private function validateConfig(array $config): void
    {
        if (!isset($config['input'])) {
            throw new RuntimeException('Input file in config not specified' . "\n");
        }

        if (!file_exists($config['input'])) {
            throw new RuntimeException('Input file in config not specified' . "\n");
        }
    }
}
