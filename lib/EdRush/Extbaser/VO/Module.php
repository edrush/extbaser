<?php

namespace EdRush\Extbaser\VO;

use EdRush\Extbaser\VO\Module\Value;
use EdRush\Extbaser\VO\Module\Config;
/**
 * @author weberius
 *
 */
class Module {
	
	/**
	 * @var Config
	 */
	protected $config;
	
	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var Value
	 */
	protected $value;
	
	public function __construct() {
		$this->config = new Config();
		$this->value = new Value();
	}
	
	public function getConfig() {
		return $this->config;
	}
	public function setConfig(Config $config) {
		$this->config = $config;
		return $this;
	}
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	public function getValue() {
		return $this->value;
	}
	public function setValue(Value $value) {
		$this->value = $value;
		return $this;
	}
	
}