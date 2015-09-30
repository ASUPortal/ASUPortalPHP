<?php

class CWorkPlanMarkTypes extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Перечень оценочных средств";
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
        if (!is_null($contextObject->markTypes)) {
        	foreach ($contextObject->markTypes->getItems() as $row) {
				$dataRow = array();
				$dataRow[0] = count($result) + 1;
				$dataRow[1] = $row->type;
				$dataRow[2] = $row->form;
				$dataRow[3] = implode(", ", $row->funds->getItems());
				$dataRow[4] = implode(", ", $row->places->getItems());
				$result[] = $dataRow;
        	}
        }
        return $result;
    }
}