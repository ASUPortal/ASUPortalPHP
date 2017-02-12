<?php

class CCourseProjectsTaskIssueDate extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Дата выдачи задания студента для курсового проектирования";
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
        $result = "";
        $activity = CStaffService::getStudentActivityByTypeAndDate($contextObject->student, $contextObject->courseProject->discipline, $contextObject->courseProject->lecturer,
        		CCourseProjectConstants::CONTROL_TYPE_COURSE_PROJECT, $contextObject->courseProject->issue_date);
        if (!is_null($activity)) {
        	$result = date("d.m.Y", strtotime($activity->date_act));
        }
        return $result;
    }
}