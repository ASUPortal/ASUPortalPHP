<?php

class CWorkPlanMethodProjectInstructs extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Методические указания к курсовому проектированию";
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
		$result = $contextObject->method_project_instructs;
        return $result;
    }
}