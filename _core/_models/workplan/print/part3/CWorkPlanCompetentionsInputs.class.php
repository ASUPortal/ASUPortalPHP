<?php

class CWorkPlanCompetentionsInputs extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Входные компетенции";
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
        		if ($item->type == 1) {
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
        			if (!is_null($item->level)) {
        				$dataRow[3] = $item->level->getValue();
        			}
        			if (!is_null($item->discipline)) {
        				$dataRow[4] = $item->discipline->discipline->getValue();
        			}
        			$result[] = $dataRow;
        		}	
        	}
        }
    	return $result;
    }
}