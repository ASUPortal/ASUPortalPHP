<?php

class CIndPlanMethodsLoad extends CAbstractPrintClassField{
    public function getFieldName()
    {
        return "Перечень научных и научно-методических работ, выполненных преподавателем";
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
        $studyLoad = $load->getWorksByType(5);
        foreach ($studyLoad->getItems() as $row) {
        	$dataRow = array();
        	$dataRow[0] = $row->getTitle();
        	$dataRow[1] = $row->paper_pages;
        	$result[] = $dataRow;
        }
        for ($i = count($result); $i <= 7; $i++) {
        	$row = array();
        	for ($j = 0; $j <= 1; $j++) {
        		$row[$j] = "";
        	}
        	$result[] = $row;
        }
        return $result;
    }
}