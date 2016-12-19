<?php

class CDiplomSpeciality extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Специальность студента темы ВКР";
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
    	$student = $contextObject->student;
    	if (!is_null($student)) {
    		$group = $contextObject->student->group;
    		if (!is_null($group)) {
    			$corriculum = $contextObject->student->group->corriculum;
    			if (!is_null($corriculum)) {
    				$result = $corriculum->speciality_direction->getValue();
    			}
    		}
    	}
        return $result;
    }
}