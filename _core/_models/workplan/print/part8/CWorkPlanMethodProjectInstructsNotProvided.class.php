<?php

class CWorkPlanMethodProjectInstructsNotProvided extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Надпись, если нет метод. указаний к курсовому проектированию - Не предусмотрено";
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
        if (empty(CWorkPlanMethodProjectInstructs::execute($contextObject))) {
            $result = "не предусмотрено";
        }
        return $result;
    }
}