<?php

class CCourseProjectsTaskIssueDateMonth extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Месяц даты выдачи задания для курсового проектирования";
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
        $monthNum = date("m", strtotime($contextObject->courseProject->issue_date));
        return CUtils::getMonthAsWord($monthNum);
    }
}