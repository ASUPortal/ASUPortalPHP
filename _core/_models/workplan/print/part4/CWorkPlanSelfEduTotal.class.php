<?php

class CWorkPlanSelfEduTotal extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Самостоятельное изучение разделов дисциплины. Всего часов";
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
        $result = array();
        $result = 0;
        foreach ($contextObject->selfEducations->getItems() as $row) {
        	$result += $row->question_hours;
        }
        return $result;
    }
}