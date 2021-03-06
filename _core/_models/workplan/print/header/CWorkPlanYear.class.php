<?php

class CWorkPlanYear extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Год";
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
        $result = date("Y", strtotime($contextObject->date_of_formation));
        return $result;
    }
}