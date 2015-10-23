<?php

class CWorkPlanSoftwarePrint extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Программное обеспечение";
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
        if (!is_null($contextObject->software)) {
        	foreach ($contextObject->software->getItems() as $row) {
	        	$dataRow = array();
	        	$dataRow[0] = (count($result) + 1).".";
	        	$dataRow[1] = $row->software;
	        	$result[] = $dataRow;
        	}
        }
        return $result;
    }
}