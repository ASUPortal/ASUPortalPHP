<?php

class CStudyLoadYear extends CStudyLoadParameters {
    public function getFieldName()
    {
        return "Год учебной нагрузки";
    }

    public function getFieldType()
    {
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
    	$year = $this->getYear();
    	$result = $year->getValue();
    	return $result;
    }
}