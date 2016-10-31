<?php

class CWorkPlanProtocolNMSNumber extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Номер протокола НМС рабочей программы";
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
		$result = "__";
		$protocols = array();
		if (!is_null($contextObject->protocolsNMS)) {
			foreach ($contextObject->protocolsNMS->getItems() as $protocol) {
				$protocols[] = $protocol->getNumber();
			}
		}
		if (!empty($protocols)) {
			$result = $protocols[0];
		}
		return $result;
    }
}