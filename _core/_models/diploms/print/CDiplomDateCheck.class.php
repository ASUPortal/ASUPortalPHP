<?php

class CDiplomDateCheck extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Дата проверки ВКР на антиплагиат";
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
    	if ($contextObject->date_check !== "") {
    		$result = date("d.m.Y", strtotime($contextObject->date_check));
    	}
        return $result;
    }
}