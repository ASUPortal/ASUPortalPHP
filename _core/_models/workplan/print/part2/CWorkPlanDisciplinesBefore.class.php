<?php

class CWorkPlanDisciplinesBefore extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Предшествующие дисциплины";
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
		$disciplines = array();
		if (!is_null($plan->disciplinesBefore)) {
			foreach ($plan->disciplinesBefore->getItems() as $discipline) {
				$discipline = CTaxonomyManager::getDiscipline($discipline->id);
				$disciplines[] = $discipline->name;
			}
		}
		$result = implode("; ", $disciplines);
        return $result;
    }
}