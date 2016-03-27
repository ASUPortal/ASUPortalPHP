<?php

class CWorkPlanCompetentionsProceduresEvaluation extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Методические материалы, определяющие процедуры оценивания результатов обучения (знаний, умений, владений), характеризующих этапы формирования компетенций";
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
        if (!is_null($contextObject->competentions)) {
        	foreach ($contextObject->competentions->getItems() as $item) {
        		//формируемые компетенции рабочей программы
        		if ($item->type == 0) {
        			$dataRow = array();
	        		if (!is_null($item->competention)) {
	        			$str = $item->competention->getValue();
	        			//берем код компетенции - текст из скобок
	        			preg_match('/\((.+)\)/', $str, $m);
	        			$dataRow[0] = $m[1].", ".$item->level->getValue();;
	        		}
	        		if (!is_null($item->knowledges)) {
	        			$dataRow[1] = "Знать: ".implode("; ", $item->knowledges->getItems())."; ";
	        			$dataRow[1] .= "Уметь: ".implode("; ", $item->skills->getItems())."; ";
	        			$dataRow[1] .= "Владеть: ".implode("; ", $item->experiences->getItems())."; ";
	        		}
	        		$dataRow[2] = $item->type_task;
	        		$dataRow[3] = $item->procedure_eval;
	        		$dataRow[4] = $item->criteria_eval;
        			$result[] = $dataRow;
        		}
        	}
        }
    	return $result;
    }
}