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
        $result = 0;
        foreach ($contextObject->getSelfWorkQuestions()->getItems() as $row) {
        	$result += $row->value;
        }
        return $result;
    }
}