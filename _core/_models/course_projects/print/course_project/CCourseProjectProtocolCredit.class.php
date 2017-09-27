<?php

class CCourseProjectProtocolCredit extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Протокол зачёта для курсового проектирования";
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
                $dataRow[1] = $item->student->getName();
                $dataRow[2] = "";
                $activity = CStaffService::getStudentActivityByTypeAndDate($item->student, $contextObject->discipline, $contextObject->lecturer,
                		CTaxonomyManager::getLegacyTaxonomy("study_act")->getTerm(CCourseProjectConstants::CONTROL_TYPE_COURSE_PROJECT), $contextObject->issue_date);
                if (!is_null($activity)) {
                	$dataRow[2] = date("d.m.Y", strtotime($activity->date_act));
                }
                $dataRow[3] = $item->student->stud_num;
                $dataRow[4] = "";
                $studentActivity = CStaffService::getStudentActivityByTypeAndDate($item->student, $contextObject->discipline, $contextObject->lecturer,
                	CTaxonomyManager::getLegacyTaxonomy("study_act")->getTerm(CCourseProjectConstants::CONTROL_TYPE_COURSE_PROJECT), $contextObject->issue_date);
                if (!is_null($studentActivity)) {
                	$dataRow[4] = $studentActivity->mark->getValue();
                }
                $dataRow[5] = "";
                $result[] = $dataRow;
            }
        }
        return $result;
    }
}