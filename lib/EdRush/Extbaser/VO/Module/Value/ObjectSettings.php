<?php

namespace EdRush\Extbaser\VO\Module\Value;

/**
 * @author weberius
 *
 */
class ObjectSettings {
	
	/**
	 * @var bool
	 */
	protected $addDeletedField = true;
	
	/**
	 * @var bool
	 */
	protected $addHiddenField = true;
	
	/**
	 * @var bool
	 */
	protected $addStarttimeEndtimeFields = true;
	
	/**
	 * @var bool
	 */
	protected $aggregateRoot = true;
	
	/**
	 * @var bool
	 */
	protected $categorizable = false;
	
	/**
	 * @var string
	 */
	protected $description = '';
	
	/**
	 * @var string
	 */
	protected $mapToTable = '';
	
	/**
	 * @var string
	 */
	protected $parentClass = '';
	
	/**
	 * @var bool
	 */
	protected $sorting = false;
	
	/**
	 * @var string
	 */
	protected $type = '';
	
	/**
	 * @var int
	 */
	protected $uid = 0;
	
	public function getAddDeletedField() {
		return $this->addDeletedField;
	}
	public function setAddDeletedField($addDeletedField) {
		$this->addDeletedField = $addDeletedField;
		return $this;
	}
	public function getAddHiddenField() {
		return $this->addHiddenField;
	}
	public function setAddHiddenField($addHiddenField) {
		$this->addHiddenField = $addHiddenField;
		return $this;
	}
	public function getAddStarttimeEndtimeFields() {
		return $this->addStarttimeEndtimeFields;
	}
	public function setAddStarttimeEndtimeFields($addStarttimeEndtimeFields) {
		$this->addStarttimeEndtimeFields = $addStarttimeEndtimeFields;
		return $this;
	}
	public function getAggregateRoot() {
		return $this->aggregateRoot;
	}
	public function setAggregateRoot($aggregateRoot) {
		$this->aggregateRoot = $aggregateRoot;
		return $this;
	}
	public function getCategorizable() {
		return $this->categorizable;
	}
	public function setCategorizable($categorizable) {
		$this->categorizable = $categorizable;
		return $this;
	}
	public function getDescription() {
		return $this->description;
	}
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	public function getMapToTable() {
		return $this->mapToTable;
	}
	public function setMapToTable($mapToTable) {
		$this->mapToTable = $mapToTable;
		return $this;
	}
	public function getParentClass() {
		return $this->parentClass;
	}
	public function setParentClass($parentClass) {
		$this->parentClass = $parentClass;
		return $this;
	}
	public function getSorting() {
		return $this->sorting;
	}
	public function setSorting($sorting) {
		$this->sorting = $sorting;
		return $this;
	}
	public function getType() {
		return $this->type;
	}
	public function setType($type) {
		$this->type = $type;
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