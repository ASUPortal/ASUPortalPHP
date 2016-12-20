<?php

class CDiplomCheckAntiplagiatComments extends CAbstractPrintClassField {
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
    	if ($contextObject->checksOnAntiplagiat->getCount() != 0) {
    		if ($contextObject->checksOnAntiplagiat->getLastItem()->comments_on_antiplagiat != "") {
    			$result = $contextObject->checksOnAntiplagiat->getLastItem()->comments_on_antiplagiat;
    		}
    	}
        return $result;
    }
}