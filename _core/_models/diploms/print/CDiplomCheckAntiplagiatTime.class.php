<?php

class CDiplomCheckAntiplagiatTime extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Время проверки ВКР на антиплагиат";
    }

    public function getFieldDescription()
    {
        return "Используется при печати тем ВКР, принимает параметр id с Id темы ВКР";
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
    	$result = "";
    	if ($contextObject->checksOnAntiplagiat->getCount() != 0) {
    		if ($contextObject->checksOnAntiplagiat->getLastItem()->check_time_on_antiplagiat !== "") {
    			$checkTime = $contextObject->checksOnAntiplagiat->getLastItem()->check_time_on_antiplagiat;
    			$result = substr($checkTime, 0, strpos($checkTime, ":"))." час. ";
    			$result .= substr($checkTime, strpos($checkTime, ":") + 1)." мин.";
    		}
    	}
        return $result;
    }
}