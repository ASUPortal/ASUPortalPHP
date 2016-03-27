<?php

class CWorkPlanMethodPracticInstructs extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Методические указания к практическим занятиям";
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
		$result = $contextObject->method_practic_instructs;
        return $result;
    }
}