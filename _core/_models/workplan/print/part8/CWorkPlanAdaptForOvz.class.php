<?php

class CWorkPlanAdaptForOvz extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Адаптация рабочей программы для лиц с ОВЗ";
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
		$result = $contextObject->adapt_for_ovz;
        return $result;
    }
}