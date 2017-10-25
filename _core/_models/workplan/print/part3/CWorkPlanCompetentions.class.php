<?php

class CWorkPlanCompetentions extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Компетенции";
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
        			$dataRow[0] = count($result) + 1;
	        		if (!is_null($item->competention)) {
	        			$str = $item->competention->getValue();
	        			//удаляем текст в скобках - код компетенции
	        			$text = preg_replace("|\(.*?\)|is", "", $str);
	        			//удаляем пробелы из начала и конца строки
        				$dataRow[1] = trim($text);
	        			//берем код компетенции - текст из скобок
	        			preg_match('/\((.+)\)/', $str, $m);
	        			$dataRow[2] = $m[1];
	        		}
	        		$knowledges = array();
	        		if (!is_null($item->knowledges)) {
	        			foreach ($item->knowledges->getItems() as $o) {
	        				if (!is_null($o->knowledge)) {
	        					$knowledges[] = $o->knowledge->getValue();
	        				}
	        			}
	        			if (!empty($knowledges)) {
	        				$dataRow[3] = implode("; ", $knowledges);
	        			} else {
	        				$dataRow[3] = "—";
	        			}
	        		}
	        		$skills = array();
	        		if (!is_null($item->skills)) {
	        			foreach ($item->skills->getItems() as $o) {
	        				if (!is_null($o->skill)) {
	        					$skills[] = $o->skill->getValue();
	        				}
	        			}
	        			if (!empty($skills)) {
	        				$dataRow[4] = implode("; ", $skills);
	        			} else {
	        				$dataRow[4] = "—";
	        			}
	        		}
	        		$experiences = array();
	        		if (!is_null($item->experiences)) {
	        			foreach ($item->experiences->getItems() as $o) {
	        				if (!is_null($o->experience)) {
	        					$experiences[] = $o->experience->getValue();
	        				}
	        			}
	        			if (!empty($experiences)) {
	        				$dataRow[5] = implode("; ", $experiences);
	        			} else {
	        				$dataRow[5] = "—";
	        			}
	        		}
        			$result[] = $dataRow;
        		}
        	}
        }
    	return $result;
    }
}