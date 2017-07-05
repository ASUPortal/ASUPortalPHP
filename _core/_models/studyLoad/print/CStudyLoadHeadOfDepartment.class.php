<?php

class CStudyLoadHeadOfDepartment extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Заведующий кафедрой";
    }

    public function getFieldDescription()
    {
        return "Используется при печати учебной нагрузки, принимает параметр globalRequestVariables (значения глобальных переменных запроса) учебной нагрузки";
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
		$result = CStaffService::getHeadOfDepartment();
        return $result;
    }
}