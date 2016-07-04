<?php

class CWorkPlanStatus extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Статус рабочей программы";
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
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
		$result = array();
		$dataRow = array();
		$dataRow[0] = $contextObject->discipline->getValue();
		$authors = array();
		if (!is_null($contextObject->authors)) {
			foreach ($contextObject->authors->getItems() as $author) {
				$authors[] = $author->getNameShort();
			}
		}
		$dataRow[1] = implode(", ", $authors);
		if ($contextObject->comment_file == 0 or is_null($contextObject->commentFile)) {
			$dataRow[2] = "Нет комментария";
		} else {
			$dataRow[2] = $contextObject->commentFile->getValue();
		}
		if ($contextObject->statusWorkplan == 0 or is_null($contextObject->statusWorkplan)) {
			$dataRow[3] = "Нет комментария";
		} else {
			$dataRow[3] = $contextObject->statusWorkplan->getValue();
		}
		if ($contextObject->statusOnPortal == 0 or is_null($contextObject->statusOnPortal)) {
			$dataRow[4] = "Нет комментария";
		} else {
			$dataRow[4] = $contextObject->statusOnPortal->getValue();
		}
		$result[] = $dataRow;
        return $result;
    }
}