<?php

namespace EdRush\Extbaser;

use EdRush\Extbaser\VO\ExtensionBuilderConfiguration;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\PropertyGroup\Property;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Properties;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Log;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Doctrine\ORM\Tools\Console\MetadataFilter;

/**
 * 
 * ExtbaseExporter
 * 
 * @author weberius
 */
class ExtbaseExporter
{
    const PROJECT_FILE_NAME = 'ExtensionBuilder.json';
    
    private static $_propertyTypes = array(
		'bool' => 'Boolean'
    );

    /**
     * @var DisconnectedClassMetadataFactory
     */
    protected $cmf;
    
    /**
     * @var string
     */
    protected $extensionKey;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var bool
     */
    protected $overwriteExistingFiles = false;
    
    /**
     * @var string
     */
    protected $filter = '';
    
    /**
     * @var array
     */
    protected $logs = array();
    
    public function __construct(DisconnectedClassMetadataFactory $cmf) {
    	
    	$this->cmf = $cmf;
    	
    	//allow sonata type 'json'
    	\Doctrine\DBAL\Types\Type::addType('json', 'Sonata\Doctrine\Types\JsonType');
    }

    public function exportJson() {
    	
    	$metadata = $this->cmf->getAllMetadata();
    	$metadata = MetadataFilter::filter($metadata, $this->filter);
    	
    	if ($metadata) {

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
    		
    		foreach ($metadata as $metadata) {
    			$className = $metadata->name;
    			$this->logs[] = sprintf('--processing table "<info>%s</info>" ...', $className);
    				
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
    		
    		$this->logs[] = 'Exported database scheme to '.$filename;
    		
    		return true;
    	} else {
    		$this->logs[] = 'Database does not have any mapping information.';
    		return false;
    	}
        
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
	public function getFilter() {
		return $this->filter;
	}
	public function setFilter($filter) {
		$this->filter = $filter;
		return $this;
	}
	public function getCmf() {
		return $this->cmf;
	}
	public function setCmf(DisconnectedClassMetadataFactory $cmf) {
		$this->cmf = $cmf;
		return $this;
	}
	
	
}
