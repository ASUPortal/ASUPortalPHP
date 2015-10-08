<?php

class CWorkPlanSelfWorkValueOfLoad extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Значение нагрузки по самостоятельной работе";
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
		foreach ($contextObject->corriculumDiscipline->labors->getItems() as $labor) {
        	if ($labor->type->getAlias() == "self_work") {
        		$result = $labor->value;
        	}
        }
		return $result;
    }
}