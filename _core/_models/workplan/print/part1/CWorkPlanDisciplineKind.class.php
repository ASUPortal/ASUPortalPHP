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
		if ($discipline->discipline_kind_id != 0) {
			$result = CTaxonomyManager::getTerm($discipline->discipline_kind_id)->getValue();
		} else {
			$result = "(базовой, вариативной)";
		}
        return $result;
    }
}