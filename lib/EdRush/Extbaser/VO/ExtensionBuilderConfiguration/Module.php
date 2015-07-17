<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration;

use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Config;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\PropertyGroup\Property;

/**
 * @author weberius
 *
 */
class Module {
	
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
	
	public function __construct() {
		$this->config = new Config();
		$this->value = new Value();
	}
	
	/**
	 * Module's name is in $this->value
	 * 
	 * @return Module
	 */
	public function setName($name) {
		$this->value->setName($name);
		return $this;
	}
	
	/**
	 * Module's position is in $this->config
	 *
	 * @return Module
	 */
	public function setPosition(array $position) {
		$this->config->setPosition($position);
		return $this;
	}
	
	/**
	 * Module's properties are in $this->value->propertyGroup
	 *
	 * @return Module
	 */
	public function addProperty(Property $property) {
		$this->value->getPropertyGroup()->addProperty($property);
		return $this;
	}
	
	/**
	 * Module's uid is in $this->value->objectSettings
	 *
	 * @return Module
	 */
	public function setUid($uid) {
		$this->value->getObjectSettings()->setUid($uid);
		return $this;
	}
	
	public function getConfig() {
		return $this->config;
	}
	public function setConfig(Config $config) {
		$this->config = $config;
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