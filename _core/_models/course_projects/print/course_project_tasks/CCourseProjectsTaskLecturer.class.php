<?php

class CCourseProjectsTaskLecturer extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Преподаватель задания для курсового проектирования";
    }

    public function getFieldDescription()
    {
        return "Используется при печати задания курсового проекта, принимает параметр id с Id задания курсового проекта";
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
        $result = $contextObject->courseProject->lecturer->getNameShort();
        return $result;
    }
}