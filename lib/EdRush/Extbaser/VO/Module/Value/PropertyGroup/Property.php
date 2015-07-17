<?php

namespace EdRush\Extbaser\VO\Module\Value\PropertyGroup;

/**
 * @author weberius
 *
 */
class Property {
	
	const TYPE_STRING = 'String';

	/**
	 * @var string
	 */
	protected $allowedFileTypes = '';
	
	/**
	 * @var int
	 */
	protected $maxItems = 1;
	
	/**
	 * @var string
	 */
	protected $propertyDescription;
	
	/**
	 * @var bool
	 */
	protected $propertyIsExcludeField = false;
	
	/**
	 * @var bool
	 */
	protected $propertyIsRequired = false;
	
	/**
	 * @var string
	 */
	protected $propertyName = '';
	
	/**
	 * @var string
	 */
	protected $propertyType = '';
	
	/**
	 * @var int
	 */
	protected $uid = 0;
	
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