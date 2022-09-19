<?php

namespace App;

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

    public static function init(callable $rowCallable = null, callable $headCallable = null, callable $resultCallable = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($rowCallable, $headCallable, $resultCallable);
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

    private function __construct(callable $headCallback = null, callable $rowCallback = null, callable $resultCallback = null)
    {
        $this->headCallback = $headCallback;
        $this->rowCallback = $rowCallback;
        $this->resultCallback = $resultCallback;
        $this->config = $this->getConfig();
    }

    private function getConfig(): array
    {
        return require self::CONFIG_FILE;
    }
}
