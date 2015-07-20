<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration;

use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Wire\Node;
/**
 * @author weberius
 */
class Wire
{
    /**
     * @var Node
     */
    public $src;

    /**
     * @var Node
     */
    public $tgt;
    
    public function __construct()
    {
        $this->src = new Node();
        $this->tgt = new Node();
    }
    
	public function getSrc() {
		return $this->src;
	}
	public function setSrc(Node $src) {
		$this->src = $src;
		return $this;
	}
	public function getTgt() {
		return $this->tgt;
	}
	public function setTgt(Node $tgt) {
		$this->tgt = $tgt;
		return $this;
	}
	

}
