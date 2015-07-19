<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module;

/**
 * @author weberius
 */
class Config
{
    /**
     * @var array
     */
    public $position;

    public function getPosition()
    {
        return $this->position;
    }
    public function setPosition(array $position)
    {
        $this->position = $position;

        return $this;
    }
}
