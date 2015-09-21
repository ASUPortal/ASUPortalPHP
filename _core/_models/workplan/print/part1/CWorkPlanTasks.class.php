<?php

class CWorkPlanTasks extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Задачи";
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
		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_TASKS, "plan_id = ".$contextObject->getId())->getItems() as $ar) {
			$item = new CWorkPlanTask($ar);
			$items[] = $item->task;
		}
		$result = implode("; ", $items);
        return $result;
    }
}