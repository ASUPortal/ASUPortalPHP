<?php

class CDiplomAntiplagiatCheckStudentGroup extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Группа студента темы ВКР";
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
    			$group = $student->group;
    			if (!is_null($group)) {
    				$result = $group->getName();
    			}
    		}
    	}
        return $result;
    }
}