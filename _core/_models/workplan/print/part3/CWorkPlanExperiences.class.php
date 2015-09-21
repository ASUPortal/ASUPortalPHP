<?php

class CWorkPlanExperiences extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Навыки";
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
		$items = array();
		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_COMPETENTIONS, "plan_id = ".$contextObject->getId())->getItems() as $ar) {
			$item = new CWorkPlanCompetention($ar);
			if (!is_null($item->experiences)) {
				foreach ($item->experiences->getItems() as $item) {
					$items[] = CTaxonomyManager::getTerm($item->id)->name;
				}
			}
		}
		$result = implode("; ", $items);
        return $result;
    }
}