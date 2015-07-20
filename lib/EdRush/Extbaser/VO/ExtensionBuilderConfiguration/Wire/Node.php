<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Wire;

/**
 * @author weberius
 */
class Node
{
    /**
     * @var int
     */
    public $moduleId;

    /**
     * @var string
     */
    public $terminal;

    /**
     * @var string
     */
    public $uid = '0';

    public function getModuleId()
    {
        return $this->moduleId;
    }
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;

        return $this;
    }
    public function getTerminal()
    {
        return $this->terminal;
    }
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }
    public function getUid()
    {
        return $this->uid;
    }
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }
}
