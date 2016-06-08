<?php

class CWorkPlanCorriculumDirection extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Направление подготовки из учебного плана";
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
		if (!is_null($discipline->cycle->corriculum->speciality_direction)) {
			$result = $discipline->cycle->corriculum->speciality_direction->getValue();
		}
        return $result;
    }
}