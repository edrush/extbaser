<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value;

/**
 * @author weberius
 */
class ObjectSettings
{
    const OBJECT_TYPE_ENTITY = 'Entity';
    const OBJECT_TYPE_VALUE_OBJECT = 'ValueObject';

    /**
     * @var bool
     */
    public $addDeletedField = true;

    /**
     * @var bool
     */
    public $addHiddenField = true;

    /**
     * @var bool
     */
    public $addStarttimeEndtimeFields = true;

    /**
     * @var bool
     */
    public $aggregateRoot = false;

    /**
     * @var bool
     */
    public $categorizable = false;

    /**
     * @var string
     */
    public $description = '';

    /**
     * @var string
     */
    public $mapToTable = '';

    /**
     * @var string
     */
    public $parentClass = '';

    /**
     * @var bool
     */
    public $sorting = false;

    /**
     * @var string
     */
    public $type = '';

    /**
     * @var string
     */
    public $uid = '0';

    public function getAddDeletedField()
    {
        return $this->addDeletedField;
    }
    public function setAddDeletedField($addDeletedField)
    {
        $this->addDeletedField = $addDeletedField;

        return $this;
    }
    public function getAddHiddenField()
    {
        return $this->addHiddenField;
    }
    public function setAddHiddenField($addHiddenField)
    {
        $this->addHiddenField = $addHiddenField;

        return $this;
    }
    public function getAddStarttimeEndtimeFields()
    {
        return $this->addStarttimeEndtimeFields;
    }
    public function setAddStarttimeEndtimeFields($addStarttimeEndtimeFields)
    {
        $this->addStarttimeEndtimeFields = $addStarttimeEndtimeFields;

        return $this;
    }
    public function getAggregateRoot()
    {
        return $this->aggregateRoot;
    }
    public function setAggregateRoot($aggregateRoot)
    {
        $this->aggregateRoot = $aggregateRoot;

        return $this;
    }
    public function getCategorizable()
    {
        return $this->categorizable;
    }
    public function setCategorizable($categorizable)
    {
        $this->categorizable = $categorizable;

        return $this;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
    public function getMapToTable()
    {
        return $this->mapToTable;
    }
    public function setMapToTable($mapToTable)
    {
        $this->mapToTable = $mapToTable;

        return $this;
    }
    public function getParentClass()
    {
        return $this->parentClass;
    }
    public function setParentClass($parentClass)
    {
        $this->parentClass = $parentClass;

        return $this;
    }
    public function getSorting()
    {
        return $this->sorting;
    }
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;

        return $this;
    }
    public function getType()
    {
        return $this->type;
    }
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
    public function getUid()
    {
        return $this->uid;
    }
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }
}
