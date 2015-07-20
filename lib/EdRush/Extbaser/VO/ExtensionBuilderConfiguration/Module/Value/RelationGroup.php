<?php

namespace EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value;

use EdRush\Extbaser\VO\ExtensionBuilderConfiguration\Module\Value\RelationGroup\Relation;

/**
 * @author weberius
 */
class RelationGroup
{
    /**
     * @var Relation[]
     */
    public $relations;

    public function addRelation(Relation $relation)
    {
        $this->relations[] = $relation;

        return $this;
    }

	public function getRelations() {
		return $this->relations;
	}
	public function setRelations($relations) {
		$this->relations = $relations;
		return $this;
	}
	
}
