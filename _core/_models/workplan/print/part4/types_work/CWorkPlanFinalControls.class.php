<?php

class CWorkPlanFinalControls extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Виды итогового контроля";
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
		if (!is_null($contextObject->finalControls)) {
        	$arr = array("");
        	foreach ($contextObject->finalControls->getItems() as $control) {
        		$arr[] = $control->controlType;
        	}
        	$result[] = $arr;
        }
        return $result;
    }
}