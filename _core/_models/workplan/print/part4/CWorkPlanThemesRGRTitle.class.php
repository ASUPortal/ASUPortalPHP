<?php

class CWorkPlanThemesRGRTitle extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Темы расчётно-графических работ. Заголовок";
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
    	if (!empty(CWorkPlanThemesRGR::execute($contextObject))) {
    		$result = "Тематика расчетно-графических работ:";
    	}
    	return $result;
    }
}