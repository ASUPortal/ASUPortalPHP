<?php

class CCourseProjectOrderNumber extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Номер распоряжения для курсового проектирования";
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
        $result = $contextObject->order_number;
        return $result;
    }
}