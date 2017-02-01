<?php

class CCourseProjectStudentGroup extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Учебная группа для курсового проектирования";
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
        $result = $contextObject->group->getName();
        return $result;
    }
}