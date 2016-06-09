<?php

class CWorkPlanOrderNumber extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Номер приказа утверждения стандарта из учебного плана";
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
    	$discipline = CCorriculumsManager::getDiscipline($contextObject->corriculum_discipline_id);
    	$result = $discipline->cycle->corriculum->order_number_standart;
        return $result;
    }
}