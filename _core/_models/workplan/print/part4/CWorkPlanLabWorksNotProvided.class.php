<?php

class CWorkPlanLabWorksNotProvided extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Надпись, если нет лабораторных работ - Не предусмотрено";
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
        if (empty(CWorkPlanLabWorks::execute($contextObject))) {
        	$result = "Не предусмотрено";
        }
        return $result;
    }
}