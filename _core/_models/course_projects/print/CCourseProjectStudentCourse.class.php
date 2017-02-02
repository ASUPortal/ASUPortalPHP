<?php

class CCourseProjectStudentCourse extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Учебный курс группы для курсового проектирования";
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
        $string = $contextObject->group->getName();
        // находим первое попавшееся число в названии группы
        preg_match('/\d+/', $string, $matches);
        $number = $matches[0];
        // берём из числа первую цифру
        $result = $number[0];
        return $result;
    }
}