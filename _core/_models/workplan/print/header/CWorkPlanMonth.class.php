<?php

class CWorkPlanMonth extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Месяц формирования раб. программы";
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
		$monthNum = date("m", strtotime($contextObject->date_of_formation));
		return CUtils::getMonthAsWord($monthNum);
    }
}