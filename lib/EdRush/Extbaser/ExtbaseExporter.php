<?php

namespace EdRush\Extbaser;

class ExtbaseExporter
{
	const PROJECT_FILE_NAME = 'ExtensionBuilder.json';
	
	/**
     * @var array
     */
    private $metadata;
	
	/**
     * @var string
     */
    private $extensionKey;
	
	/**
     * @var array
     */
	private $logs;
	
	public function exportJson()
	{
		foreach ($this->metadata as $class) 
		{
			$className = $class->name;
			$this->logs[] = $className;
        }
	}
	
	public function getMetadata(){
		return $this->metadata;
	}

	/**
     * @param array $metadata
     */
	public function setMetadata($metadata){
		$this->metadata = $metadata;
	}

	public function getExtensionKey(){
		return $this->extensionKey;
	}

	/**
     * @param string $extensionKey
     */
	public function setExtensionKey($extensionKey){
		$this->extensionKey = $extensionKey;
	}
	
	public function getLogs(){
		return $this->logs;
	}

	/**
     * @param array $logs
     */
	public function setLogs($logs){
		$this->logs = $logs;
	}
	
}
