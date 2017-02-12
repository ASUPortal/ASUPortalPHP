<?php

class CCourseProjectCommissionMembers extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Члены комиссии для курсового проектирования";
    }

    public function getFieldDescription()
    {
        return "Используется при печати курсового проекта, принимает параметр id с Id курсового проекта";
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
        if (!is_null($contextObject->commision_members)) {
        	$arrayLength = $contextObject->commision_members->getCount();
        	$counter = 0;
        	foreach ($contextObject->commision_members->getItems() as $member) {
        		$counter++;
        		$person = $member->getNameShort()." – ";
        		$person .= $member->degree->comment;
        		if (!is_null($member->getPost())) {
        			$person .= ", ".$member->getPost()->getValue()." каф. АСУ";
        		}
        		if ($counter == $arrayLength) {
        			$prefix = ".";
        		} else {
        			$prefix = ";";
        		}
        		$dataRow = array();
        		$dataRow[0] = $person.$prefix;
        		$result[] = $dataRow;
        	}
        }
        return $result;
    }
}