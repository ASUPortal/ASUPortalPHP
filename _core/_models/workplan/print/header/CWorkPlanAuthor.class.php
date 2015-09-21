<?php

class CWorkPlanAuthor extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "ФИО авторов-составителей";
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
				$authors[] = $person->fio_short;
			}
		}
		$result = implode(", ", $authors);
        return $result;
    }
}