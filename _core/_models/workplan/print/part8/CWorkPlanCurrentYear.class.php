<?php

class CWorkPlanCurrentYear extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Текущий учебный год";
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
		$result = substr(CBaseManager::getTimeIntervals(CUtils::getCurrentYear()->getId())->date_start, -4)."/".substr(CBaseManager::getTimeIntervals(CUtils::getCurrentYear()->getId())->date_end, -4);
        return $result;
    }
}