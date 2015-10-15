<?php

class CWorkPlanDisciplinesAfter extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Последующие дисциплины";
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
    	if (!is_null($contextObject->disciplinesAfter)) {
    		foreach ($contextObject->disciplinesAfter->getItems() as $item) {
    			$dataRow = array();
    			$dataRow[0] = "•";
    			$dataRow[1] = $item->discipline->getValue();
    			$result[] = $dataRow;
    		}
    	}
    	return $result;
    }
}