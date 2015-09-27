<?php

class CWorkPlanCanUse extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Умеет использовать";
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
    	if (!is_null($contextObject->competentions)) {
    		foreach ($contextObject->competentions->getItems() as $item) {
    			if ($item->discipline_id == 0) {
    				if (!is_null($item->canUse)) {
    					foreach ($item->canUse->getItems() as $item) {
    						$dataRow = array();
    						$dataRow[0] = "•";
    						$dataRow[1] = $item->getValue();
    						$result[] = $dataRow;
    					}
    				}
    			}
    		}
    	}
    	return $result;
    }
}