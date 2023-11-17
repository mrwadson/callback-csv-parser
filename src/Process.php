<?php

namespace mrwadson;

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
     * @var array
     */
    private static $instances = [];

    /**
     * Init a new instance from the passed config
     *
     * @param array|null $config
     * @param callable|null $rowCallable
     * @param callable|null $headCallable
     * @param callable|null $resultCallable
     *
     * @return self
     */
    public static function init(array $config = null, callable $rowCallable = null, callable $headCallable = null, callable $resultCallable = null): self
    {
        $instanceKey = self::getInstanceKey($config);
        if (!isset(self::$instances[$instanceKey])) {
            self::$instances[$instanceKey] = new self($config, $rowCallable, $headCallable, $resultCallable);
        }
        return self::$instances[$instanceKey];
    }

    /**
     * Generate uniq instance key
     *
     * @param array $key
     *
     * @return string
     */
    private static function getInstanceKey(array $key): string
    {
        return md5(json_encode($key));
    }

    /**
     * Set callback for head row
     *
     * @param callable $headCallback
     *
     * @return $this
     */
    public function setHeadCallback(callable $headCallback): self
    {
        $this->headCallback = $headCallback;
        return $this;
    }

    /**
     * Set callback for every row
     *
     * @param callable $rowCallback
     *
     * @return $this
     */
    public function setRowCallback(callable $rowCallback): self
    {
        $this->rowCallback = $rowCallback;
        return $this;
    }

    /**
     * Set callback for the result data
     *
     * @param callable $resultCallback
     *
     * @return $this
     */
    public function setResultCallback(callable $resultCallback): self
    {
        $this->resultCallback = $resultCallback;
        return $this;
    }

    /**
     * Invoke process by call this class
     *
     * @return array|null
     */
    public function __invoke(): ?array
    {
        $parser = new Csv();
        return $parser->open(
                $this->config['input'],
                isset($this->config['first_row_is_header']) && $this->config['first_row_is_header'],
                isset($this->config['include_header_in_result']) && $this->config['include_header_in_result']
            )
            ->parse($this->rowCallback)
            ->result($this->config['result'] ?? null, $this->headCallback, $this->resultCallback);
    }

    /**
     * Construct for new instance
     *
     * @param array|null $config
     * @param callable|null $headCallback
     * @param callable|null $rowCallback
     * @param callable|null $resultCallback
     */
    private function __construct(array $config = null, callable $headCallback = null, callable $rowCallback = null, callable $resultCallback = null)
    {
        $this->config = $this->parseConfig($config);
        $this->headCallback = $headCallback;
        $this->rowCallback = $rowCallback;
        $this->resultCallback = $resultCallback;
    }

    /**
     * Parse and validate config
     *
     * @param array|null $config
     *
     * @return array
     */
    private function parseConfig(array $config = null): array
    {
        $this->validateConfig($config);
        return $config;
    }

    /**
     * Validate config
     *
     * @param array $config
     *
     * @return void
     */
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
