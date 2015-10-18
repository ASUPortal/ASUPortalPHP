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
    	$corriculumDiscipline = $contextObject->corriculumDiscipline;
    	$corriculum = CCorriculumsManager::getCorriculum($corriculumDiscipline->cycle->corriculum->getId());
    	$disciplinesBefore = array();
    	if (!is_null($contextObject->disciplinesBefore)) {
    		foreach ($contextObject->disciplinesBefore->getItems() as $disciplineBefore) {
    			$disciplinesBefore[$disciplineBefore->getId()] = $disciplineBefore->discipline->getValue();
    		}
    	}
    	$disciplines = array();
    	foreach ($corriculum->getDisciplines() as $disc) {
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_SECTIONS, "discipline_id=".$disc->getId())->getItems() as $ar) {
    			$section = new CActiveModel($ar);
    			foreach ($corriculumDiscipline->sections->getItems() as $currentSection) {
    				if ($currentSection->title >= $section->title) {
    					$disciplines[$section->discipline_id] = $section->title;
    				}
    			}
    		}
    	}
    	$competentions = array();
    	foreach ($corriculum->getDisciplines() as $disc) {
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_COMPETENTIONS, "discipline_id=".$disc->getId())->getItems() as $ar) {
    			$competention = new CActiveModel($ar);
    			foreach ($corriculumDiscipline->competentions->getItems() as $currentCompetention) {
    				if ($currentCompetention->competention_id == $competention->competention_id) {
    					$competentions[$competention->discipline_id] = $competention->competention_id;
    				}
    			}
    		}
    	}
    	$disciplinesCorriculum = array();
    	foreach ($disciplines as $section_id=>$section) {
    		foreach ($competentions as $comp_id=>$competention) {
    			if ($section_id == $comp_id) {
    				$disciplinesCorriculum[$comp_id] = $competention;
    			}
    		}
    	}
    	$items = array();
    	foreach (array_intersect_key($disciplinesBefore, $disciplinesCorriculum) as $key=>$value) {
    		$items[] = $key;
    	}
    	$result = array();
    	foreach ($items as $value) {
    		$discipl = CCorriculumsManager::getDiscipline($value);
    		foreach ($discipl->competentions->getItems() as $comp) {
    			$dataRow = array();
    			$dataRow[0] = count($result) + 1;
    			if (!is_null($comp->competention)) {
    				$str = $comp->competention->getValue();
    				//удаляем текст в скобках - код компетенции
    				$text = preg_replace("|\(.*?\)|is", "", $str);
    				//удаляем последний символ пробела из строки
    				$rest = substr($text, 0, -1);
    				$dataRow[1] = $rest;
    				//берем код компетенции - текст из скобок
    				preg_match('/\((.+)\)/', $str, $m);
    				$dataRow[2] = $m[1];
    			}
    			if (!is_null($comp->level)) {
    				$dataRow[3] = $comp->level->getValue();
    			}
    			if (!is_null($comp->discipline)) {
    				$dataRow[4] = $comp->discipline->discipline->getValue();
    			}
    			$result[] = $dataRow;
    		}
    	}
    	return $result;
    }
}