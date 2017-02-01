<?php

class CCourseProjectEducationForm extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Форма обучения для курсового проектирования";
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
        $group = $contextObject->group;
        if (!is_null($group)) {
            $corriculum = $group->corriculum;
            if (!is_null($corriculum)) {
                $result = $corriculum->educationForm->getValue();
            }
        }
        return $result;
    }
}