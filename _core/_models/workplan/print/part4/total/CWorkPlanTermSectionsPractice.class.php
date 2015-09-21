<?php

class CWorkPlanTermSectionsPractice extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Общая нагрузка. Практики";
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
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
		$termSectionsData = new CArrayList();
        foreach ($contextObject->terms->getItems() as $term) {
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
        $result = 0;
        foreach ($termSectionsData->getItems() as $termId=>$termData) {
        	foreach ($termData as $row) {
        		$result += $row["practice"];
        	}
        }
        return $result;
    }
}