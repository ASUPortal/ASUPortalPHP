<?php

class CWorkPlanNmsChairman extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Председатель НМС";
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
    	if (!is_null(CCorriculumsManager::getDiscipline($contextObject->corriculum_discipline_id)->cycle->corriculum->nisChairman)) {
    		$result = CCorriculumsManager::getDiscipline($contextObject->corriculum_discipline_id)->cycle->corriculum->nisChairman->getNameShort();
    	}
        return $result;
    }
}