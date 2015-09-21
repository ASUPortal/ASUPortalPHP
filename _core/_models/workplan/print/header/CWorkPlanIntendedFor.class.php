<?php

class CWorkPlanIntendedFor extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Предназначено для";
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
		$result = $contextObject->intended_for;
        return $result;
    }
}