<?php

class CCourseProjectOrderDate extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Дата распоряжения для курсового проектирования";
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
        $result = date("d", strtotime($contextObject->order_date))." ".CUtils::getMonthAsWord(date("m", strtotime($contextObject->order_date)))." ".date("Y", strtotime($contextObject->order_date));
        return $result;
    }
}