<?php

class CDepartmentProtocolVisited extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Присутствовали";
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
        $visits = $contextObject->visits;
        $comparator = new CPersonComparator("fio");
        $protocolVisits = CCollectionUtils::sort($visits, $comparator);
        $items = array();
        foreach ($protocolVisits->getItems() as $visit) {
        	if ($visit->visit_type != 0) {
        		$items[] = $visit->person->fio_short;
        	}
        }
        $result = implode("; ", $items);
        return $result;
    }
}