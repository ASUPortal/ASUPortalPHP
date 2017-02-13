<?php

class CCourseProjectsTaskMainContent extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Основное содержание задания для курсового проектирования";
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
        $result = $contextObject->courseProject->main_content;
        return $result;
    }
}