<?php

class CDepartmentProtocolListened extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Слушали/постановили";
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
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
        $result = array();
        foreach ($contextObject->agenda->getItems() as $point) {
        	$dataRow = array();
        	$dataRow[0] = $point->section_id;
        	$dataRow[1] = "СЛУШАЛИ";
        	$dataRow[2] = $point->person->fio_short." ".$point->text_content;
        	$result[] = $dataRow;
        	
        	$dataRow = array();
        	$dataRow[0] = "";
        	$dataRow[1] = "ПОСТАНОВИЛИ";
        	$dataRow[2] = "";
        	if (!is_null($point->decision)) {
        		$dataRow[2] .= $point->decision->getValue();
        	}
        	$dataRow[2] .= $point->opinion_text;
        	$result[] = $dataRow;
        	
        	$dataRow = array();
        	$dataRow[0] = "";
        	$dataRow[1] = "";
        	$dataRow[2] = "";
        	$result[] = $dataRow;
        }
        return $result;
    }
}