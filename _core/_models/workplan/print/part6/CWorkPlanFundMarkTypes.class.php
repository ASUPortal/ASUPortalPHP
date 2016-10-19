<?php

class CWorkPlanFundMarkTypes extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Фонд оценочных средств";
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
		$ar_sort = array();
		if (!is_null($contextObject->fundMarkTypes)) {
			$ar = $contextObject->fundMarkTypes->getItems();
			foreach ($ar as $row) {
				$ar_sort[] = $row->section->sectionIndex;
			}
			array_multisort($ar_sort, SORT_ASC, $ar);
			 
			foreach ($ar as $row) {
				$dataRow = array();
				$dataRow[0] = count($result) + 1;
				$dataRow[1] = $row->section->name;
				$sectionId = $row->section_id;
				$codes = array();
				$levels = array();
				foreach ($row->competentions->getItems() as $competention) {
					$str = $competention;
					//берем код компетенции - текст из скобок
					preg_match('/\((.+)\)/', $str, $m);
					$codes[] = $m[1];
					foreach (CWorkPlanManager::getWorkplanCompetentionFormed(CWorkPlanManager::getWorkplan($contextObject->getId()), $competention) as $items) {
						$levels[] = CTaxonomyManager::getTerm($items->level_id);
					}
				}
				$dataRow[2] = implode(", ", $codes);
				$dataRow[3] = implode(", ", array_unique($levels));
				$dataRow[4] = implode(", ", CBaseManager::getWorkPlanContentSection($sectionId)->controls->getItems());
				$result[] = $dataRow;
			}
		}
        return $result;
    }
}