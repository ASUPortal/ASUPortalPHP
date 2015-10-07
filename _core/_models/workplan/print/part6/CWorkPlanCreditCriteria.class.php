<?php

class CWorkPlanCreditCriteria extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Критерии оценки к зачёту";
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
        if (!is_null($contextObject->criteriaCreditOfEvaluation)) {
        	foreach ($contextObject->criteriaCreditOfEvaluation->getItems() as $row) {
	        	$dataRow = array();
	        	$dataRow[0] = (count($result) + 1).".";
	        	$dataRow[1] = $row->criteria;
	        	$result[] = $dataRow;
        	}
        }
        return $result;
    }
}