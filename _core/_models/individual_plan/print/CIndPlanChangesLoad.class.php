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
        $load = CIndPlanManager::getLoad(CRequest::getInt("planId"));
        $studyLoad = $load->getWorksByType(6);
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
        return $result;
    }
}