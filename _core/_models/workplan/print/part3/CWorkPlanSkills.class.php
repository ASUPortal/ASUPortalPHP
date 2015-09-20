<?php

class CWorkPlanSkills extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Умения";
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
		$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
		$items = array();
		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_COMPETENTIONS, "plan_id = ".$plan->getId())->getItems() as $ar) {
			$item = new CWorkPlanCompetention($ar);
			if (!is_null($item->skills)) {
				foreach ($item->skills->getItems() as $item) {
					$items[] = CTaxonomyManager::getTerm($item->id)->name;
				}
			}
		}
		$result = implode("; ", $items);
        return $result;
    }
}