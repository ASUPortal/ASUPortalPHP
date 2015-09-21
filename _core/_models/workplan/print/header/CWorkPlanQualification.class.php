<?php

class CWorkPlanQualification extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Квалификация";
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
		if (!is_null($contextObject->qualification_id)) {
			$result = $contextObject->qualification->getValue();
		}
        return $result;
    }
}