<?php

class CWorkPlanDepartment extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Кафедра";
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
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
    	$result = "";
		if (!is_null($contextObject->department)) {
			$result = $contextObject->department->getValue();
		}
        return $result;
    }
}