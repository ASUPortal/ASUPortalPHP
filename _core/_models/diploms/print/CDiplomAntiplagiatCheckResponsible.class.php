<?php

class CDiplomAntiplagiatCheckResponsible extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Ответственный проверки ВКР на антиплагиат";
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
    		$responsible = $contextObject->antiplagiatChecks->getLastItem()->responsible;
    		if (!is_null($responsible)) {
    			$result = $responsible->getNameShort();
    		}
    	}
        return $result;
    }
}