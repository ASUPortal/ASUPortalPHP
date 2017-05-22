<?php

class CWorkPlanProjectDescriptionNotProvided extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Надпись, если нет описания курсового проекта - Не предусмотрено";
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
        if (empty(CWorkPlanProjectDescription::execute($contextObject))) {
            $result = "не предусмотрено";
        }
        return $result;
    }
}