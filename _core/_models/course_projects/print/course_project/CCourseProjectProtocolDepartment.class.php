<?php

class CCourseProjectProtocolDepartment extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Протокол заседания кафедры";
    }

    public function getFieldDescription()
    {
        return "Используется при печати курсового проекта, принимает параметр id с Id рабочей программы";
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
        $result = "";
        $protocol = $contextObject->protocol;
        if (!is_null($protocol)) {
            $result = "№ ".$protocol->getNumber()." от ".date("d.m.Y", strtotime($protocol->getDate()));
        }
        return $result;
    }
}