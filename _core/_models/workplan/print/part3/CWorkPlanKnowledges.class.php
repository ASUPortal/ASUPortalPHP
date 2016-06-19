<?php

class CWorkPlanKnowledges extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Знания";
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
    			//знания формируемых компетенций рабочей программы
    			if ($item->type == 0) {
    				if (!is_null($item->knowledges)) {
    					foreach ($item->knowledges->getItems() as $item) {
    						$dataRow = array();
    						$dataRow[0] = "•";
    						$knowledges = array();
    						if (!is_null($item->knowledge)) {
    							$knowledges[] = $item->knowledge->getValue();
    						}
    						$dataRow[1] = implode("; ", $knowledges);
    						$result[] = $dataRow;
    					}
    				}
    			}
    		}
    	}
    	return $result;
    }
}