<?php

class CIndPlanChangesLoad extends CAbstractPrintClassField{
    public function getFieldName()
    {
        return "Записи об изменениях в Годовом индивидуальном плане";
    }

    public function getFieldDescription()
    {
        return "Используется при печати индивидуального плана, принимает параметр planId с Id плана";
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
        $result = array();
        $studyLoad = new CArrayList();
        if (!is_null(CIndPlanManager::getLoad(CRequest::getInt("planId")))) {
            $load = CIndPlanManager::getLoad(CRequest::getInt("planId"));
        } else {
            $load = CIndPlanManager::getLoad($contextObject->getId());
        }
        $studyLoad = $load->getWorksByType(CIndPlanPersonWorkType::CHANGE_RECORDS);
        foreach ($studyLoad->getItems() as $row) {
        	$dataRow = array();
        	$dataRow[0] = count($result) + 1;
        	$dataRow[1] = $row->change_section;
        	$dataRow[2] = $row->change_reason;
        	$dataRow[3] = "";
        	$dataRow[4] = $row->change_add_date;
        	$dataRow[5] = $row->isExecuted();
        	$result[] = $dataRow;
        }
        for ($i = count($result); $i <= 7; $i++) {
        	$row = array();
        	for ($j = 0; $j <= 5; $j++) {
        		$row[$j] = "";
        	}
        	$result[] = $row;
        }
        return $result;
    }
}