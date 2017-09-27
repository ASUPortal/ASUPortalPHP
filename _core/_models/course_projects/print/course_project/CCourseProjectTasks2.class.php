<?php

class CCourseProjectTasks2 extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Задания для курсового проектирования (со столбцом для подписи студента)";
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
        if (!$contextObject->tasks->isEmpty()) {
            foreach ($contextObject->tasks->getItems() as $item) {
                $dataRow = array();
                $dataRow[0] = count($result) + 1;
                $dataRow[1] = $item->student->getShortName();
                $dataRow[2] = $item->theme;
                $dataRow[3] = date("d.m.Y", strtotime($contextObject->issue_date));
                $dataRow[4] = "";
                $result[] = $dataRow;
            }
        }
        return $result;
    }
}