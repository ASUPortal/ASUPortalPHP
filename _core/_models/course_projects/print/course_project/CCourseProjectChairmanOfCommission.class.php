<?php

class CCourseProjectChairmanOfCommission extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Председатель комиссии для курсового проектирования";
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
        $result = $contextObject->chairmanOfCommission->getNameShort()." – ";
        if (!is_null($contextObject->chairmanOfCommission->degree)) {
            $result .= $contextObject->chairmanOfCommission->degree->comment;
        }
        if (!is_null($contextObject->chairmanOfCommission->title)) {
            $result .= ", ".$contextObject->chairmanOfCommission->title->getValue();
        }
        if (!is_null($contextObject->chairmanOfCommission->getPost())) {
            $result .= ", ".$contextObject->chairmanOfCommission->getPost()->getValue();
        }
        return $result;
    }
}