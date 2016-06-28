<?php

class CCorriculumDisciplineName extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Название дисциплины учебного плана";
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
    	if (!is_null($contextObject->discipline)) {
    		$result = $contextObject->discipline->getValue();
    	}
    	return $result;
    }
}