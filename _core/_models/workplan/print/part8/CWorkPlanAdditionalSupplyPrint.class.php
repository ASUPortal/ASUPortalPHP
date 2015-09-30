<?php

class CWorkPlanAdditionalSupplyPrint extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Материальное обеспечение";
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
        if (!is_null($contextObject->additionalSupply)) {
        	foreach ($contextObject->additionalSupply->getItems() as $row) {
	        	$dataRow = array();
	        	$dataRow[0] = (count($result) + 1).".";
	        	$dataRow[1] = $row->supply;
	        	$result[] = $dataRow;
        	}
        }
        return $result;
    }
}