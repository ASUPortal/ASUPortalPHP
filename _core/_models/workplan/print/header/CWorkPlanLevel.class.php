<?php

class CWorkPlanLevel extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Уровень подготовки";
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
		if (!is_null($contextObject->level)) {
			$result = $contextObject->level->getValue();
		}
        return $result;
    }
}