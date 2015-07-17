<?php

namespace EdRush\Extbaser\VO\Module;

use EdRush\Extbaser\VO\Module\Value\ActionGroup;
use EdRush\Extbaser\VO\Module\Value\ObjectSettings;
use EdRush\Extbaser\VO\Module\Value\PropertyGroup;
/**
 * @author weberius
 *
 */
class Value {
	
	/**
	 * @var ActionGroup
	 */
	protected $actionGroup;
	
	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var ObjectSettings
	 */
	protected $objectSettings;
	
	/**
	 * @var PropertyGroup
	 */
	protected $propertyGroup;
	
	public function __construct() {
		$this->actionGroup = new ActionGroup();
		$this->objectSettings = new ObjectSettings();
		$this->propertyGroup = new PropertyGroup();
	}
	
	public function getActionGroup() {
		return $this->actionGroup;
	}
	public function setActionGroup(ActionGroup $actionGroup) {
		$this->actionGroup = $actionGroup;
		return $this;
	}
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	public function getObjectSettings() {
		return $this->objectSettings;
	}
	public function setObjectSettings(ObjectSettings $objectSettings) {
		$this->objectSettings = $objectSettings;
		return $this;
	}
	public function getPropertyGroup() {
		return $this->propertyGroup;
	}
	public function setPropertyGroup(PropertyGroup $propertyGroup) {
		$this->propertyGroup = $propertyGroup;
		return $this;
	}
	
	
}