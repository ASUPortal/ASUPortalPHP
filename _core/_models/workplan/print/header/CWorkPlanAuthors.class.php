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
		$authors = array();
		if (!is_null($contextObject->authors)) {
			foreach ($contextObject->authors->getItems() as $author) {
				$persons = $author->getNameShort();
				if (!is_null($author->degree)) {
					$persons .= ", ".$author->degree->getValue();
				}
				if (!is_null($author->title)) {
					$persons .= ", ".$author->title->getValue();
				}
				$authors[] = $persons;
			}
		}
		$result = implode(", ", $authors);
        return $result;
    }
}