<?php

class CWorkPlanExamQuestionsTitle extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Вопросы к экзамену. Заголовок";
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
    	$result = "";
    	$items = array();
    	if (!is_null($contextObject->finalControls)) {
    		foreach ($contextObject->finalControls->getItems() as $control) {
    			$items[] = $control->controlType;
    		}
    	}
    	if (in_array("Экзамен", $items)) {
    		$result = "Вопросы к экзамену";
    	}
        return $result;
    }
}