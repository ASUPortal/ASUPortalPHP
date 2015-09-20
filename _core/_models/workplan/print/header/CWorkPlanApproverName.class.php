<?php

class CWorkPlanApproverName extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Утверждающий";
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
		$result = $plan->approver_name;
        return $result;
    }
}