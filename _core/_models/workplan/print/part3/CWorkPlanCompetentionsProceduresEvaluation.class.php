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
        			if ($item->type == 0) {
        				$dataRow1 = array();
        				$competention = "";
        				if (!is_null($item->competention)) {
        					$str = $item->competention->getValue();
        					//берем код компетенции - текст из скобок
        					preg_match('/\((.+)\)/', $str, $m);
        					if (!is_null($item->level)) {
        						$dataRow1[0] = $m[1].", ".$item->level->getValue();
        					} else {
        						$dataRow1[0] = $m[1];
        					}
        					$competention = $dataRow1[0];
        				}
        				if (!is_null($item->knowledges)) {
        					$dataRow1[1] = "Знать: ".implode("; ", $item->knowledges->getItems());
        				}
        				$dataRow1[2] = $item->type_task;
        				$dataRow1[3] = $item->procedure_eval;
        				$dataRow1[4] = $item->criteria_eval;
        				$result[] = $dataRow1;
        			
        				$dataRow2 = array();
        				$dataRow2[0] = $competention;
        				if (!is_null($item->knowledges)) {
        					$dataRow2[1] = "Уметь: ".implode("; ", $item->skills->getItems());
        				}
        				$dataRow2[2] = $item->type_task;
        				$dataRow2[3] = $item->procedure_eval;
        				$dataRow2[4] = $item->criteria_eval;
        				$result[] = $dataRow2;
        			
        				$dataRow3 = array();
        				$dataRow3[0] = $competention;
        				if (!is_null($item->knowledges)) {
        					$dataRow3[1] = "Владеть: ".implode("; ", $item->experiences->getItems());
        				}
        				$dataRow3[2] = $item->type_task;
        				$dataRow3[3] = $item->procedure_eval;
        				$dataRow3[4] = $item->criteria_eval;
        				$result[] = $dataRow3;
        			}
        		}
        	}
        }
    	return $result;
    }
}