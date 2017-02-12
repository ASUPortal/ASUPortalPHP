<?php

class CCourseProjectsTaskIssueDateDay extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "День даты выдачи задания для курсового проектирования";
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
        $result = date("d", strtotime($contextObject->courseProject->issue_date));
        return $result;
    }
}