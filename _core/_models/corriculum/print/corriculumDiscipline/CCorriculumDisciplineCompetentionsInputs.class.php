<?php

class CCorriculumDisciplineCompetentionsInputs extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Входные компетенции дисциплины учебного плана";
    }

    public function getFieldDescription()
    {
        return "Используется при печати дисциплин учебного плана, принимает параметр id с Id дисциплины учебного плана";
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
		$corriculum = CCorriculumsManager::getCorriculum($contextObject->cycle->corriculum->getId());
		//массив предшествующих дисциплин из рабочих программ дисциплины учебного плана
		$disciplinesBefore = array();
		foreach ($contextObject->plans->getItems() as $plan) {
			if (!is_null($plan->disciplinesBefore)) {
				foreach ($plan->disciplinesBefore->getItems() as $disciplineBefore) {
					$disciplinesBefore[$disciplineBefore->getId()] = $disciplineBefore->discipline->getValue();
				}
			}
		}
		//массив с ключами дисциплин, у которых название семестра меньше либо равно названию семестра текущей дисциплины
    	$disciplines = array();
    	//получаем все дисциплины учебного плана
    	foreach ($corriculum->getDisciplines() as $disc) {
    		//получаем семестры дисциплин
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_SECTIONS, "discipline_id=".$disc->getId())->getItems() as $ar) {
    			$section = new CActiveModel($ar);
    			//получаем семестры текущей дисциплины учебного плана
    			foreach ($contextObject->sections->getItems() as $currentSection) {
    				//проверяем, что название текущего семестра больше либо равно названию семестров всех дисциплин учебного плана
    				if ($currentSection->title >= $section->title) {
    					$disciplines[$section->discipline_id] = $section->title;
    				}
    			}
    		}
    	}
    	//массив с ключами дисциплин с нужными компетенциями
    	$competentions = array();
    	foreach ($corriculum->getDisciplines() as $disc) {
    		//получаем компетенции всех дисциплин учебного плана
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_COMPETENTIONS, "discipline_id=".$disc->getId())->getItems() as $ar) {
    			$competention = new CActiveModel($ar);
    			//получаем компетенции текущей дисциплины учебного плана
    			//foreach ($contextObject->competentions->getItems() as $currentCompetention) {
    				//проверяем, что компетенции текущей дисциплины совпадают с компетенциями других дисциплин учебного плана
    				//if ($currentCompetention->competention_id == $competention->competention_id) {
    					$competentions[$competention->discipline_id] = $competention->competention_id;
    				//}
    			//}
    		}
    	}
    	//получаем нужные дисциплины учебного плана
    	$disciplinesCorriculum = array();
        foreach ($disciplines as $section_id=>$section) {
        	foreach ($competentions as $comp_id=>$competention) {
        		//сравниваем массив $disciplines с нужными семестрами с массивом $competentions с нужными компетенциями по совпадающим ключам дисциплин
        		if ($section_id == $comp_id) {
        			//массив дисциплин, отобранных по семестру и компетенциям, ключ - id дисциплины, значение - id компетенции
        			$disciplinesCorriculum[$comp_id] = $competention;
        		}
        	}
        }
        //массив, по которому будем выводить компетенции в шаблон
        $items = array();
        //сравниваем по совпадающим ключам массив предшествующих дисциплин с массивом дисциплин, отобранных по семестру и компетенциям
        foreach (array_intersect_key($disciplinesBefore, $disciplinesCorriculum) as $key=>$value) {
        	//записываем в массив $items id отобранных дисциплин
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