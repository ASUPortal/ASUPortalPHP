<?php

class CWorkPlanEducationTechnologiesDescriptionNotProvided extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Надпись, если нет описания образовательных технологий - Не предусмотрено";
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
        if (empty(CWorkPlanEducationTechnologiesDescription::execute($contextObject))) {
            $result = "не предусмотрено";
        }
        return $result;
    }
}