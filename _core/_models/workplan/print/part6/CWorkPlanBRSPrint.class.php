<?php

class CWorkPlanBRSPrint extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Балльно-рейтинговая система";
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
        if (!is_null($contextObject->BRS)) {
        	foreach ($contextObject->BRS->getItems() as $row) {
	        	$dataRow = array();
	        	$dataRow[0] = count($result) + 1;
	        	$dataRow[1] = $row->mark;
	        	$dataRow[2] = $row->range;
	        	if ($row->is_ok) {
	        		$dataRow[3] = "Аттестация успешная";
	        	}
				else {
					$dataRow[3] = "Аттестация не пройдена";
				}
	        	$dataRow[4] = $row->comment;
	        	$result[] = $dataRow;
        	}
        }
        return $result;
    }
}