<?php

class CWorkPlanTotalHours extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Всего часов у дисциплины";
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
	        ->condition("category.plan_id = ".$contextObject->getId()." and l._deleted = 0 and category._deleted = 0");
        $objects = $query->execute();
        $result = 0;
        foreach ($objects->getItems() as $key=>$value) {
        	$result += $value["t_sum"];
        }
        if (!is_null($contextObject->finalControls)) {
        	foreach ($contextObject->finalControls->getItems() as $control) {
        		$item = $control->controlType;
        	}
        }
        if ($item == "Зачет") {
        	$result += 9;
        } else {
        	$result += 36;
        }
        return $result;
    }
}