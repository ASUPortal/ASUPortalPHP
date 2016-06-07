<?php

class CWorkPlanStructure2 extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Структура дисциплины. Без столбца Итого";
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
        $terms = array();
        $terms[] = "term.name";
        $termIds = array();
        foreach ($contextObject->terms->getItems() as $term) {
        	$termIds[] = $term->getId();
        	$terms[] = "sum(if(l.term_id = ".$term->getId().", l.value, 0)) as t_".$term->getId();
        }
        $query = new CQuery();
        $query->select(join(", ", $terms))
	        ->from(TABLE_WORK_PLAN_CONTENT_LOADS." as l")
	        ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "l.section_id = section.id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
	        ->condition("category.plan_id = ".$contextObject->getId()." and l._deleted = 0 and category._deleted = 0")
	        ->group("l.load_type_id")
	        ->order("term.name");
        $objects = $query->execute();
        foreach ($objects->getItems() as $key=>$value) {
        	$arr = array_values($value);
        	$dataRow = array();
        	for ($i = 0; $i <= count($value)-1; $i++) {
        		$dataRow[$i] = $arr[$i];
        	}
        	$result[] = $dataRow;
        }
        if (empty($result)) {
        	$terms = array();
        	$terms[] = "term.name";
        	foreach ($discipline->sections->getItems() as $section) {
        		$terms[] = "sum(if(l.section_id = ".$section->getId().", l.value, 0)) as t_".$section->getId();
        	}
        	$query = new CQuery();
        	$query->select(join(", ", $terms))
	        	->from(TABLE_CORRICULUM_DISCIPLINE_LABORS." as l")
	        	->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.type_id")
	        	->innerJoin(TABLE_CORRICULUM_DISCIPLINE_SECTIONS." as section", "l.section_id = section.id")
	        	->innerJoin(TABLE_CORRICULUM_DISCIPLINES." as discipline", "section.discipline_id = discipline.id")
	        	->condition("discipline.id = ".$discipline->getId())
	        	->group("l.type_id")
	        	->order("term.name");
        	$objects = $query->execute();
        	foreach ($objects->getItems() as $key=>$value) {
        		$arr = array_values($value);
        		$dataRow = array();
        		for ($i = 0; $i <= count($value)-1; $i++) {
        			$dataRow[$i] = $arr[$i];
        		}
        		$result[] = $dataRow;
        	}
        }
        return $result;
    }
}