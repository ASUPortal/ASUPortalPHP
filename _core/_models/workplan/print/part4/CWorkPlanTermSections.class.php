<?php

class CWorkPlanTermSections extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Нагрузка по разделам дисциплины с КСР";
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
        			$queryTech = new CQuery();
        			$queryTech->select("tech.technology_id")
	        			->from(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section")
	        			->innerJoin(TABLE_WORK_PLAN_CONTENT_LOADS." as l", "l.section_id = section.id")
	        			->innerJoin(TABLE_WORK_PLAN_CONTENT_TECHNOLOGIES." as tech", "tech.load_id = l.id")
	        			->condition("l.section_id = ".$row["id"]);
        			$technologies = array();
        			foreach ($queryTech->execute()->getItems() as $items) {
        				foreach ($items as $tech) {
        					$technologies[] = CTaxonomyManager::getTerm($tech)->getValue();
        				}
        			}
        			$queryLiter = new CQuery();
        			$queryLiter->select("lit.literature_id")
	        			->from(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section")
	        			->innerJoin(TABLE_WORK_PLAN_RECOMMENDED_LITERATURE." as lit", "lit.section_id = section.id")
	        			->condition("lit.section_id = ".$row["id"]);
        			$literature = array();
        			foreach ($queryLiter->execute()->getItems() as $items) {
        				foreach ($items as $lit) {
        					if (CBaseManager::getWorkPlanLiterature($lit)->type == "1") {
        						$literature[] = "Разд. 6.1 [".CBaseManager::getWorkPlanLiterature($lit)->ordering."]";
        					}
        					if (CBaseManager::getWorkPlanLiterature($lit)->type == "2") {
        						$literature[] = "Разд. 6.2 [".CBaseManager::getWorkPlanLiterature($lit)->ordering."]";
        					}
        					if (CBaseManager::getWorkPlanLiterature($lit)->type == "3") {
        						$literature[] = "Разд. 6.3 [".CBaseManager::getWorkPlanLiterature($lit)->ordering."]";
        					}
        				}
        			}
        			$dataRow = array();
        			$dataRow[0] = $row["sectionIndex"];
        			$dataRow[1] = $row["name"].": ".$row["content"];
        			$dataRow[2] = $row["lecture"];
        			$dataRow[3] = $row["practice"];
        			$dataRow[4] = $row["labwork"];
        			$dataRow[5] = $row["ksr"];
        			if ($selfWork) {
        				$dataRow[6] = $row["self_work"];
        			} else {
        				$dataRow[6] = $row["selfedu"];
        			}
        			$dataRow[7] = $row["total"];
        			$dataRow[8] = implode(", ", $literature);
        			$dataRow[9] = implode(", ", $technologies);
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
        		$total[2] = $lectureSum;
        		$total[3] = $practiceSum;
        		$total[4] = $labworkSum;
        		$total[5] = $ksrSum;
        		$total[6] = $selfeduSum;
        		$total[7] = $totalSum;
        		$total[8] = "";
        		$total[9] = "";
        		$result[] = $total;
        	}
        }
        if (empty($result)) {
        	$lectureSum = 0;
        	$practiceSum = 0;
        	$labworkSum = 0;
        	$ksrSum = 0;
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
        			if ($labor->type->getAlias() == "ksr") {
        				$ksrSum += $labor->value;
        			}
        			if ($labor->type->getAlias() == "self_work") {
        				$selfeduSum += $labor->value;
        			}
        			$totalSum = $lectureSum+$practiceSum+$labworkSum+$ksrSum+$selfeduSum;
        		}
        	}
        	$countSections = $lectureSum/2;
        	$taxonomy = CTaxonomyManager::getTaxonomy("corriculum_education_technologies");
        	$technologies = array();
        	foreach ($taxonomy->getTerms()->getItems() as $item) {
        		$technologies[] = $item->getValue();
        	}
        	for ($i = 1; $i <= $countSections; $i++) {
        		$dataRow = array();
        		$dataRow[0] = $i;
        		$dataRow[1] = "Раздел №".$i;
        		$dataRow[2] = round($lectureSum/$countSections, 0);
        		$dataRow[3] = round($practiceSum/$countSections, 0);
        		$dataRow[4] = round($labworkSum/$countSections, 0);
        		$dataRow[5] = round($ksrSum/$countSections, 0);
        		$dataRow[6] = round($selfeduSum/$countSections, 0);
        		$dataRow[7] = round($totalSum/$countSections, 0);
        		$dataRow[8] = "Разд. 6.1 [".$i."]";
        		$dataRow[9] = $technologies[array_rand($technologies, 1)];
        		$result[] = $dataRow;
        	}
        	$total = array();
        	$total[0] = "";
        	$total[1] = "Итого";
        	$total[2] = $lectureSum;
        	$total[3] = $practiceSum;
        	$total[4] = $labworkSum;
        	$total[5] = $ksrSum;
        	$total[6] = $selfeduSum;
        	$total[7] = $totalSum;
        	$total[8] = "";
        	$total[9] = "";
        	$result[] = $total;
        }
        return $result;
    }
}