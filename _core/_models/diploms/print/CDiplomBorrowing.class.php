<?php

class CDiplomBorrowing extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Процент заимствований ВКР на антиплагиат";
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
    	$result = $contextObject->borrowing_percent;
        return $result;
    }
}