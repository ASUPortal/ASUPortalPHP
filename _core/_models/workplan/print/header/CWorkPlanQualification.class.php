<?php

class CWorkPlanQualification extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Квалификация";
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
		if (!is_null($plan->qualification_id)) {
			$result = CTaxonomyManager::getTerm($plan->qualification_id)->name;
		}
        return $result;
    }
}