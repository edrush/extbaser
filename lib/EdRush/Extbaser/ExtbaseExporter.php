<?php

namespace EdRush\Extbaser;

use EdRush\Extbaser\VO\ExtensionBuilderConfiguration;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\PropertyGroup\Property;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Properties;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Log;

class ExtbaseExporter
{
    const PROJECT_FILE_NAME = 'ExtensionBuilder.json';
    
    private static $_propertyTypes = array(
		'bool' => 'Boolean'
    );

    /**
     * @var array
     */
    private $metadata = array();

    /**
     * @var string
     */
    private $extensionKey;

    /**
     * @var array
     */
    private $logs = array();

    /**
     * @var string
     */
    private $path;

    /**
     * @var bool
     */
    private $overwriteExistingFiles = false;

    public function exportJson()
    {
        $filename = $this->path.'/'.$this->extensionKey.'/'.self::PROJECT_FILE_NAME;

        if (!is_dir($dir = dirname($filename))) {
            mkdir($dir, 0777, true);
        }
        
        $configuration = new ExtensionBuilderConfiguration();

        if (is_readable($filename))
        {
        	
        	if ( !$this->overwriteExistingFiles) {
            	
        		$this->logs[] = sprintf('File "<info>%s</info>" already existing, use --force to replace it.', $filename);
            	return 1;
        	} else {
        		
        		$roundtripContents = json_decode(file_get_contents($filename), true);
        		
        		$configuration->setProperties($this->mapArrayToClass($roundtripContents['properties'], new Properties()));
        		$configuration->setLog($this->mapArrayToClass($roundtripContents['log'], new Log()));
        		
        		$configuration->setWires($roundtripContents['wires']);
        		
        	}
        	
        }
        
        $configuration->setExtensionKey($this->extensionKey);
        $configuration->setLastModified(date('Y-m-d H:i'));
        
        foreach ($this->metadata as $metadata) {
        	$className = $metadata->name;
			$this->logs[] = 'Processing '.$className;
			
            $module = new Module();
            $module->setName($className);
            $module->setPosition(array(100,100));
            $module->setUid($this->getRandomUid());
            
            foreach ($metadata->fieldMappings as $fieldMapping) {
            	$property = new Property();
            	
            	$property->setPropertyName($fieldMapping['fieldName']);
            	$property->setPropertyType($this->getPropertyType($fieldMapping['type']));
            	$property->setUid($this->getRandomUid());
            	
            	$module->addProperty($property);
            }

            $configuration->addModule($module);
        }

        file_put_contents($filename, json_encode($configuration, JSON_PRETTY_PRINT));

        return 0;
    }
    
    /**
     * Gets the extbase property type by for a given type.
     *
     * @param string      $type The type to get the extbase property for.
     *
     * @return string
     */
    protected function getPropertyType($type) {
    	
    	$propertyType = '';
    	if ( isset(self::$_propertyTypes[$type])) {
    		$propertyType = self::$_propertyTypes[$type];
    	} else {
    		$propertyType = ucfirst($type);
    	}
    		
    	return $propertyType;
    }
    
    /**
     * Generate a 12 chars long random integer, convert it to string
     * 
     * @return string
     */
    protected function getRandomUid() {
    	
    	$uid =  rand (100000000000, 999999999999);
    	return (string) $uid;
    }
    
    protected function mapArrayToClass ($array, $class) {
    	
    	foreach ($array as $key => $value) {
    		$class->{$key} = $value;
    	}
    	
    	return $class;
    }

    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    public function getExtensionKey()
    {
        return $this->extensionKey;
    }

    /**
     * @param string $extensionKey
     */
    public function setExtensionKey($extensionKey)
    {
        $this->extensionKey = $extensionKey;
    }

    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param array $logs
     */
    public function setLogs($logs)
    {
        $this->logs = $logs;
    }

    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getOverwriteExistingFiles()
    {
        return $this->overwriteExistingFiles;
    }

    /**
     * @param bool $overwriteExistingFiles
     */
    public function setOverwriteExistingFiles($overwriteExistingFiles)
    {
        $this->overwriteExistingFiles = $overwriteExistingFiles;
    }
}
