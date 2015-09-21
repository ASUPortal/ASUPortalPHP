<?php

class CWorkPlanTasks extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Задачи";
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
    	if (!is_null($contextObject->tasks)) {
    		foreach ($contextObject->tasks->getItems() as $item) {
    			$dataRow = array();
    			$dataRow[0] = "•";
    			$dataRow[1] = $item->task;
    			$result[] = $dataRow;
    		}
    	}
    	return $result;
    }
}