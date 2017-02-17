<?php

class CCourseProjectIssueDate extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Дата выдачи задания для курсового проектирования";
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
        $result = date("d.m.Y", strtotime($contextObject->issue_date));
        return $result;
    }
}