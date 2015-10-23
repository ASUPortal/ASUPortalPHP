<?php

class CWorkPlanProjectValueOfLoad extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Значение нагрузки по курсовой работе";
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
    	$result = 0;
		foreach ($contextObject->corriculumDiscipline->labors->getItems() as $labor) {
        	if ($labor->type->getAlias() == "course_work") {
        		$result = $labor->value;
        	}
        }
		return $result;
    }
}