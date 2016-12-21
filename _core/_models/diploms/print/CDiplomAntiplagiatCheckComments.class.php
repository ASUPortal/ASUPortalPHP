<?php

class CDiplomAntiplagiatCheckComments extends CAbstractPrintClassField {
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
    	$result = "не указано";
    	if ($contextObject->antiplagiatChecks->getCount() != 0) {
    		if ($contextObject->antiplagiatChecks->getLastItem()->comments != "") {
    			$result = $contextObject->antiplagiatChecks->getLastItem()->comments;
    		}
    	}
        return $result;
    }
}