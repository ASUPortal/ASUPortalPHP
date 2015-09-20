<?php

class CWorkPlanProjectThemes extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Темы курсовых проектов";
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
        $query = new CQuery();
        $query->select("t.*")
	        ->from(TABLE_WORK_PLAN_PROJECT_THEMES." as t")
	        ->order("t.id asc")
	        ->condition("plan_id=".$plan->getId());
        $objects = $query->execute();
        foreach ($objects->getItems() as $row) {
        	$dataRow = array();
        	$dataRow[0] = (count($result) + 1).".";
        	$dataRow[1] = $row["project_title"];
        	$result[] = $dataRow;
        }
        return $result;
    }
}