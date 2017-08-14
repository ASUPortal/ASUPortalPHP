<?php

class CStudyLoadHeadOfDepartment extends CStudyLoadParameters {
    public function getFieldName()
    {
        return "Заведующий кафедрой";
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