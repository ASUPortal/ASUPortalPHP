<?php

class CWorkPlanSection12StudyTypes extends CWorkPlanSection1StudyTypes {
    public function getFieldName()
    {
        return "Виды учебной деятельности для двенадцатого раздела текущего контроля";
    }

    public function getNumberSection()
    {
    	$str = get_class($this);
    	return preg_replace('|[^0-9]*|','',$str);
    }
}