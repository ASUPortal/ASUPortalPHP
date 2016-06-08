<?php

class CWorkPlanDisciplineKind extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Вид дисциплины";
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
		if ($discipline->cycle->title == "Базовая часть") {
			$result = "базовой";
		} elseif ($discipline->cycle->title == "Вариативная часть") {
			$result = "вариативной";
		} else {
			$result = "(базовой, вариативной)";
		}
        return $result;
    }
}