<?php

class CWorkPlanCompetentionsPK extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Компетенции ПК";
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
    			//формируемые компетенции рабочей программы
    			if ($item->type == 0) {
    				if (!is_null($item->competention)) {
    					if (strpos($item->competention->getValue(), "(ПК-") !== false) {
    						$dataRow = array();
    						$dataRow[0] = "•";
    						$dataRow[1] = $item->competention->getValue();
    						$result[] = $dataRow;
    					}
    				}
    			}
    		}
    	}
    	return $result;
    }
}