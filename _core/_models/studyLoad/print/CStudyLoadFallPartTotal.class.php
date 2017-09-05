<?php

class CStudyLoadFallPartTotal extends CStudyLoadParameters {
    public function getFieldName()
    {
        return "Итого по учебной нагрузке за осенний семестр";
    }
    
    /**
     * Осенний семестр из учебной нагрузки
     *
     * @return CYearPart
     */
    public function getYearPart()
    {
    	return CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::FALL);
    }

    public function getFieldType()
    {
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
    	$lecturer = $this->getLecturer();
    	$year = $this->getYear();
    	$loadTypes = $this->getLoadTypes();
    	$part = $this->getYearPart();
    	
    	$result = array();
    	$dataRow = array();
    	$dataRow[0] = "Итого часов:";
    	$dataRow[1] = "";
    	$dataRow[2] = "";
    	$dataRow[3] = "";
    	$dataRow[4] = "";
    	$dataRow[5] = "";
    	$dataRow[6] = "";
    	$row = 7;
    	foreach (CStudyLoadService::getStudyWorksTotalValuesByLecturerAndPart($lecturer, $year, $part, $loadTypes)->getItems() as $typeId=>$rows) {
    		foreach ($rows as $kindId=>$value) {
    			if (!in_array($kindId, array(0))) {
    				if ($value == 0) {
    					$dataRow[$row] = "";
    				} else {
    					$dataRow[$row] = number_format($value,1,',','');
    				}
    			}
    		}
    		$row++;
    	}
    	$rowTotal = CStudyLoadService::getStudyWorksTotalValuesByLecturerAndPart($lecturer, $year, $part, $loadTypes)->getCount()+7;
    	$dataRow[$rowTotal] = number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturerAndPart($lecturer, $year, $part, $loadTypes),1,',','');
    	/*
    	$onFilial = CStudyLoadService::getAllStudyWorksTotalValuesByLecturerAndPartWithFilials($lecturer, $year, $part, $loadTypes);
    	if ($onFilial != 0) {
    		$dataRow[$rowTotal+1] = number_format($onFilial,1,',','');
    	} else {
    		$dataRow[$rowTotal+1] = "";
    	}
    	*/
    	$result[] = $dataRow;
    	
    	return $result;
    }
}