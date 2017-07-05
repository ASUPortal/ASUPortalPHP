<?php

class CStudyLoadYear extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Год учебной нагрузки";
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
    	
    	$year = CTaxonomyManager::getYear($requestVariables["year_id"]);
    	$result = $year->getValue();
    	
    	return $result;
    }
}