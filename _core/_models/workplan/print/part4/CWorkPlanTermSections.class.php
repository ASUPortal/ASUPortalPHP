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
        			if ($load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK) {
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
        			$select[] = "sum(if(term.alias in ('".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE."',
            			 '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE."',
            			 '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK."',
            			 '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR."',
            			 '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK."'), l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_TOTAL."";
        		} else {
        			$select[] = "sum(if(term.alias in ('".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE."',
            			'".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE."',
            			'".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK."',
            			'".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR."'), l.value, 0)) + sum(ifnull(selfedu.question_hours, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_TOTAL."";
        		}
        		$select[] = "sum(if(term.alias = '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE."', l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE."";
        		$select[] = "sum(if(term.alias = '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE."', l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE."";
        		$select[] = "sum(if(term.alias = '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK."', l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK."";
        		$select[] = "sum(if(term.alias = '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR."', l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR."";
        		if ($selfWork) {
        			$select[] = "sum(if(term.alias = '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK."', l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK."";
        		} else {
        			$select[] = "sum(ifnull(selfedu.question_hours, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_EDUCATION."";
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
        			$dataRow[2] = $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE];
        			$dataRow[3] = $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE];
        			$dataRow[4] = $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK];
        			$dataRow[5] = $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR];
        			if ($selfWork) {
        				$dataRow[6] = $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK];
        			} else {
        				$dataRow[6] = $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_EDUCATION];
        			}
        			$dataRow[7] = $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_TOTAL];
        			$dataRow[8] = implode(", ", $literature);
        			$dataRow[9] = implode(", ", $technologies);
        			$result[] = $dataRow;
        			
        			$lectureSum += $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE];
        			$practiceSum += $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE];
        			$labworkSum += $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK];
        			$ksrSum += $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR];
        			if ($selfWork) {
        				$selfeduSum += $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK];
        			} else {
        				$selfeduSum += $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_EDUCATION];
        			}
        			$totalSum += $row[CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_TOTAL];
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
        			if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE) {
        				$lectureSum += $labor->value;
        			}
        			if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE) {
        				$practiceSum += $labor->value;
        			}
        			if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK) {
        				$labworkSum += $labor->value;
        			}
        			if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR) {
        				$ksrSum += $labor->value;
        			}
        			if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK) {
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