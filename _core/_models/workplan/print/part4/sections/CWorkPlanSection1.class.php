<?php

class CWorkPlanSection1 extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Название первого раздела";
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
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
		$result = "";
        foreach ($contextObject->getControlTypes()->getItems() as $control) {
        	if ($control->section->sectionIndex == 1) {
        		$result = $control->section->name;
        	}
        }
        return $result;
    }
}