<?php

class CWorkPlanPracticesHeader extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Практические занятия дисциплины. Шапка таблицы";
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
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
        $result = array();
        if (!empty(CWorkPlanPractices::execute($contextObject))) {
        	$dataRow = array();
        	$dataRow[0] = "№ занятия";
        	$dataRow[1] = "№ раздела";
        	$dataRow[2] = "Тема";
        	$dataRow[3] = "Кол-во часов";
        	$result[] = $dataRow;
        }
        return $result;
    }
}