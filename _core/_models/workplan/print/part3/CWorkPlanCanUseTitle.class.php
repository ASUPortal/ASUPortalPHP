<?php

class CWorkPlanCanUseTitle extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Надпись - Приобрести опыт деятельности";
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
        if (!empty(CWorkPlanCanUse::execute($contextObject))) {
            $result = "Приобрести опыт деятельности:";
        }
        return $result;
    }
}