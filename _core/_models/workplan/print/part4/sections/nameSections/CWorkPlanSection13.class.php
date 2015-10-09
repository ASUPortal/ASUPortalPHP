<?php

class CWorkPlanSection13 extends CWorkPlanSection1 {
    public function getFieldName()
    {
        return "Название тринадцатого раздела";
    }

    public function getNumberSection()
    {
    	$str = get_class($this);
    	return preg_replace('|[^0-9]*|','',$str);
    }
}