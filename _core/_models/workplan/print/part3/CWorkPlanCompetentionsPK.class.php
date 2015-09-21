<?php

class CWorkPlanCompetentionsPK extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Компетенции ПК";
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
			if (!is_null($item->competention)) {
				if (strpos($item->competention->getValue(), "(ПК-") !== false) {
					$items[] = $item->competention->getValue();
				}
			}
		}
		$result = implode("; ", $items);
        return $result;
    }
}