<?php

class CDiplomAntiplagiatCheckDate extends CAbstractPrintClassField {
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
    	if ($contextObject->antiplagiatChecks->getCount() != 0) {
    		if ($contextObject->antiplagiatChecks->getLastItem()->check_date !== "") {
    			$result = date("d.m.Y", strtotime($contextObject->antiplagiatChecks->getLastItem()->check_date));
    		}
    	}
        return $result;
    }
}