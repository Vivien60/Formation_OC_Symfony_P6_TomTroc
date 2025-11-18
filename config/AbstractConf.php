<?php
declare(strict_types=1);

namespace config;

use services\DBManager;

abstract class AbstractConf
{
    private static ?self $instance = null;
    public readonly array $_config;

    protected function __construct(array $config)
    {
        $this->_config = array_merge($this->defaultConfig(), $config);
    }

    public static function getInstance(array $config = []): static
    {
        if (static::$instance === null) {
            static::$instance = new static($config);
        }
        return static::$instance;
    }

    protected abstract function defaultConfig(): array;

    public abstract function deploy(): void;

}