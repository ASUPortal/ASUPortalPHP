<?php

class CWorkPlanDiscipline extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Дисциплина";
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
		if (!is_null($contextObject->corriculum_discipline_id)) {
			$result = CCorriculumsManager::getDiscipline($contextObject->corriculum_discipline_id)->discipline->name;
		}
        return $result;
    }
}