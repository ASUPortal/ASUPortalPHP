<?php

class CStudyLoadYear extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Год учебной нагрузки";
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
    	
    	$year = CTaxonomyManager::getYear(UrlBuilder::getValueByParam($url, "year_id"));
    	$result = $year->getValue();
    	
    	return $result;
    }
}