<?php

class CDiplomAntiplagiatCheckTeacher extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Руководитель темы ВКР";
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
    		$person = $contextObject->antiplagiatChecks->getLastItem()->diplom->person;
    		if (!is_null($person)) {
    			$result = $person->getName();
    		}
    	}
        return $result;
    }
}