<?php

namespace EdRush\Extbaser;

use EdRush\Extbaser\VO\ExtensionBuilderConfiguration;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\PropertyGroup\Property;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Properties;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Log;
use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Doctrine\ORM\Tools\Console\MetadataFilter;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\RelationGroup\Relation;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Wire;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Wire\Node;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\ObjectSettings;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\Yaml\Yaml;

/**
 * ExtbaseExporter.
 *
 * @author weberius
 */
class ExtbaseExporter
{
    const PROJECT_FILE = 'ExtensionBuilder.json';
    const CONFIG_FILE = 'Configuration/extbaser.yml';

    private static $_propertyTypes = array(
        'bool' => 'Boolean',
        'datetime' => 'NativeDateTime',
        'array' => 'String',
        'json' => 'String',
        'jsonarray' => 'String',
        'smallint' => 'Integer',
        'dateinterval' => 'String',
        'time' => 'NativeDateTime'
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
     * @var bool
     */
    protected $roundTripExistingFiles = false;

    /**
     * @var string
     */
    protected $filter = '';

    /**
     * @var array
     */
    protected $logs = array();

    /**
     * @var array
     */
    protected $configuration = array();

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var array
     */
    protected $metadata;

    public function __construct(DisconnectedClassMetadataFactory $cmf)
    {
        $this->cmf = $cmf;

        //allow sonata type 'json'
        if (!\Doctrine\DBAL\Types\Type::hasType('json')) {
            \Doctrine\DBAL\Types\Type::addType('json', 'Sonata\Doctrine\Types\JsonType');
        }

        if (!\Doctrine\DBAL\Types\Type::hasType('dateinterval')) {
            \Doctrine\DBAL\Types\Type::addType('dateinterval', 'Herrera\Doctrine\DBAL\Types\DateIntervalType');
        }
    }

    public function exportJson()
    {
        // set metadata, mkdir, set filename and configuration
        $this->prepare();

        if (!empty($this->metadata)) {
            $extbaseConfig = new ExtensionBuilderConfiguration();
            $roundtripCache = array();

            if (is_readable($this->filename)) {
                if ($this->overwriteExistingFiles && $this->roundTripExistingFiles) {
                    $this->logs[] = sprintf('File "<info>%s</info>" already exists, you selected both override (force) and round-trip - please choose one.', $this->filename);

                    return 1;
                }
                if (!$this->overwriteExistingFiles && !$this->roundTripExistingFiles) {
                    $this->logs[] = sprintf('File "<info>%s</info>" already exists, use --force to override or --round-trip it.', $this->filename);

                    return 1;
                }
                if ($this->roundTripExistingFiles) {
                    $roundtripContents = json_decode(file_get_contents($this->filename), true);

                    $extbaseConfig->setProperties($this->mapArrayToClass($roundtripContents['properties'], new Properties()));
                    $extbaseConfig->setLog($this->mapArrayToClass($roundtripContents['log'], new Log()));
//                    $roundtripCache[]
                    foreach ($roundtripContents['modules'] as $moduleArray) {
                        $module = $this->mapArrayToClass($moduleArray, new Module());
                        /* @var $module \EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module */
                        if (isset($module->value) && isset($module->value['name'])) {
                            $roundtripCache['actionGroups'][$module->value['name']] = $module->getValue()['actionGroup'];
                        }
                    }
                }
            }

            $extensionKey = $this->extensionKey;
            if (is_null($extensionKey)) {
                // support limbo directory separators
                // e.g. on Windows git bash uses "/", but php's DIRECTORY_SEPARATOR is "\"
                $directorySeparators = array("\\", "/");
                $path = str_replace($directorySeparators, DIRECTORY_SEPARATOR, $this->path);
                $extensionKey = array_pop(explode(DIRECTORY_SEPARATOR, $path));
            }

            $extbaseConfig->getProperties()
                ->setExtensionKey($extensionKey);
            $extbaseConfig->getLog()
                ->setLastModified(date('Y-m-d H:i'));

            //in this array we store the target entites for all relations to create wires later on
            $relationTargetsByModuleByRelation = array();

            $moduleIndex = 0;
            $posX = 50;
            $posY = 50;
            foreach ($this->metadata as $metadata) {
                /* @var $metadata \Doctrine\ORM\Mapping\ClassMetadata */
                $className = $metadata->name;
                $tableName = $metadata->getTableName();
                //remove namespaces, e.g. when importing entities
                if (class_exists($className)) {
                    $reflection = new \ReflectionClass($className);
                    $className = $reflection->getShortName();
                }

                $ignore = false;
                if (isset($this->configuration['ignore_tables'])) {
                    foreach ($this->configuration['ignore_tables'] as $ignoreTableName) {
                        if (fnmatch($ignoreTableName, $tableName))
                            $ignore = true;
                    }
                }
                if ($ignore) {
                    continue;
                }

                $this->logs[] = '';
                $this->logs[] = sprintf('Processing table "<info>%s</info>"...', $tableName);

                if ($moduleIndex) {
                    if (0 == $moduleIndex % 5) {
                        $posX = 50;
                        $posY += 200;
                    } else {
                        $posX += 300;
                    }
                }

                $module = new Module();
                $module->getValue()
                    ->setName($className);
                $module->getConfig()
                    ->setPosition(array($posX, $posY));
                $module->getValue()
                    ->getObjectsettings()
                    ->setUid($this->getRandomUid());
                $module->getValue()
                    ->getObjectsettings()
                    ->setType(ObjectSettings::OBJECT_TYPE_ENTITY);

                if (isset($roundtripCache['actionGroups']) && isset($roundtripCache['actionGroups'][$className])) {
                    foreach ($roundtripCache['actionGroups'][$className] as $key => $value) {
                        $module->getValue()->getActionGroup()->$key = $value;
                    }
                }

                //export properties
                foreach ($metadata->fieldMappings as $fieldMapping) {
                    $property = new Property();

                    $property->setPropertyName($fieldMapping['fieldName']);
                    $property->setPropertyType($this->getPropertyType($fieldMapping['type']));
                    $property->setUid($this->getRandomUid());

                    $module->getValue()
                        ->getPropertyGroup()
                        ->addProperty($property);
                }

                //export relations
                $relationIndex = 0;
                foreach ($metadata->associationMappings as $associationMapping) {
                    $targetClassName = $associationMapping['targetEntity'];
                    //remove namespaces, e.g. when importing entities
                    if (class_exists($targetClassName)) {
                        $reflection = new \ReflectionClass($targetClassName);
                        $targetClassName = $reflection->getShortName();
                    }

                    $relationNameSingular = $associationMapping['fieldName'];
                    $relationNamePlural = Inflector::pluralize(Inflector::singularize($associationMapping['fieldName']));
                    $relationNameSingularMappedBy = Inflector::singularize(strtolower($targetClassName));
                    $relationNamePluralMappedBy = Inflector::pluralize(strtolower($targetClassName));

                    $relation = new Relation();
                    $relationType = null;
                    $relationName = '';

                    switch ($associationMapping['type']) {
                        case ClassMetadataInfo::ONE_TO_MANY:
                            $relationType = Relation::ONE_TO_MANY;
                            $relationName = $relationNamePlural;
                            break;
                        case ClassMetadataInfo::MANY_TO_ONE:
                            $relationType = Relation::MANY_TO_ONE;
                            $relationName = $relationNameSingular;
                            break;
                        case ClassMetadataInfo::ONE_TO_ONE:
                            $relationType = Relation::ONE_TO_ONE;
                            $relationName = $relationNameSingular;
                            break;
                        case ClassMetadataInfo::MANY_TO_MANY:
                            $relationType = Relation::MANY_TO_MANY;
                            if ($relationNameSingularMappedBy == $tableName) {
                                $relationName = $relationNamePlural;
                            } else {
                                $relationName = $relationNamePluralMappedBy;
                            }
                            break;
                    }

                    $relation->setRelationName($relationName);
                    $relation->setRelationType($relationType);

                    // add the relation to the module
                    $module->getValue()->getRelationGroup()->addRelation($relation);

                    $relationTargetsByModuleByRelation[$moduleIndex][$relationIndex] = $targetClassName;
                    $relationIndex++;

                    $this->logs[] = sprintf('Added relation "<comment>%s</comment>": "<info>%s</info>" -> "<info>%s</info>"', $relationName, $className, $targetClassName);
                }

                $extbaseConfig->addModule($module);

                $moduleIndex++;
            }

            // now we have all the modules, we can create wires
            $moduleIndex = 0;
            foreach ($extbaseConfig->getModules() as $key => $module) {
                $relationIndex = 0;
                if (!empty($module->getValue()->getRelationGroup()->getRelations())) {
                    foreach ($module->getValue()->getRelationGroup()->getRelations() as $relation) {
                        /* @var $relation Relation */

                        // now add the corresponding wire for the relation
                        $wire = new Wire();
                        $targetEntity = $relationTargetsByModuleByRelation[$moduleIndex][$relationIndex];
                        $targetModule = $extbaseConfig->getModuleByName($targetEntity);

                        if ($targetModule) {
                            $targetModuleId = array_search($targetModule, $extbaseConfig->getModules());

                            $src = new Node();
                            $src->setModuleId($key);
                            $src->setTerminal(Node::TERMINAL_SRC . $relationIndex);
                            $src->setUid($relation->getUid());

                            $tgt = new Node();
                            $tgt->setModuleId($targetModuleId);
                            $tgt->setTerminal(Node::TERMINAL_TGT);
                            $tgt->setUid($targetModule->getValue()->getObjectsettings()->getUid());

                            $wire->setSrc($src);
                            $wire->setTgt($tgt);
                            $extbaseConfig->addWire($wire);

                            $this->logs[] = sprintf('Added wire "<comment>%s</comment>": "<info>%s</info>" -> "<info>%s</info>"', $relation->getRelationName(), $module->getValue()->getName(), $targetEntity);
                        }

                        $relationIndex++;
                    }
                }

                $moduleIndex++;
            }

            file_put_contents($this->filename, json_encode($extbaseConfig, JSON_PRETTY_PRINT));

            $this->logs[] = 'Exported database schema to ' . $this->filename;

            return true;
        } else {
            $this->logs[] = 'Database does not have any mapping information.';

            return false;
        }
    }

    protected function prepare()
    {
        $this->metadata = $this->cmf->getAllMetadata();
        $this->metadata = MetadataFilter::filter($this->metadata, $this->filter);

        $this->filename = $this->path . '/' . self::PROJECT_FILE;
        if (!is_dir($dir = dirname($this->filename))) {
            mkdir($dir, 0777, true);
        }

        $configFile = $this->path . '/' . self::CONFIG_FILE;
        if (is_readable($configFile)) {
            $this->configuration = Yaml::parse(file_get_contents($configFile));
        }

    }

    /**
     * Gets the extbase property type by for a given type.
     *
     * @param string $type The type to get the extbase property for.
     *
     * @return string
     */
    protected function getPropertyType($type)
    {
        $propertyType = ucfirst($type);
        if (isset(self::$_propertyTypes[$type])) {
            $propertyType = self::$_propertyTypes[$type];
        }

        return $propertyType;
    }

    /**
     * Generate a 12 chars long random integer, convert it to string.
     *
     * @return string
     */
    protected function getRandomUid()
    {
        $uid = rand(100000000000, 999999999999);

        return (string)$uid;
    }

    protected function mapArrayToClass($array, $class)
    {
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

        return $this;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function getCmf()
    {
        return $this->cmf;
    }

    public function setCmf(DisconnectedClassMetadataFactory $cmf)
    {
        $this->cmf = $cmf;

        return $this;
    }

    public function getRoundTripExistingFiles()
    {
        return $this->roundTripExistingFiles;
    }

    public function setRoundTripExistingFiles($roundTripExistingFiles)
    {
        $this->roundTripExistingFiles = $roundTripExistingFiles;

        return $this;
    }
}
