<?php

class CWorkPlanSelfWork extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Самоподготовка";
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
    	$selfWorkValueOfLoad = 0;
    	foreach ($contextObject->corriculumDiscipline->labors->getItems() as $labor) {
    		if ($labor->type->getAlias() == "self_work") {
    			$selfWorkValueOfLoad = $labor->value;
    		}
    	}
    	$projectValueOfLoad = 0;
    	foreach ($contextObject->corriculumDiscipline->labors->getItems() as $labor) {
    		if ($labor->type->getAlias() == "course_work") {
    			$projectValueOfLoad = $labor->value;
    		}
    	}
    	$selfEduTotal = 0;
    	foreach ($contextObject->selfEducations->getItems() as $row) {
    		$selfEduTotal += $row->question_hours;
    	}
    	$result = $selfWorkValueOfLoad-$projectValueOfLoad-$selfEduTotal;
		return $result;
    }
}