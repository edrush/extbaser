<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\PropertyGroup;

/**
 * @author weberius
 *
 */
class Property {
	
	/**
	 * @var string
	 */
	public $allowedFileTypes = '';
	
	/**
	 * @var string
	 */
	public $maxItems = '1';
	
	/**
	 * @var string
	 */
	public $propertyDescription = '';
	
	/**
	 * @var bool
	 */
	public $propertyIsExcludeField = false;
	
	/**
	 * @var bool
	 */
	public $propertyIsRequired = false;
	
	/**
	 * @var string
	 */
	public $propertyName = '';
	
	/**
	 * @var string
	 */
	public $propertyType = '';
	
	/**
	 * @var string
	 */
	public $uid = '0';
	
	public function getAllowedFileTypes() {
		return $this->allowedFileTypes;
	}
	public function setAllowedFileTypes($allowedFileTypes) {
		$this->allowedFileTypes = $allowedFileTypes;
		return $this;
	}
	public function getMaxItems() {
		return $this->maxItems;
	}
	public function setMaxItems($maxItems) {
		$this->maxItems = $maxItems;
		return $this;
	}
	public function getPropertyDescription() {
		return $this->propertyDescription;
	}
	public function setPropertyDescription($propertyDescription) {
		$this->propertyDescription = $propertyDescription;
		return $this;
	}
	public function getPropertyIsExcludeField() {
		return $this->propertyIsExcludeField;
	}
	public function setPropertyIsExcludeField($propertyIsExcludeField) {
		$this->propertyIsExcludeField = $propertyIsExcludeField;
		return $this;
	}
	public function getPropertyIsRequired() {
		return $this->propertyIsRequired;
	}
	public function setPropertyIsRequired($propertyIsRequired) {
		$this->propertyIsRequired = $propertyIsRequired;
		return $this;
	}
	public function getPropertyName() {
		return $this->propertyName;
	}
	public function setPropertyName($propertyName) {
		$this->propertyName = $propertyName;
		return $this;
	}
	public function getPropertyType() {
		return $this->propertyType;
	}
	public function setPropertyType($propertyType) {
		$this->propertyType = $propertyType;
		return $this;
	}
	public function getUid() {
		return $this->uid;
	}
	public function setUid($uid) {
		$this->uid = $uid;
		return $this;
	}
	
			
}