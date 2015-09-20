<?php

class CWorkPlanSelfEdu extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Самостоятельное изучение разделов дисциплины";
    }

    public function getFieldDescription()
    {
        return "Используется при печати рабочей программы, принимает параметр id с Id рабочей программы";
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
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        foreach ($plan->selfEducations->getItems() as $row) {
        	$dataRow = array();
        	$dataRow[0] = $row->load->section->sectionIndex;
        	$dataRow[1] = $row->question_title;
        	$dataRow[2] = $row->question_hours;
        	$result[] = $dataRow;
        }
        return $result;
    }
}