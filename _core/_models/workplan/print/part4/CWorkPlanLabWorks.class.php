<?php

class CWorkPlanLabWorks extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Лабораторные работы занятия дисциплины";
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
        foreach ($contextObject->getLabWorks()->getItems() as $termData) {
        	foreach ($termData as $row) {
        		$dataRow = array();
        		$dataRow[0] = count($result) + 1;
        		$dataRow[1] = $row->load->section->sectionIndex;
        		$dataRow[2] = $row->title;
        		$dataRow[3] = $row->value;
        		$result[] = $dataRow;
        	}
        }
        return $result;
    }
}