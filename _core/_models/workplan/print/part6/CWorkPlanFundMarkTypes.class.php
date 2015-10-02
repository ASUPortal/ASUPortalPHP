<?php

class CWorkPlanFundMarkTypes extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Фонд оценочных средств";
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
        if (!is_null($contextObject->fundMarkTypes)) {
        	foreach ($contextObject->fundMarkTypes->getItems() as $row) {
				$dataRow = array();
				$dataRow[0] = count($result) + 1;
				$dataRow[1] = $row->section->name;
				$codes = array();
				foreach ($row->competentions->getItems() as $competention) {
					$str = $competention;
					//берем код компетенции - текст из скобок
					preg_match('/\((.+)\)/', $str, $m);
					$codes[] = $m[1];
				}
				$dataRow[2] = implode(", ", $codes);
				$dataRow[3] = implode(", ", $row->levels->getItems());
				$dataRow[4] = implode(", ", $row->controls->getItems());
				$result[] = $dataRow;
        	}
        }
        return $result;
    }
}