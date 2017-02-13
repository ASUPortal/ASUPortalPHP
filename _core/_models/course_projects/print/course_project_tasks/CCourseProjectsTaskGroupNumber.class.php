<?php

class CCourseProjectsTaskGroupNumber extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Номер группы задания для курсового проектирования";
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
        $string = $contextObject->courseProject->group->getName();
        // берем все числа из названия группы
        $result = preg_replace('|[^0-9]*|', '', $string);
        return $result;
    }
}