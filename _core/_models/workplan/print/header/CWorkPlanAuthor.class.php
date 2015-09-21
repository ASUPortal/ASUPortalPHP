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
		$authors = array();
		if (!is_null($contextObject->authors)) {
			foreach ($contextObject->authors->getItems() as $author) {
				$authors[] = $author->getNameShort();
			}
		}
		$result = implode(", ", $authors);
        return $result;
    }
}