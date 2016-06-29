<?php

class CCorriculumDirection extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Направление/специальность учебного плана";
    }

    public function getFieldDescription()
    {
        return "Используется при печати учебных планов, принимает параметр id с Id учебного плана";
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
    	if (!is_null($contextObject->speciality_direction)) {
    		$result = $contextObject->speciality_direction->getValue();
    	}
    	return $result;
    }
}