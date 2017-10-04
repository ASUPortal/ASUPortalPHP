<?php

class CCourseProjectsTaskDepartmentNumber extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Номер кафедры в задании для курсового проектирования";
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
        $result = CTaxonomyManager::getTaxonomy("curriculum_plan_departments")->getTerm("автоматизированных систем управления")->getAlias();
        return $result;
    }
}