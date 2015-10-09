<?php

class CWorkPlanSection9 extends CWorkPlanSection1 {
    public function getFieldName()
    {
        return "Название девятого раздела";
    }

	public function getNumberSection()
    {
    	$str = get_class($this);
    	return preg_replace('|[^0-9]*|','',$str);
    }
}