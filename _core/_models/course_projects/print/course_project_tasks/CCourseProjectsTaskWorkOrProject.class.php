<?php

class CCourseProjectsTaskWorkOrProject extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Курсовой проект или курсовая работа для курсового проектирования (родительный падеж)";
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
        $corriculum = $contextObject->courseProject->group->corriculum;
        if (!is_null($corriculum)) {
	        	foreach ($corriculum->cycles as $cycle) {
	        		foreach ($cycle->allDisciplines as $discipline) {
	        			if ($contextObject->courseProject->discipline->getId() == $discipline->discipline->getId()) {
	        				foreach ($discipline->sections->getItems() as $section) {
	        					foreach ($section->labors->getItems() as $labor) {
	        						if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_COURSE_WORK) {
	        							$result = "курсовой работы";
	        						}
	        						if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_COURSE_PROJECT) {
	        							$result = "курсового проекта";
	        						}
	        					}
	        				}
	        			}
	        		}
	        	}
        }
        return $result;
    }
}