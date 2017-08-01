<?php

class CStudyLoadLecturerPost extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Должность преподавателя учебной нагрузки";
    }

    public function getFieldDescription()
    {
        return "Используется при печати учебной нагрузки, принимает параметр url (значения параметров) учебной нагрузки";
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
    	$url = CRequest::getString("id");
    	 
    	$lecturer = CStaffManager::getPerson(UrlBuilder::getValueByParam($url, "kadri_id"));
    	$result = $lecturer->getPost();
    	
    	return $result;
    }
}