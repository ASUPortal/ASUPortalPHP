<?php

class CCourseProjectYear extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Учебный год для курсового проектирования";
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
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
        $result = "";
        $date = date("Y-m-d 00:00:00", strtotime($contextObject->issue_date));
        $years = array();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_YEARS, 'date_start <= "'.$date.'" and date_end >= "'.$date.'"')->getItems() as $ar) {
            $term = new CTerm($ar);
            $years[] = $term->getValue();
        }
        if (!empty($years)) {
        	$result = $years[0];
        }
        return $result;
    }
}