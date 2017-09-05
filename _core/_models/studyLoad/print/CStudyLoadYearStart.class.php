<?php

class CStudyLoadYearStart extends CStudyLoadParameters {
    public function getFieldName()
    {
        return "Дата начала года учебной нагрузки";
    }

    public function getFieldType()
    {
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
    	$year = $this->getYear();
    	$result = date("Y", strtotime($year->date_start));
    	return $result;
    }
}