<?php

class CWorkPlanDirection extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Направление подготовки";
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
		$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
		if (!is_null($plan->direction_id)) {
			$result = CTaxonomyManager::getTerm($plan->direction_id)->name;
		}
        return $result;
    }
}