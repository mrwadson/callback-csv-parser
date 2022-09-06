<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App;

class Process
{
    private const CONFIG_FILE = __DIR__ . '/../config.php';

    private $headCallback;
    private $rowCallback;

    public array $config;
    private static $instance;

    private function __construct(callable $headCallback = null, callable $rowCallback = null)
    {
        $this->headCallback = $headCallback;
        $this->rowCallback = $rowCallback;
        $this->config = $this->getConfig();
    }

    public static function init(callable $rowCallable = null, callable $headCallable = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($rowCallable, $headCallable);
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

    public function getConfig(): array
    {
        return require self::CONFIG_FILE;
    }

    public function __invoke()
    {
        $parser = new Csv();
        $parser->open($this->config['input'])
            ->parse($this->rowCallback)
            ->put($this->config['result'], $this->headCallback);
    }
}
