<?php

class CWorkPlanInternetResources extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Интернет-ресурсы";
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
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
		$result = array();
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
	        ->from(TABLE_WORK_PLAN_LITERATURE." as t")
	        ->order("t.id asc")
	        ->condition("plan_id=".$contextObject->getId()." AND type=3");
        $objects = new CArrayList();
        foreach ($set->getItems() as $ar) {
        	$object = new CWorkPlanLiterature($ar);
        	$objects->add($object->getId(), $object);
        }
        foreach ($objects->getItems() as $row) {
        	$dataRow = array();
        	$dataRow[0] = (count($result) + 1).".";
        	$dataRow[1] = $row->book;
        	$result[] = $dataRow;
        }
        return $result;
    }
}