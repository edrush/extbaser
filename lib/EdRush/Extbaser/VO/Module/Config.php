<?php

namespace EdRush\Extbaser\VO\Module;

/**
 * @author weberius
 *
 */
class Config {
	
	/**
	 * @var array
	 */
	protected $position;

	public function getPosition() {
		return $this->position;
	}
	public function setPosition(array $position) {
		$this->position = $position;
		return $this;
	}
	
}