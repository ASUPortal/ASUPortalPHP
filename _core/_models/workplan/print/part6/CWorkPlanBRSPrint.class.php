<?php

class CWorkPlanBRSPrint extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Балльно-рейтинговая система";
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
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
		$result = array();
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
	        ->from(TABLE_WORK_PLAN_BRS." as t")
	        ->order("t.id asc")
	        ->condition("plan_id=".$plan->getId());
        $objects = new CArrayList();
        foreach ($set->getItems() as $ar) {
        	$object = new CWorkPlanBRS($ar);
        	$objects->add($object->getId(), $object);
        }
        foreach ($objects->getItems() as $row) {
        	$dataRow = array();
        	$dataRow[0] = count($result) + 1;
        	$dataRow[1] = $row->mark;
        	$dataRow[2] = $row->range;
        	if ($row->is_ok) {
        		$dataRow[3] = "Аттестация успешная";
        	}
			else {
				$dataRow[3] = "Аттестация не пройдена";
			}
        	$dataRow[4] = $row->comment;
        	$result[] = $dataRow;
        }
        return $result;
    }
}