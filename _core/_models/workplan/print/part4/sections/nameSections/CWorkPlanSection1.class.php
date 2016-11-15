<?php

class CWorkPlanSection1 extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Название ".$this->getNumberSection()." раздела";
    }

    public function getFieldDescription()
    {
        return "Используется при печати рабочей программы, принимает параметр id с Id рабочей программы";
    }

    public function getParentClassField()
    {

    }
    
	public function getNumberSection()
    {
    	return 1;
    }

    public function getFieldType()
    {
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
        $result = array();
        foreach ($contextObject->getControlTypes()->getItems() as $control) {
            if ($control->section->sectionIndex == $this->getNumberSection()) {
                $dataRow = array();
                $dataRow[0] = "Раздел ".$this->getNumberSection()." ".$control->section->name;
            }
        }
        if (isset($dataRow)) {
            $result[] = $dataRow;
        }
        return $result;
    }
    
    public function getColSpan()
    {
        return 5;
    }
}