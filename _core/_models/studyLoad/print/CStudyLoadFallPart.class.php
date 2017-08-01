<?php

class CStudyLoadFallPart extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Значения по учебной нагрузке за осенний семестр";
    }

    public function getFieldDescription()
    {
        return "Используется при печати учебной нагрузки, принимает параметр url (значения параметров) учебной нагрузки";
    }

    public function getParentClassField()
    {

    }
    
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
    	$url = CRequest::getString("id");
    	$loads = CStudyLoadService::getStudyLoadsByYearAndLoadTypeByUrl($url);
    	
    	$studyLoads = CStudyLoadService::getStudyLoadsByPart($loads, $this->getYearPart());
    	 
    	$result = array();
    	foreach ($studyLoads->getItems() as $studyLoad) {
    		$dataRow = array();
    		$dataRow[0] = count($result) + 1;
    		$dataRow[1] = $studyLoad->discipline->getValue();
    		$dataRow[2] = "ИРТ";
    		$dataRow[3] = $studyLoad->direction->getValue();
    		$dataRow[4] = $studyLoad->studyLevel->name;
    		$dataRow[5] = $studyLoad->groups_count;
    		$dataRow[6] = $studyLoad->students_count + $studyLoad->students_contract_count;
    		$row = 7;
    		foreach ($studyLoad->getStudyLoadTable()->getTableTotal() as $typeId=>$rows) {
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
    		$rowTotal = count($studyLoad->getStudyLoadTable()->getTableTotal())+7;
    		$dataRow[$rowTotal] = number_format($studyLoad->getSumWorksValue(),1,',','');
    		if ($studyLoad->on_filial) {
    			$dataRow[$rowTotal+1] = number_format($studyLoad->getWorkWithFilialsTotals(),1,',','');
    		} else {
    			$dataRow[$rowTotal+1] = "";
    		}
    		$result[] = $dataRow;
    	}
    	return $result;
    }
}