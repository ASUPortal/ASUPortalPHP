<?php

class CWorkPlanProtocolNMSYear extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Год даты протокола НМС рабочей программы";
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
		if (!is_null($contextObject->protocolsNMS)) {
			foreach ($contextObject->protocolsNMS->getItems() as $protocol) {
				$protocols[] = date("Y", strtotime($protocol->getDate()));
			}
		}
		if (!empty($protocols)) {
			$result = $protocols[0];
		} else {
			$result = "20__";
		}
        return $result;
    }
}