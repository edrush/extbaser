<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\RelationGroup;

/**
 * @author weberius
 */
class Relation
{
    /**
     * @var string
     */
    public $foreignRelationClass = '';

    /**
     * @var bool
     */
    public $lazyLoading = false;

    /**
     * @var bool
     */
    public $propertyIsExcludeField = true;

    /**
     * @var string
     */
    public $relationDescription = '';

    /**
     * @var string
     */
    public $relationName = '';

    /**
     * @var string
     */
    public $relationType = '';
    
    /**
     * @var string
     */
    public $relationWire = '[wired]';

    /**
     * @var string
     */
    public $uid = '0';
    
	public function getForeignRelationClass() {
		return $this->foreignRelationClass;
	}
	public function setForeignRelationClass($foreignRelationClass) {
		$this->foreignRelationClass = $foreignRelationClass;
		return $this;
	}
	public function getLazyLoading() {
		return $this->lazyLoading;
	}
	public function setLazyLoading($lazyLoading) {
		$this->lazyLoading = $lazyLoading;
		return $this;
	}
	public function getPropertyIsExcludeField() {
		return $this->propertyIsExcludeField;
	}
	public function setPropertyIsExcludeField($propertyIsExcludeField) {
		$this->propertyIsExcludeField = $propertyIsExcludeField;
		return $this;
	}
	public function getRelationDescription() {
		return $this->relationDescription;
	}
	public function setRelationDescription($relationDescription) {
		$this->relationDescription = $relationDescription;
		return $this;
	}
	public function getRelationName() {
		return $this->relationName;
	}
	public function setRelationName($relationName) {
		$this->relationName = $relationName;
		return $this;
	}
	public function getRelationType() {
		return $this->relationType;
	}
	public function setRelationType($relationType) {
		$this->relationType = $relationType;
		return $this;
	}
	public function getRelationWire() {
		return $this->relationWire;
	}
	public function setRelationWire($relationWire) {
		$this->relationWire = $relationWire;
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
