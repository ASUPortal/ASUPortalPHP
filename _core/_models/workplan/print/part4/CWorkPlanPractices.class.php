<?php

class CWorkPlanPractices extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Практические занятия дисциплины";
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
        $discipline = CCorriculumsManager::getDiscipline($contextObject->corriculum_discipline_id);
        $sum = 0;
        foreach ($contextObject->getPractices()->getItems() as $row) {
        	$dataRow = array();
        	$dataRow[0] = count($result) + 1;
        	$dataRow[1] = $row->load->section->sectionIndex;
        	$dataRow[2] = $row->title;
        	$dataRow[3] = $row->value;
        	$result[] = $dataRow;
        	$sum += $row->value;
        }
        $total = array();
        $total[0] = "";
        $total[1] = "";
        $total[2] = "Итого";
        $total[3] = $sum;
        $result[] = $total;
        if (empty($contextObject->getPractices()->getItems())) {
        	$sum = 0;
        	foreach ($discipline->sections->getItems() as $section) {
        		foreach ($section->labors->getItems() as $labor) {
        			if ($labor->type->getAlias() == "practice") {
        				$sum += $labor->value;
        			}
        		}
        	}
        	$total = array();
        	$total[0] = "";
        	$total[1] = "";
        	$total[2] = "Итого";
        	$total[3] = $sum;
        }
        return $result;
    }
}