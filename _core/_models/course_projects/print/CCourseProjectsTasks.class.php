<?php

class CCourseProjectsTasks extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Задания для курсового проектирования";
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
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
        $result = array();
        if (!is_null($contextObject->tasks)) {
            foreach ($contextObject->tasks->getItems() as $item) {
                $dataRow = array();
                $dataRow[0] = count($result) + 1;
                $dataRow[1] = $item->student->getName();
                $dataRow[2] = $item->theme;
                $dataRow[3] = "";
                $result[] = $dataRow;
            }
        }
        return $result;
    }
}