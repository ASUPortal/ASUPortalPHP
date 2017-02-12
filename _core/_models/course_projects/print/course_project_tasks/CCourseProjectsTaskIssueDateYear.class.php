<?php

class CCourseProjectsTaskIssueDateYear extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Год даты выдачи задания для курсового проектирования";
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
        $result = date("Y", strtotime($contextObject->courseProject->issue_date));
        return $result;
    }
}