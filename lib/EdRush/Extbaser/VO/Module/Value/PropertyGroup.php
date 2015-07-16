<?php

namespace EdRush\Extbaser\VO\Module\Value;

use EdRush\Extbaser\VO\Module\Value\PropertyGroup\Property;
/**
 * @author weberius
 *
 */
class PropertyGroup {
	
	/**
	 * @var Property[]
	 */
	protected $properties;
	
	public function addProperty(Property $property) {
		$this->properties[] = $property;
		return $this;
	}
	
	public function getProperties() {
		return $this->properties;
	}
	public function setProperties($properties) {
		$this->properties = $properties;
	}
	
}