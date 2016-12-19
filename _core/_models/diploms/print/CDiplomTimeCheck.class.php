<?php

class CDiplomTimeCheck extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Время проверки ВКР на антиплагиат";
    }

    public function getFieldDescription()
    {
        return "Используется при печати тем ВКР, принимает параметр id с Id темы ВКР";
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
    	if ($contextObject->time_check !== "") {
    		$result = substr($contextObject->time_check, 0, strpos($contextObject->time_check, ":"))." час. ";
    		$result .= substr($contextObject->time_check, strpos($contextObject->time_check, ":") + 1)." мин.";
    	}
        return $result;
    }
}