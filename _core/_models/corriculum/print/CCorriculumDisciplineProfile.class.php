<?php

class CCorriculumDisciplineProfile extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Профиль дисциплины учебного плана";
    }

    public function getFieldDescription()
    {
        return "Используется при печати дисциплин учебного плана, принимает параметр id с Id дисциплины учебного плана";
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
    	$corriculum = CCorriculumsManager::getCorriculum($contextObject->cycle->corriculum->getId());
    	if (!is_null($corriculum->profile)) {
    		$result = $corriculum->profile->getValue();
    	}
    	return $result;
    }
}