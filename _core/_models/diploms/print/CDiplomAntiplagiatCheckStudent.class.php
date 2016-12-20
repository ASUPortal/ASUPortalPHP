<?php

class CDiplomAntiplagiatCheckStudent extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Студент темы ВКР";
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
    		$student = $contextObject->antiplagiatChecks->getLastItem()->diplom->student;
    		if (!is_null($student)) {
    			$result = $student->getName();
    		}
    	}
        return $result;
    }
}