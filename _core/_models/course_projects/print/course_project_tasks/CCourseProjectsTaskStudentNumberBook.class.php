<?php

class CCourseProjectsTaskStudentNumberBook extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Последние 3 цифры зачётной книжки студента задания для курсового проектирования";
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
    	$result = "";
    	if ($contextObject->student->stud_num != "") {
    		$result = substr($contextObject->student->stud_num, -3);
    	}
        return $result;
    }
}