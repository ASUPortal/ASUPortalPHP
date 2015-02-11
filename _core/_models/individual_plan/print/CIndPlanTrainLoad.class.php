<?php

class CIndPlanTrainLoad extends CAbstractPrintClassField{
    public function getFieldName()
    {
        return "Учебно-воспитательная работа";
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
        $studyLoad = $load->getWorksByType(4);
        foreach ($studyLoad->getItems() as $row) {
        	$dataRow = array();
        	$dataRow[0] = count($result) + 1;
        	$dataRow[1] = $row->getTitle();
        	$dataRow[2] = $row->plan_hours;
        	$dataRow[3] = $row->plan_expiration_date;
        	$dataRow[4] = $row->isExecuted();
        	$dataRow[5] = $row->comment;
        	$result[] = $dataRow;
        }
        return $result;
    }
}