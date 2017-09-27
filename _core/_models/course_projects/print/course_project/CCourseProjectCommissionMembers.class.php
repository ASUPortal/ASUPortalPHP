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
        if (!$contextObject->commision_members->isEmpty()) {
        	foreach ($contextObject->commision_members->getItems() as $member) {
        		$person = $member->getNameShort();
        		if (!is_null($member->degree)) {
        			$person .= " – ".$member->degree->comment;
        		}
        		if (!is_null($member->getPost())) {
        			$person .= ", ".$member->getPost()->getValue()." каф. АСУ";
        		}
        		$dataRow = array();
        		$dataRow[0] = $person.";";
        		$result[] = $dataRow;
        	}
        }
        // добавляем к списку членов комиссии преподавателя
        $lecturer = $contextObject->lecturer->getNameShort()." – ";
        if (!is_null($contextObject->lecturer->degree)) {
        	$lecturer .= $contextObject->lecturer->degree->comment;
        }
        if (!is_null($contextObject->lecturer->getPost())) {
        	$lecturer .= ", ".$contextObject->lecturer->getPost()->getValue()." каф. АСУ";
        }
        $row = array();
        $row[0] = $lecturer.".";
        $result[] = $row;
        return $result;
    }
}