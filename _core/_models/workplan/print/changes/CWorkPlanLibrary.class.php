<?php

class CWorkPlanLibrary extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Директор библиотеки";
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
		$result = $contextObject->director_of_library;
        return $result;
    }
}