<?php

class CStudyLoadSpringPartTotal extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Итого по учебной нагрузке за весенний семестр";
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
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
    	$globalRequestVariables = CRequest::getString("id");
    	$requestVariables = unserialize(urldecode($globalRequestVariables));
    	
    	$lecturer = CStaffManager::getPerson($requestVariables["kadri_id"]);
    	$year = CTaxonomyManager::getYear($requestVariables["year_id"]);
    	$part = CStudyLoadYearPartsConstants::SPRING;
    	$loadTypes = CStudyLoadService::getLoadTypesByGlobalRequestVariables($requestVariables);
    	 
    	$result = array();
    	$dataRow = array();
    	$dataRow[0] = "Итого часов:";
    	$dataRow[1] = "";
    	$dataRow[2] = "";
    	$dataRow[3] = "";
    	$dataRow[4] = "";
    	$dataRow[5] = "";
    	$dataRow[6] = "";
    	$i = 7;
    	foreach (CStudyLoadService::getStudyWorksTotalValuesByLecturerAndPart($lecturer, $year, $part, $loadTypes)->getItems() as $typeId=>$rows) {
    		foreach ($rows as $kindId=>$value) {
    			if (!in_array($kindId, array(0))) {
    				if ($value == 0) {
    					$dataRow[$i] = "";
    				} else {
    					$dataRow[$i] = number_format($value,1,',','');
    				}
    			}
    		}
    		$i++;
    	}
    	$k = CStudyLoadService::getStudyWorksTotalValuesByLecturerAndPart($lecturer, $year, $part, $loadTypes)->getCount()+7;
    	$dataRow[$k] = number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturerAndPart($lecturer, $year, $part, $loadTypes),1,',','');
    	$onFilial = CStudyLoadService::getAllStudyWorksTotalValuesByLecturerAndPartWithFilials($lecturer, $year, $part, $loadTypes);
    	if ($onFilial != 0) {
    		$dataRow[$k+1] = number_format($onFilial,1,',','');
    	} else {
    		$dataRow[$k+1] = "";
    	}
    	$result[] = $dataRow;
    	
    	return $result;
    }
}