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
        $string = CWorkPlanEducationTechnologiesDescription::execute($contextObject);
        if (CStringUtils::isEmptyWithTags($string)) {
            $result = "не предусмотрено";
        }
        return $result;
    }
}