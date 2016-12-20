<?php

class CDiplomAntiplagiatCheckCitations extends CAbstractPrintClassField {
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
    	$result = "";
    	if ($contextObject->antiplagiatChecks->getCount() != 0) {
    		$result = $contextObject->antiplagiatChecks->getLastItem()->citations_percent;
    	}
        return $result;
    }
}