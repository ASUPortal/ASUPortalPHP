<?php

class CWorkPlanTermSectionsFirstFinalControl extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Вид итогового контроля для первого семестра в списке";
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
    	$result = "";
    	if (!is_null($contextObject->terms)) {
    		$terms = array();
    		foreach ($contextObject->terms->getItems() as $term) {
    			$terms[] = $term->number;
    		}
    	}
    	$queryControl = new CQuery();
        $queryControl->select("term.name as name, l.term_id as termId")
	        ->from(TABLE_WORK_PLAN_CONTENT_FINAL_CONTROL." as l")
	        ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.control_type_id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "l.section_id = section.id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
	        ->condition("category.plan_id = ".$contextObject->getId())
	        ->group("l.control_type_id")
	        ->order("name");
        $finalControls = $queryControl->execute();
        foreach ($finalControls->getItems() as $control) {
        	if (CBaseManager::getWorkPlanTerm($control["termId"]) == $terms[0]) {
        		$result = $control["name"];
        	}
        }
        return $result;
    }
}