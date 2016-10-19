<?php

class CWorkPlanPracticesNotProvided extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Надпись, если нет практических занятий - Не предусмотрено";
    }

    public function getFieldDescription()
    {
        return "Используется при печати рабочей программы, принимает параметр id с Id рабочей программы";
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
    	if (empty(CWorkPlanPractices::execute($contextObject))) {
    		$result = "Не предусмотрено";
    	}
        return $result;
    }
}