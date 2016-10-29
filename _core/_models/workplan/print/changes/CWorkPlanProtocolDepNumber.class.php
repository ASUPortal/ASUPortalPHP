<?php

class CWorkPlanProtocolDepNumber extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Номер протокола кафедры рабочей программы";
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
		$protocols = array();
		if (!is_null($contextObject->protocolsDep)) {
			foreach ($contextObject->protocolsDep->getItems() as $protocol) {
				$protocols[] = $protocol->getNumber();
			}
		}
		$result = @$protocols[0];
        return $result;
    }
}