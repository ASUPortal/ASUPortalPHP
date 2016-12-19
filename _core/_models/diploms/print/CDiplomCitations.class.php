<?php

class CDiplomCitations extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Процент цитирования ВКР на антиплагиат";
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
    	$result = $contextObject->citations;
        return $result;
    }
}