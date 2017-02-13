<?php

class CCourseProjectGraduationDateAndTime extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Дата и время защит для курсового проектирования";
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
        $dates = explode(";", $contextObject->graduation_date);
        $times = explode(";", $contextObject->graduation_time);
        $i = 0;
        foreach ($dates as $date) {
            $dataRow = array();
            $dataRow[0] = $date;
            if (array_key_exists($i, $times)) {
                $dataRow[1] = $times[$i];
            } else {
                $dataRow[1] = "";
            }
            $result[] = $dataRow;
            $i++;
        }
        return $result;
    }
}