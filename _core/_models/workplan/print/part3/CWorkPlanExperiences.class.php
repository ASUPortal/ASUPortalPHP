<?php

class CWorkPlanExperiences extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Навыки";
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
    			//умения формируемых компетенций рабочей программы
    			if ($item->type == 0) {
    				if (!is_null($item->experiences)) {
    					foreach ($item->experiences->getItems() as $item) {
    						$dataRow = array();
    						$dataRow[0] = "•";
    						$experiences = array();
    						if (!is_null($item->experience)) {
    							$experiences[] = $item->experience->getValue();
    						}
    						$dataRow[1] = implode("; ", $experiences);
    						$result[] = $dataRow;
    					}
    				}
    			}
    		}
    	}
    	return $result;
    }
}