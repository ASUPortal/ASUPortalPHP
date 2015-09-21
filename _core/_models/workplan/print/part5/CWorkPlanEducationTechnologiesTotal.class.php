<?php

class CWorkPlanEducationTechnologiesTotal extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Образовательные технологии. Всего часов";
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
        $result = array();
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $result = 0;
        foreach ($plan->getTechnologies()->getItems() as $row) {
        	$result += $row->value;
        }
        return $result;
    }
}