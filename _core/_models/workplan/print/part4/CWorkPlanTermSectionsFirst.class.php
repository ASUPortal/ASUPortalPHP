<?php

class CWorkPlanTermSectionsFirst extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Нагрузка для первого семестра в списке";
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
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
		if (!is_null($plan->terms)) {
        	$terms = array();
        	foreach ($plan->terms->getItems() as $term) {
        		$terms[] = $term->number;
        	}
        }
		$termSectionsData = new CArrayList();
        foreach ($plan->terms->getItems() as $term) {
            $query = new CQuery();
            $select = array();
            $select[] = "section.sectionIndex";
            $select[] = "section.name";
            $select[] = "sum(if(term.alias in ('lecture', 'practice', 'labwork'), l.value, 0)) + sum(selfedu.question_hours) as total";
            $select[] = "sum(if(term.alias = 'lecture', l.value, 0)) as lecture";
            $select[] = "sum(if(term.alias = 'practice', l.value, 0)) as practice";
            $select[] = "sum(if(term.alias = 'labwork', l.value, 0)) as labwork";
            $select[] = "sum(selfedu.question_hours) as selfedu";
            $query->select(join(", ", $select))
                ->from(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section")
                ->innerJoin(TABLE_WORK_PLAN_CONTENT_LOADS." as l", "l.section_id = section.id")
                ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
                ->leftJoin(TABLE_WORK_PLAN_SELFEDUCATION." as selfedu", "selfedu.load_id = l.id")
                ->group("l.section_id")
                ->condition("l.term_id = ".$term->getId());
            $items = $query->execute();
            if ($items->getCount() > 0) {
                $termSectionsData->add($term->getId(), $items);
            }
        }
        foreach ($termSectionsData->getItems() as $termId=>$termData) {
        	if (CBaseManager::getWorkPlanTerm($termId)->number == $terms[0]) {
        		foreach ($termData as $row) {
        			$dataRow = array();
        			$dataRow[0] = $row["sectionIndex"];
        			$dataRow[1] = $row["name"];
        			$dataRow[2] = $row["total"];
        			$dataRow[3] = $row["lecture"];
        			$dataRow[4] = $row["practice"];
        			$dataRow[5] = $row["labwork"];
        			$dataRow[6] = $row["selfedu"];
        			$result[] = $dataRow;
        		}
        	}
        }
        return $result;
    }
}