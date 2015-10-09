<?php

class CWorkPlanSection7 extends CWorkPlanSection1 {
    public function getFieldName()
    {
        return "Название седьмого раздела";
    }

	public function getNumberSection()
    {
    	$str = get_class($this);
    	return preg_replace('|[^0-9]*|','',$str);
    }
}