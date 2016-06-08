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
    	$result = 0;
    	$discipline = CCorriculumsManager::getDiscipline($contextObject->corriculum_discipline_id);
        $terms = array();
        $termIds = array();
        foreach ($contextObject->terms->getItems() as $term) {
        	$termIds[] = $term->getId();
        }
        $sections = array();
        foreach ($contextObject->categories->getItems() as $category) {
        	foreach ($category->sections->getItems() as $section) {
        		$sections[] = $section->getId();
        	}
        }
        if (count($termIds) > 0 and !empty($sections)) {
        	$query = new CQuery();
        	$query->select("sum(if(l.term_id in (".join(", ", $termIds)."), l.value, 0)) as t_sum")
	        	->from(TABLE_WORK_PLAN_CONTENT_LOADS." as l")
	        	->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
	        	->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "l.section_id = section.id")
	        	->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
	        	->condition("category.plan_id = ".$contextObject->getId()." and l._deleted = 0 and category._deleted = 0");
        	$objects = $query->execute();
        	$res = 0;
        	foreach ($objects->getItems() as $key=>$value) {
        		$res += $value["t_sum"];
        	}
        	if (!is_null($contextObject->finalControls)) {
        		foreach ($contextObject->finalControls->getItems() as $control) {
        			$item = $control->controlType;
        		}
        	}
        	if (isset($item) && $item == "Зачет") {
        		$res += 9;
        	} elseif(isset($item)) {
        		$res += 36;
        	}
        	$result = round($res/36, 2);
        } else {
        	$result = round($discipline->getLaborValue()/36, 2);
        }
        return $result;
    }
}