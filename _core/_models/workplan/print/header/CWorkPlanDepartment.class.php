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
		if (!is_null($contextObject->department_id)) {
			$result = CTaxonomyManager::getTerm($contextObject->department_id)->name;
		}
        return $result;
    }
}