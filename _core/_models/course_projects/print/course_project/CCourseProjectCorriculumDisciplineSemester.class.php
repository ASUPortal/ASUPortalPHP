<?php

class CCourseProjectCorriculumDisciplineSemester extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Семестр дисциплины учебного плана курсового проектирования";
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
        $group = $contextObject->group;
        if (!is_null($group)) {
        	$corriculum = $group->corriculum;
        	if (!is_null($corriculum)) {
        		foreach ($corriculum->cycles as $cycle) {
        			foreach ($cycle->allDisciplines as $discipline) {
        				if ($contextObject->discipline->getId() == $discipline->discipline->getId()) {
        					if ($discipline->sections->getCount() != 0) {
        						$result = $discipline->sections->getFirstItem()->title;
        					}
        				}
        			}
        		}
        	}
        }
        return $result;
    }
}