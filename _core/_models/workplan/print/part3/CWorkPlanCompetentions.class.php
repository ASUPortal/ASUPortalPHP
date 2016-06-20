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
	        			//удаляем последний символ пробела из строки
	        			$rest = substr($text, 0, -1);
	        			$dataRow[1] = $rest;
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
	        			$dataRow[3] = implode("; ", $knowledges);
	        		}
	        		$skills = array();
	        		if (!is_null($item->skills)) {
	        			foreach ($item->skills->getItems() as $o) {
	        				if (!is_null($o->skill)) {
	        					$skills[] = $o->skill->getValue();
	        				}
	        			}
	        			$dataRow[4] = implode("; ", $skills);
	        		}
	        		$experiences = array();
	        		if (!is_null($item->experiences)) {
	        			foreach ($item->experiences->getItems() as $o) {
	        				if (!is_null($o->experience)) {
	        					$experiences[] = $o->experience->getValue();
	        				}
	        			}
	        			$dataRow[5] = implode("; ", $experiences);
	        		}
        			$result[] = $dataRow;
        		}
        	}
        }
    	return $result;
    }
}