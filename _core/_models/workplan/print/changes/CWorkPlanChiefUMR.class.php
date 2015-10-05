<?php

class CWorkPlanChiefUMR extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Начальник УМР";
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
		$result = $contextObject->chief_umr;
        return $result;
    }
}