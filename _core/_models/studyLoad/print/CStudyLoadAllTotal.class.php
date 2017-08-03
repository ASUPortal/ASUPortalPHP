<?php

class CStudyLoadAllTotal extends CStudyLoadParameters {
    public function getFieldName()
    {
        return "Всего за год по учебной нагрузке (без учёта филиалов)";
    }

    public function getFieldType()
    {
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
    	$lecturer = $this->getLecturer();
    	$year = $this->getYear();
    	$loadTypes = $this->getLoadTypes();
    	
    	$value = CStudyLoadService::getAllStudyWorksTotalValuesByLecturer($lecturer, $year, $loadTypes);
    	if ($value == 0) {
    		$result = "";
    	} else {
    		$result = number_format($value,1,',','');
    	}
    	return $result;
    }
}