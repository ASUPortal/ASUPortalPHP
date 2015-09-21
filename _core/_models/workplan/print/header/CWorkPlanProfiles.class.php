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
		$profiles = array();
		if (!is_null($contextObject->profiles)) {
			foreach ($contextObject->profiles->getItems() as $profil) {
				$profiles[] = $profil->getValue();
			}
		}
		$result = implode(", ", $profiles);
        return $result;
    }
}