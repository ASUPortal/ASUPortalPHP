<?php

class CWorkPlanSkills extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Умения";
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
    			if ($item->type == 0) {
    				if (!is_null($item->skills)) {
    					foreach ($item->skills->getItems() as $item) {
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