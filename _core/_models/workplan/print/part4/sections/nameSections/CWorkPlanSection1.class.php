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
    
    public function getNumberSection()
    {
    	$str = get_class($this);
    	return preg_replace('|[^0-9]*|','',$str);
    }

    public function getFieldType()
    {
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
		$result = "";
        foreach ($contextObject->getControlTypes()->getItems() as $control) {
        	if ($control->section->sectionIndex == $this->getNumberSection()) {
        		$result = $control->section->name;
        	}
        }
        return $result;
    }
}