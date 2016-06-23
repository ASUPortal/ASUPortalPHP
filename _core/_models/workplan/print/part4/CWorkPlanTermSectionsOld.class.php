<?php

class CWorkPlanTermSectionsOld extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Нагрузка по разделам дисциплины для старого шаблона";
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
        $selfWork = false;
        foreach ($contextObject->categories->getItems() as $category) {
        	foreach ($category->sections->getItems() as $section) {
        		foreach ($section->loadsDisplay->getItems() as $load) {
        			if ($load->loadType->getAlias() == "self_work") {
        				$selfWork = true;
        			}
        		}
        	}
        }
        if (!is_null($contextObject->terms)) {
        	$termSectionsData = new CArrayList();
        	foreach ($contextObject->terms->getItems() as $term) {
        		$query = new CQuery();
        		$select = array();
        		$select[] = "section.id";
        		$select[] = "section.sectionIndex";
        		$select[] = "section.name";
        		$select[] = "section.content";
        		if ($selfWork) {
        			$select[] = "sum(if(term.alias in ('lecture', 'practice', 'labwork', 'ksr', 'self_work'), l.value, 0)) as total";
        		} else {
        			$select[] = "sum(if(term.alias in ('lecture', 'practice', 'labwork', 'ksr'), l.value, 0)) + sum(ifnull(selfedu.question_hours, 0)) as total";
        		}
        		$select[] = "sum(if(term.alias = 'lecture', l.value, 0)) as lecture";
        		$select[] = "sum(if(term.alias = 'practice', l.value, 0)) as practice";
        		$select[] = "sum(if(term.alias = 'labwork', l.value, 0)) as labwork";
        		$select[] = "sum(if(term.alias = 'ksr', l.value, 0)) as ksr";
        		$select[] = "sum(if(term.alias = 'self_work', l.value, 0)) as self_work";
        		if ($selfWork) {
        			$select[] = "sum(if(term.alias = 'self_work', l.value, 0)) as self_work";
        		} else {
        			$select[] = "sum(ifnull(selfedu.question_hours, 0)) as selfedu";
        		}
        		$query->select(join(", ", $select))
	        		->from(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section")
	        		->innerJoin(TABLE_WORK_PLAN_CONTENT_LOADS." as l", "l.section_id = section.id")
	        		->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
	        		->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
	        		->leftJoin(TABLE_WORK_PLAN_SELFEDUCATION." as selfedu", "selfedu.load_id = l.id")
	        		->group("l.section_id")
	        		->condition("l.term_id = ".$term->getId()." and l._deleted = 0 and category._deleted = 0");
        		$items = $query->execute();
        		if ($items->getCount() > 0) {
        			$termSectionsData->add($term->getId(), $items);
        		}
        	}
        	foreach ($termSectionsData->getItems() as $termId=>$termData) {
        		$lectureSum = 0;
        		$practiceSum = 0;
        		$labworkSum = 0;
        		$ksrSum = 0;
        		$selfeduSum = 0;
        		$totalSum = 0;
        		foreach ($termData as $row) {
        			$dataRow = array();
        			$dataRow[0] = $row["sectionIndex"];
        			$dataRow[1] = $row["name"].": ".$row["content"];
        			$dataRow[2] = $row["total"];
        			$dataRow[3] = $row["lecture"];
        			$dataRow[4] = $row["practice"];
        			$dataRow[5] = $row["labwork"];
        			if ($selfWork) {
        				$dataRow[6] = $row["self_work"];
        			} else {
        				$dataRow[6] = $row["selfedu"];
        			}
        			$result[] = $dataRow;
        			
        			$lectureSum += $row["lecture"];
        			$practiceSum += $row["practice"];
        			$labworkSum += $row["labwork"];
        			$ksrSum += $row["ksr"];
        			if ($selfWork) {
        				$selfeduSum += $row["self_work"];
        			} else {
        				$selfeduSum += $row["selfedu"];
        			}
        			$totalSum += $row["total"];
        		}
        		$total = array();
        		$total[0] = "";
        		$total[1] = "Итого";
        		$total[2] = $totalSum;
        		$total[3] = $lectureSum;
        		$total[4] = $practiceSum;
        		$total[5] = $labworkSum;
        		$total[6] = $selfeduSum;
        		$result[] = $total;
        	}
        }
        if (empty($result)) {
        	$lectureSum = 0;
        	$practiceSum = 0;
        	$labworkSum = 0;
        	$selfeduSum = 0;
        	$totalSum = 0;
        	foreach ($discipline->sections->getItems() as $section) {
        		foreach ($section->labors->getItems() as $labor) {
        			if ($labor->type->getAlias() == "lecture") {
        				$lectureSum += $labor->value;
        			}
        			if ($labor->type->getAlias() == "practice") {
        				$practiceSum += $labor->value;
        			}
        			if ($labor->type->getAlias() == "labwork") {
        				$labworkSum += $labor->value;
        			}
        			if ($labor->type->getAlias() == "self_work") {
        				$selfeduSum += $labor->value;
        			}
        			$totalSum = $lectureSum+$practiceSum+$labworkSum+$selfeduSum;
        		}
        	}
        	$countSections = $lectureSum/2;
        	for ($i = 1; $i <= $countSections; $i++) {
        		$dataRow = array();
        		$dataRow[0] = $i;
        		$dataRow[1] = "Раздел №".$i;
        		$dataRow[2] = round($totalSum/$countSections, 0);
        		$dataRow[3] = round($lectureSum/$countSections, 0);
        		$dataRow[4] = round($practiceSum/$countSections, 0);
        		$dataRow[5] = round($labworkSum/$countSections, 0);
        		$dataRow[6] = round($selfeduSum/$countSections, 0);
        		$result[] = $dataRow;
        	}
        	$total = array();
        	$total[0] = "";
        	$total[1] = "Итого";
        	$total[2] = $totalSum;
        	$total[3] = $lectureSum;
        	$total[4] = $practiceSum;
        	$total[5] = $labworkSum;
        	$total[6] = $selfeduSum;
        	$result[] = $total;
        }
        return $result;
    }
}