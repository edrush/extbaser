<?php

namespace EdRush\Extbaser\VO\Module\Value;

/**
 * @author weberius
 *
 */
class ActionGroup {
	
	/**
	 * @var bool
	 */
	protected $_default0_list = true;
	
	/**
	 * @var bool
	 */
	protected $_default1_show = false;
	
	/**
	 * @var bool
	 */
	protected $_default2_new_create = false;
	
	/**
	 * @var bool
	 */
	protected $_default3_edit_update = false;
	
	/**
	 * @var bool
	 */
	protected $_default4_delete = false;
	
	/**
	 * @var string[]
	 */
	protected $customActions = array();
	
	public function getDefault0List() {
		return $this->_default0_list;
	}
	public function setDefault0List($_default0_list) {
		$this->_default0_list = $_default0_list;
		return $this;
	}
	public function getDefault1Show() {
		return $this->_default1_show;
	}
	public function setDefault1Show($_default1_show) {
		$this->_default1_show = $_default1_show;
		return $this;
	}
	public function getDefault2NewCreate() {
		return $this->_default2_new_create;
	}
	public function setDefault2NewCreate($_default2_new_create) {
		$this->_default2_new_create = $_default2_new_create;
		return $this;
	}
	public function getDefault3EditUpdate() {
		return $this->_default3_edit_update;
	}
	public function setDefault3EditUpdate($_default3_edit_update) {
		$this->_default3_edit_update = $_default3_edit_update;
		return $this;
	}
	public function getDefault4Delete() {
		return $this->_default4_delete;
	}
	public function setDefault4Delete($_default4_delete) {
		$this->_default4_delete = $_default4_delete;
		return $this;
	}
	public function getCustomActions() {
		return $this->customActions;
	}
	public function setCustomActions($customActions) {
		$this->customActions = $customActions;
		return $this;
	}
	
}