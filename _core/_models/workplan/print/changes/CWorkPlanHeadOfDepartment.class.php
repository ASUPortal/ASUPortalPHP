<?php

class CWorkPlanHeadOfDepartment extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Заведующий кафедрой";
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
		$result = CStaffManager::getPersonByPostId(HEAD_OF_DEPARTMENT)->getNameShort();
        return $result;
    }
}