<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration;

/**
 * @author weberius
 *
 */
class Log {
	
	/**
	 * @var string
	 */
	public $extension_builder_version = '6.2.0';
	
	/**
	 * @var string
	 */
	public $last_modified = '';
	
	public function getExtensionBuilderVersion() {
		return $this->extension_builder_version;
	}
	public function setExtensionBuilderVersion($extension_builder_version) {
		$this->extension_builder_version = $extension_builder_version;
		return $this;
	}
	public function getLastModified() {
		return $this->last_modified;
	}
	public function setLastModified($last_modified) {
		$this->last_modified = $last_modified;
		return $this;
	}
	
	
	
}