<?php

class CWorkPlanEducationTechnologies extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Образовательные технологии";
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
        foreach ($contextObject->getTechnologies()->getItems() as $row) {
        	$dataRow = array();
        	$dataRow[0] = $row->load->term;
        	$dataRow[1] = $row->load->loadType;
        	$dataRow[2] = $row->technology;
        	$dataRow[3] = $row->value;
        	$result[] = $dataRow;
        }
        return $result;
    }
}