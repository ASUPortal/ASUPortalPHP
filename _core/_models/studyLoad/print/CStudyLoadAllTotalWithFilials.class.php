<?php

class CStudyLoadAllTotalWithFilials extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Всего за год по учебной нагрузке (с учётом филиалов)";
    }

    public function getFieldDescription()
    {
        return "Используется при печати учебной нагрузки, принимает параметр url (значения параметров) учебной нагрузки";
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
    	$url = CRequest::getString("id");
    	 
    	$lecturer = CStaffManager::getPerson(UrlBuilder::getValueByParam($url, "kadri_id"));
    	$year = CTaxonomyManager::getYear(UrlBuilder::getValueByParam($url, "year_id"));
    	$part = CStudyLoadYearPartsConstants::SPRING;
    	$loadTypes = CStudyLoadService::getLoadTypesByUrl($url);
    	
    	$value = CStudyLoadService::getAllStudyWorksTotalValuesByLecturerWithFilials($lecturer, $year, $loadTypes);
    	
    	if ($value == 0) {
    		$result = "";
    	} else {
    		$result = number_format($value,1,',','');
    	}
    	
    	return $result;
    }
}