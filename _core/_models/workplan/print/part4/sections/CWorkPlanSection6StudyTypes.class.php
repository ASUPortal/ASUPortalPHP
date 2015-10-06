<?php

class CWorkPlanSection6StudyTypes extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Виды учебной деятельности для шестого раздела текущего контроля";
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
        foreach ($contextObject->getControlTypes()->getItems() as $row) {
        	if ($row->section->sectionIndex == 6 and $row->control->getAlias() == "current") {
        		$dataRow = array();
	        	$dataRow[0] = "– ".$row->type->getValue();
	        	$dataRow[1] = $row->mark;
	        	$dataRow[2] = $row->amount_labors;
	        	$dataRow[3] = $row->min;
	        	$dataRow[4] = $row->max;
	        	$result[] = $dataRow;
        	}
        }
        return $result;
    }
}