<?php

class CWorkPlanIntermediateControls extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Виды промежуточного контроля";
    }

    public function getFieldDescription()
    {
        return "Используется при печати рабочей программы, принимает параметр id с Id рабочей программы";
    }

    public function getParentClassField()
    {

    }

    public function getFieldType()
    {
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
		$result = array();
		$terms = array("term.name");
		foreach ($contextObject->terms->getItems() as $term) {
			$terms[] = "sum(if(l.term_id = ".$term->getId().", ".CTaxonomyManager::getTaxonomy("corriculum_final_control_hours")->getTerm("intermediateControl")->getValue().", 0)) as t_".$term->getId();
		}
		$query = new CQuery();
		$query->select(join(", ", $terms))
			->from(TABLE_WORK_PLAN_INTERMEDIATE_CONTROL." as l")
			->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.control_type_id")
			->condition("l.plan_id = ".$contextObject->getId()." and l._deleted = 0")
			->group("l.control_type_id")
			->order("term.name");
		$objects = $query->execute();
		foreach ($objects->getItems() as $key=>$value) {
			$arr = array_values($value);
			$dataRow = array();
			for ($i = 0; $i <= count($value)-1; $i++) {
				$dataRow[$i] = $arr[$i];
			}
			$result[] = $dataRow;
		}
        return $result;
    }
}