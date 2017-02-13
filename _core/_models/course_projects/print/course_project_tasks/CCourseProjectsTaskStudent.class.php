<?php

class CCourseProjectsTaskStudent extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Студент задания для курсового проектирования";
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
        $result = $contextObject->student->getShortName();
        return $result;
    }
}