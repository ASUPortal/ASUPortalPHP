<?php

class CWorkPlanProjectThemesTitle extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Темы курсовых проектов. Заголовок";
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
    	if (!empty(CWorkPlanProjectThemes::execute($contextObject))) {
    		$result = "Тематика курсовых работ (проектов):";
    	}
    	return $result;
    }
}