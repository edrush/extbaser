<?php

namespace EdRush\Extbaser;

use EdRush\Extbaser\VO\ExtensionBuilderConfiguration;

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
        		
        		$configuration->setProperties($roundtripContents['properties']);
        		$configuration->setWires($roundtripContents['wires']);
        		$configuration->setLog($roundtripContents['log']);
        		
        	}
        	
        }

        foreach ($this->metadata as $class) {
        	$className = $class->name;
			$this->logs[] = 'Processing '.$className;
			
            $module = array();
			$module['config']['position'] = array(100,100);
			$module['name'] = $className;

            $configuration->addModule($module);
        }

        file_put_contents($filename, json_encode(array(
        		'modules' => $configuration->getModules(),
        		'properties' => $configuration->getProperties(),
        		'wires' => $configuration->getWires(),
        		'log' => $configuration->getLog(),
        		), JSON_PRETTY_PRINT));

        return 0;
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
