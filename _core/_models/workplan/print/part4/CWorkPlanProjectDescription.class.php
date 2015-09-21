<?php

class CWorkPlanProjectDescription extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Описание курсового проекта";
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
		$result = $contextObject->project_description;
        return $result;
    }
}