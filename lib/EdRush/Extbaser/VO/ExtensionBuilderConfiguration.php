<?php

namespace EdRush\Extbaser\VO;

/**
 * @author weberius
 *
 */
class ExtensionBuilderConfiguration
{
	/**
	 * @var Module[]
	 */
	public $modules = array();
	
	/**
	 * @var array
	 */
	public $properties = array();
	
	/**
	 * @var array
	 */
	public $wires = array();
	
	/**
	 * @var array
	 */
	public $log = array();
	
	public function addModule(Module $module) {
		$this->modules[] = $module;
		return $this;
	}
	
	public function getModules() {
		return $this->modules;
	}
	public function setModules(array $modules) {
		$this->modules = $modules;
		return $this;
	}
	public function getProperties() {
		return $this->properties;
	}
	public function setProperties(array $properties) {
		$this->properties = $properties;
		return $this;
	}
	public function getWires() {
		return $this->wires;
	}
	public function setWires(array $wires) {
		$this->wires = $wires;
		return $this;
	}
	public function getLog() {
		return $this->log;
	}
	public function setLog(array $log) {
		$this->log = $log;
		return $this;
	}
		
}
