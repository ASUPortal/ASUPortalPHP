<?php

class CWorkPlanTotalCredits extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Всего зачетных единиц у дисциплины";
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
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
        $terms = array();
        $termIds = array();
        foreach ($contextObject->terms->getItems() as $term) {
        	$termIds[] = $term->getId();
        }
        $query = new CQuery();
        $query->select("sum(if(l.term_id in (".join(", ", $termIds)."), l.value, 0)) as t_sum")
	        ->from(TABLE_WORK_PLAN_CONTENT_LOADS." as l")
	        ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "l.section_id = section.id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
	        ->condition("category.plan_id = ".$contextObject->getId());
        $objects = $query->execute();
        $result = 0;
        foreach ($objects->getItems() as $key=>$value) {
        	$result += $value["t_sum"];
        }
        return round($result/36, 2);
    }
}