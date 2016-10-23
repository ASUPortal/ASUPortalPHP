<?php

class CWorkPlanTotalCredits extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Всего зачетных единиц у дисциплины";
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
        $result = round(CWorkPlanTotalHours::execute($contextObject)/36, 2);
        return $result;
    }
}