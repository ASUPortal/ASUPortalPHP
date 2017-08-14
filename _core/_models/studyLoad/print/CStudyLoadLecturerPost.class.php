<?php

class CStudyLoadLecturerPost extends CStudyLoadParameters {
    public function getFieldName()
    {
        return "Должность преподавателя учебной нагрузки";
    }

    public function getFieldType()
    {
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
    	$lecturer = $this->getLecturer();
    	$result = $lecturer->getPost();
    	return $result;
    }
}