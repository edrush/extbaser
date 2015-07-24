<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module;

use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\ActionGroup;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\ObjectSettings;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\PropertyGroup;
use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\RelationGroup;

/**
 * @author weberius
 */
class Value
{
    /**
     * @var ActionGroup
     */
    public $actionGroup;

    /**
     * @var string
     */
    public $name;

    /**
     * @var ObjectSettings
     */
    public $objectsettings;

    /**
     * @var PropertyGroup
     */
    public $propertyGroup;

    /**
     * @var RelationGroup
     */
    public $relationGroup;

    public function __construct()
    {
        $this->actionGroup = new ActionGroup();
        $this->objectsettings = new ObjectSettings();
        $this->propertyGroup = new PropertyGroup();
        $this->relationGroup = new RelationGroup();
    }

    public function getActionGroup()
    {
        return $this->actionGroup;
    }
    public function setActionGroup(ActionGroup $actionGroup)
    {
        $this->actionGroup = $actionGroup;

        return $this;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    public function getPropertyGroup()
    {
        return $this->propertyGroup;
    }
    public function setPropertyGroup(PropertyGroup $propertyGroup)
    {
        $this->propertyGroup = $propertyGroup;

        return $this;
    }
    public function getObjectsettings()
    {
        return $this->objectsettings;
    }
    public function setObjectsettings(ObjectSettings $objectsettings)
    {
        $this->objectsettings = $objectsettings;

        return $this;
    }
    public function getRelationGroup()
    {
        return $this->relationGroup;
    }
    public function setRelationGroup(RelationGroup $relationGroup)
    {
        $this->relationGroup = $relationGroup;

        return $this;
    }
}
