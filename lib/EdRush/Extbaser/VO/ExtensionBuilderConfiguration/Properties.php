<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration;

/**
 * @author weberius
 */
class Properties
{
    /**
     * @var string
     */
    public $extensionKey;

    public function getExtensionKey()
    {
        return $this->extensionKey;
    }
    public function setExtensionKey($extensionKey)
    {
        $this->extensionKey = $extensionKey;

        return $this;
    }
}
