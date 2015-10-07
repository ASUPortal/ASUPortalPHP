<?php

class CWorkPlanMaterialsOfEvaluation extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Типовые оценочные материалы";
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
        if (!is_null($contextObject->materialsOfEvaluation)) {
        	foreach ($contextObject->materialsOfEvaluation->getItems() as $row) {
	        	$dataRow = array();
	        	$dataRow[0] = (count($result) + 1).".";
	        	$dataRow[1] = $row->type;
	        	$dataRow[2] = $row->material;
	        	$result[] = $dataRow;
        	}
        }
        return $result;
    }
}