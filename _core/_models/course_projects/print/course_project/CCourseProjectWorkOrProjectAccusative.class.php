<?php

class CCourseProjectWorkOrProjectAccusative extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Курсовой проект или курсовая работа для курсового проектирования (винительный падеж)";
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
        $corriculum = $contextObject->group->corriculum;
        if (!is_null($corriculum)) {
	        	foreach ($corriculum->cycles as $cycle) {
	        		foreach ($cycle->allDisciplines as $discipline) {
	        			if ($contextObject->discipline->getId() == $discipline->discipline->getId()) {
	        				foreach ($discipline->sections->getItems() as $section) {
	        					foreach ($section->labors->getItems() as $labor) {
	        						if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_COURSE_WORK) {
	        							$result = "курсовую работу";
	        						}
	        						if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_COURSE_PROJECT) {
	        							$result = "курсовой проект";
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