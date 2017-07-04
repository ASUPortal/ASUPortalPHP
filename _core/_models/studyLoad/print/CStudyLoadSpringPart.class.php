<?php

class CStudyLoadSpringPart extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Значения по учебной нагрузке за весенний семестр";
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
    	$loads = CStudyLoadService::getStudyLoadsByYearAndLoadTypeByGlobalRequestVariables($globalRequestVariables);
    	
    	$studyLoads = CStudyLoadService::getStudyLoadsByPart($loads, CStudyLoadYearPartsConstants::SPRING);
    	 
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
    		$i = 7;
    		foreach ($studyLoad->getStudyLoadTable()->getTableTotal() as $typeId=>$rows) {
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
    		$k = count($studyLoad->getStudyLoadTable()->getTableTotal())+7;
    		$dataRow[$k] = number_format($studyLoad->getSumWorksValue(),1,',','');
    		if ($studyLoad->on_filial == 0) {
    			$dataRow[$k+1] = "";
    		} else {
    			$dataRow[$k+1] = $studyLoad->on_filial;
    		}
    		$result[] = $dataRow;
    	}
    	return $result;
    }
}