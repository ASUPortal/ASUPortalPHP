<?php

class CCourseProjectLecturerNameShort extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Преподаватель для курсового проектирования";
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
        $result = $contextObject->lecturer->getNameShort();
        return $result;
    }
}