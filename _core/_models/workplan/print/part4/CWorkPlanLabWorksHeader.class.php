<?php

class CWorkPlanLabWorksHeader extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Лабораторные работы занятия дисциплины. Шапка таблицы";
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
        if (!empty(CWorkPlanLabWorks::execute($contextObject))) {
        	$dataRow = array();
        	$dataRow[0] = "№ ЛР";
        	$dataRow[1] = "№ раздела";
        	$dataRow[2] = "Наименование лабораторных работ";
        	$dataRow[3] = "Кол-во часов";
        	$result[] = $dataRow;
        }
        return $result;
    }
}