<?php

class CWorkPlanOrderDate extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Дата утверждения приказа из учебного плана";
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
    	$discipline = CCorriculumsManager::getDiscipline($contextObject->corriculum_discipline_id);
    	if ($discipline->cycle->corriculum->order_date != "0000-00-00") {
    		$day = date("d", strtotime($discipline->cycle->corriculum->order_date));
    		$month = CUtils::getMonthAsWord(date("m", strtotime($discipline->cycle->corriculum->order_date)));
    		$year = date("Y", strtotime($discipline->cycle->corriculum->order_date));
    		$result = "\"".$day."\" ".$month." ".$year;
    	}
        return $result;
    }
}