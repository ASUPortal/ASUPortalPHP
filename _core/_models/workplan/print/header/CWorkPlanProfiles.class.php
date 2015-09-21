<?php

class CWorkPlanProfiles extends CAbstractPrintClassField {
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
		$profiles = array();
		if (!is_null($plan->profiles)) {
			foreach ($plan->profiles->getItems() as $profil) {
				$profiles[] = CTaxonomyManager::getTerm($profil->id)->name;
			}
		}
		$result = implode(", ", $profiles);
        return $result;
    }
}