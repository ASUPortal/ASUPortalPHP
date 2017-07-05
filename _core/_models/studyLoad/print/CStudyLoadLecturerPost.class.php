<?php

class CStudyLoadLecturerPost extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Должность преподавателя учебной нагрузки";
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
    	$globalRequestVariables = CRequest::getString("id");
    	$requestVariables = unserialize(urldecode($globalRequestVariables));
    	
    	$lecturer = CStaffManager::getPerson($requestVariables["kadri_id"]);
    	$result = $lecturer->getPost();
    	
    	return $result;
    }
}