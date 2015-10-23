<?php

class CWorkPlanDescriptionMarks extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Описание баллов по каждому виду учебной деятельности";
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
    	foreach ($contextObject->getControlTypes()->getItems() as $item) {
    		$dataRow = array();
    		$dataRow[0] = $item->type->getValue();
    		if (!is_null($item->marks)) {
    			$marks = array();
    			foreach ($item->marks->getItems() as $control) {
    				$marks[] = $control->mark;
    			}
    			$dataRow[1] = implode("; ", $marks);
    		}
    		$result[] = $dataRow;
    	}
    	return $result;
    }
}