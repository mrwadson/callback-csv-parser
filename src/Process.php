<?php

namespace App;

use RuntimeException;
use Throwable;

class Process
{
    /**
     * @var string
     */
    const CONFIG_FILE = __DIR__ . '/../config.php';

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

    public static function init(string $config = null, callable $rowCallable = null, callable $headCallable = null, callable $resultCallable = null): self
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

    public function __invoke()
    {
        $parser = new Csv();
        $parser->open($this->config['input'])
            ->parse($this->rowCallback)
            ->put($this->config['result'], $this->headCallback, $this->resultCallback);
    }

    private function __construct(string $config = null, callable $headCallback = null, callable $rowCallback = null, callable $resultCallback = null)
    {
        $this->config = $this->getConfig($config);
        $this->headCallback = $headCallback;
        $this->rowCallback = $rowCallback;
        $this->resultCallback = $resultCallback;
    }

    private function getConfig(string $config = null): array
    {
        $config = require $this->resolveConfigFile($config);
        try {
            $this->validateConfig($config);
        } catch (Throwable $e) {
            exit($e->getMessage());
        }

        return $config;
    }

    private function resolveConfigFile(string $config = null): string
    {
        if ($config) {
            return $config;
        }

        $backtrace = debug_backtrace();
        $runFile = array_pop($backtrace);

        if (preg_match('/_([^_]*\.php)$/', $basename = basename($runFile['file']), $match)
            && file_exists($config = __DIR__ . "/../config.$match[1]")) {
            return $config;
        }

        if (strpos($basename, '_') === false) {
            return self::CONFIG_FILE;
        }

        throw new RuntimeException('Unable to recognize configuration file for "' . $basename . "\"\n");
    }

    private function validateConfig(array $config)
    {
        if (!file_exists($config['input'])) {
            throw new RuntimeException('Input file in config not specified' . "\n");
        }
    }
}
