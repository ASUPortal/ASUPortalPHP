<?php

class CDepartmentProtocolNotVisited extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Не присутствовали";
    }

    public function getFieldDescription()
    {
        return "Используется при печати протокола кафедры, принимает параметр id с Id протокола кафедры";
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
        $items = array();
        foreach ($contextObject->visits->getItems() as $visit) {
        	if ($visit->visit_type == 0) {
        		$items[] = $visit->person->fio_short;
        	}
        }
        $result = implode("; ", $items);
        return $result;
    }
}