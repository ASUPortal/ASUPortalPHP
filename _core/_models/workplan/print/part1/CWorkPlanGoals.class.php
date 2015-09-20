<?php

class CWorkPlanGoals extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Цели";
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
		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_GOALS, "plan_id = ".$plan->getId())->getItems() as $ar) {
			$item = new CWorkPlanGoal($ar);
			$items[] = $item->goal;
		}
		$result = implode("; ", $items);
        return $result;
    }
}