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
		$month = date("m", strtotime($contextObject->date));
		if ($month > 12 || $month < 1) return FALSE;
		$aMonth = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
		return $aMonth[$month - 1];
    }
}