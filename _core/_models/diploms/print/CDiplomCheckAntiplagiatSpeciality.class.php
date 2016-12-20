<?php

class CDiplomCheckAntiplagiatSpeciality extends CAbstractPrintClassField {
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
    	if ($contextObject->checksOnAntiplagiat->getCount() != 0) {
    		$student = $contextObject->checksOnAntiplagiat->getLastItem()->diplom->student;
    		if (!is_null($student)) {
    			$group = $student->group;
    			if (!is_null($group)) {
    				$corriculum = $group->corriculum;
    				if (!is_null($corriculum)) {
    					$result = $corriculum->speciality_direction->getValue();
    				}
    			}
    		}
    	}
        return $result;
    }
}