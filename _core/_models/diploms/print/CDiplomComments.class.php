<?php

class CDiplomComments extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Комментарии к антиплагиату темы ВКР";
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
    	if ($contextObject->comments != "") {
    		$result = $contextObject->comments;
    	} else {
    		$result = "не указано";
    	}
        return $result;
    }
}