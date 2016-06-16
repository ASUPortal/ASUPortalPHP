<?php

class CWorkPlanTermSecond extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Второй семестр в списке";
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
    	$discipline = CCorriculumsManager::getDiscipline($contextObject->corriculum_discipline_id);
    	if (!empty($contextObject->terms->getItems())) {
    		$terms = array();
    		foreach ($contextObject->terms->getItems() as $term) {
    			$terms[] = $term->corriculum_discipline_section->title;
    		}
    	} else {
    		if (!empty($discipline->sections->getItems())) {
    			$terms = array();
    			foreach ($discipline->sections->getItems() as $section) {
    				$terms[] = $section->title;
    			}
    		}
    	}
        $result = $terms[1];
        return $result;
    }
}