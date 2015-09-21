<?php

class CWorkPlanStructure extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Структура дисциплины";
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
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $terms = array();
        $terms[] = "term.name";
        $termIds = array();
        foreach ($plan->terms->getItems() as $term) {
        	$termIds[] = $term->getId();
        	$terms[] = "sum(if(l.term_id = ".$term->getId().", l.value, 0)) as t_".$term->getId();
        }
        if (count($termIds) > 0) {
        	$terms[] = "sum(if(l.term_id in (".join(", ", $termIds)."), l.value, 0)) as t_sum";
        }
        $query = new CQuery();
        $query->select(join(", ", $terms))
	        ->from(TABLE_WORK_PLAN_CONTENT_LOADS." as l")
	        ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "l.section_id = section.id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_MODULES." as module", "section.module_id = module.id")
	        ->condition("module.plan_id = ".$plan->getId())
	        ->group("l.load_type_id")
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