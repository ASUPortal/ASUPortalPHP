<?php

class CWorkPlanSection2 extends CWorkPlanSection1 {
    public function getFieldName()
    {
        return "Название второго раздела";
    }

	public function getNumberSection()
    {
    	$str = get_class($this);
    	return preg_replace('|[^0-9]*|','',$str);
    }
}