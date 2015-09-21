<?php

class CWorkPlanTerms extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Семестры дисциплины";
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
        if (!is_null($contextObject->terms)) {
        	$arr = array(1);
        	foreach ($contextObject->terms->getItems() as $term) {
        		$arr[] = $term->number." семестр";
        	}
        	$result[] = $arr;
        }
        return $result;
    }
}