<?php

class CWorkPlanProtocolDepMonth extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Месяц даты протокола кафедры рабочей программы";
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
		$result = "______";
		$protocols = array();
		if (!is_null($contextObject->protocolsDep)) {
			foreach ($contextObject->protocolsDep->getItems() as $protocol) {
				$protocols[] = date("m", strtotime($protocol->getDate()));
			}
		}
		if (!empty($protocols)) {
			$monthNum = $protocols[0];
			$result = CUtils::getMonthAsWord($monthNum);
		}
		return $result;
    }
}