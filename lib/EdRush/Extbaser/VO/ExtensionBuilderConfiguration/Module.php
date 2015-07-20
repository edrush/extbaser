<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration;

use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Config;

/**
 * @author weberius
 */
class Module
{
    /**
     * @var Config
     */
    public $config;

    /**
     * @var string
     */
    public $name = 'New Model Object';

    /**
     * @var Value
     */
    public $value;

    public function __construct()
    {
        $this->config = new Config();
        $this->value = new Value();
    }

    public function getConfig()
    {
        return $this->config;
    }
    public function setConfig(Config $config)
    {
        $this->config = $config;

        return $this;
    }
    public function getValue()
    {
        return $this->value;
    }
    public function setValue(Value $value)
    {
        $this->value = $value;

        return $this;
    }
}
