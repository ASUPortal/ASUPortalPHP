<?php

class CStudyLoadLecturer extends CStudyLoadParameters {
    public function getFieldName()
    {
        return "Преподаватель учебной нагрузки";
    }

    public function getFieldType()
    {
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
    	$lecturer = $this->getLecturer();
    	$result = $lecturer->getNameShort();
    	return $result;
    }
}