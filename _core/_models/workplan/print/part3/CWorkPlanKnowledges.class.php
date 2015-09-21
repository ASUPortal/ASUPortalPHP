<?php

class CWorkPlanKnowledges extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Знания";
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
			if (!is_null($item->knowledges)) {
				foreach ($item->knowledges->getItems() as $item) {
					$items[] = CTaxonomyManager::getTerm($item->id)->name;
				}
			}
		}
		$result = implode("; ", $items);
        return $result;
    }
}