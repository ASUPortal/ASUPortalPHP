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
    	if (!is_null($contextObject->finalControls)) {
    		foreach ($contextObject->finalControls->getItems() as $control) {
    			$item = $control->controlType;
    		}
    	}
    	if ($item == "Экзамен") {
    		$result = "Вопросы к экзамену";
    	}
        return $result;
    }
}