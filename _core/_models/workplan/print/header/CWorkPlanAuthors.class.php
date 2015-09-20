<?php

class CWorkPlanAuthors extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "ФИО авторов-составителей с должностью и званием";
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
		$authors = array();
		if (!is_null($plan->authors)) {
			foreach ($plan->authors->getItems() as $author) {
				$person = CStaffManager::getPerson($author->id);
				$persons = $person->fio_short;
				if (!is_null($person->degree)) {
					$persons .= ", ".$person->degree->name_short;
				}
				if (!is_null($person->title)) {
					$persons .= ", ".$person->title->getValue();
				}
				$authors[] = $persons;
			}
		}
		$result = implode(", ", $authors);
        return $result;
    }
}