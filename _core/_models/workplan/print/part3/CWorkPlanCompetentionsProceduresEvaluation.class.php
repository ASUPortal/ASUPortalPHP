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
        				$knowledges = array();
        				$knowledgesTask = array();
        				$knowledgesProcedure = array();
        				$knowledgesCriteria = array();
        				if (!is_null($item->knowledges)) {
        					foreach ($item->knowledges->getItems() as $o) {
        						if (!is_null($o->knowledge)) {
        							$knowledges[] = $o->knowledge->getValue();
        							$knowledgesTask[] = $o->type_task;
        							$knowledgesProcedure[] = $o->procedure_eval;
        							$knowledgesCriteria[] = $o->criteria_eval;
        						}
        					}
        					$dataRow1[1] = "Знать: ".implode("; ", $knowledges);
        					$dataRow1[2] = implode("; ", $knowledgesTask);
        					$dataRow1[3] = implode("; ", $knowledgesProcedure);
        					$dataRow1[4] = implode("; ", $knowledgesCriteria);
        				}
        				$result[] = $dataRow1;
        			
        				$dataRow2 = array();
        				$dataRow2[0] = $competention;
        				$skills = array();
        				$skillsTask = array();
        				$skillsProcedure = array();
        				$skillsCriteria = array();
        				if (!is_null($item->skills)) {
        					foreach ($item->skills->getItems() as $o) {
        						if (!is_null($o->skill)) {
        							$skills[] = $o->skill->getValue();
        							$skillsTask[] = $o->type_task;
        							$skillsProcedure[] = $o->procedure_eval;
        							$skillsCriteria[] = $o->criteria_eval;
        						}
        					}
        					$dataRow2[1] = "Уметь: ".implode("; ", $skills);
        					$dataRow2[2] = implode("; ", $skillsTask);
        					$dataRow2[3] = implode("; ", $skillsProcedure);
        					$dataRow2[4] = implode("; ", $skillsCriteria);
        				}
        				$result[] = $dataRow2;
        			
        				$dataRow3 = array();
        				$dataRow3[0] = $competention;
        				$experiences = array();
        				$experiencesTask = array();
        				$experiencesProcedure = array();
        				$experiencesCriteria = array();
        				if (!is_null($item->experiences)) {
        					foreach ($item->experiences->getItems() as $o) {
        						if (!is_null($o->experience)) {
        							$experiences[] = $o->experience->getValue();
        							$experiencesTask[] = $o->type_task;
        							$experiencesProcedure[] = $o->procedure_eval;
        							$experiencesCriteria[] = $o->criteria_eval;
        						}
        					}
        					$dataRow3[1] = "Владеть: ".implode("; ", $experiences);
        					$dataRow3[2] = implode("; ", $experiencesTask);
        					$dataRow3[3] = implode("; ", $experiencesProcedure);
        					$dataRow3[4] = implode("; ", $experiencesCriteria);
        				}
        				$result[] = $dataRow3;
        			}
        		}
        	}
        }
    	return $result;
    }
}