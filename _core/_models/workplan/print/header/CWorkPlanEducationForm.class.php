<?php

class CWorkPlanEducationForm extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Форма обучения";
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
		if (!is_null($plan->education_form_id)) {
			$result = CTaxonomyManager::getEductionForm($plan->education_form_id)->name;
		}
        return $result;
    }
}