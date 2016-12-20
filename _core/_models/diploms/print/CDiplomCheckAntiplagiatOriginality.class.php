<?php

class CDiplomCheckAntiplagiatOriginality extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Процент оригинальности ВКР на антиплагиат";
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
    	if ($contextObject->checksOnAntiplagiat->getCount() != 0) {
    		$result = $contextObject->checksOnAntiplagiat->getLastItem()->originality_percent;
    	}
        return $result;
    }
}