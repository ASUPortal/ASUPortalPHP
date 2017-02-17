<?php

class CCourseProjectNotAllowedStudents extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Недопущенные к защите студенты для курсового проектирования";
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
        $res = array();
        $studentActivities = new CArrayList();
        foreach ($contextObject->group->getStudents() as $student) {
            $activity = CStaffService::getStudentActivityByTypeAndDate($student, $contextObject->discipline, $contextObject->lecturer,
                    CTaxonomyManager::getLegacyTaxonomy("study_act")->getTerm(CCourseProjectConstants::CONTROL_TYPE_COURSE_PROJECT), $contextObject->issue_date);
            if (!is_null($activity)) {
                $studentActivities->add($activity->getId(), $activity);
            }
        }
        foreach ($studentActivities->getItems() as $studentActivity) {
            if (CStaffService::isStudentActivityWithBadMark($studentActivity)) {
                $row = array();
                $row[0] = $studentActivity->student->getName();
                $res[] = $row;
            }
        }
        $result = array();
        $counter = 0;
        $countRes = count($res);
        foreach ($res as $row) {
            $counter++;
            if ($counter == $countRes) {
                $prefix = ".";
            } else {
                $prefix = ";";
            }
            $dataRow = array();
            $dataRow[0] = $row[0].$prefix;
            $result[] = $dataRow;
        }
        return $result;
    }
}