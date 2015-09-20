<?php

class CWorkPlanTermFirst extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Первый семестр в списке";
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
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
		if (!is_null($plan->terms)) {
        	$terms = array();
        	foreach ($plan->terms->getItems() as $term) {
        		$terms[] = $term->number;
        	}
        }
        $result = $terms[0];
        return $result;
    }
}