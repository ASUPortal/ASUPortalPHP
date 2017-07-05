<?php

class CStudyLoadAllTotal extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Всего за год по учебной нагрузке (без учёта филиалов)";
    }

    public function getFieldDescription()
    {
        return "Используется при печати учебной нагрузки, принимает параметр globalRequestVariables (значения глобальных переменных запроса) учебной нагрузки";
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
    	$globalRequestVariables = CRequest::getString("id");
    	$requestVariables = unserialize(urldecode($globalRequestVariables));
    	
    	$lecturer = CStaffManager::getPerson($requestVariables["kadri_id"]);
    	$year = CTaxonomyManager::getYear($requestVariables["year_id"]);
    	$part = CStudyLoadYearPartsConstants::SPRING;
    	$loadTypes = CStudyLoadService::getLoadTypesByGlobalRequestVariables($requestVariables);
    	
    	$value = CStudyLoadService::getAllStudyWorksTotalValuesByLecturer($lecturer, $year, $loadTypes);
    	
    	if ($value == 0) {
    		$result = "";
    	} else {
    		$result = number_format($value,1,',','');
    	}
    	
    	return $result;
    }
}