<?php

class CDepartmentProtocolHeadOfDepartment extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Заведующий кафедрой";
    }

    public function getFieldDescription()
    {
        return "Используется при печати протокола кафедры, принимает параметр id с Id протокола кафедры";
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