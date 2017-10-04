<?php

class CCourseProjectsTaskDisciplineCycle extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Цикл дисциплины задания для курсового проектирования";
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
    	$corriculum = $contextObject->courseProject->group->corriculum;
    	if (!is_null($corriculum)) {
    		foreach ($corriculum->cycles as $cycle) {
    			foreach ($cycle->allDisciplines as $discipline) {
    				if ($contextObject->courseProject->discipline->getId() == $discipline->discipline->getId()) {
    					if (!is_null($discipline->cycle)) {
    						$result = $discipline->cycle->title_abbreviated;
    					}
    				}
    			}
    		}
    	}
        return $result;
    }
}