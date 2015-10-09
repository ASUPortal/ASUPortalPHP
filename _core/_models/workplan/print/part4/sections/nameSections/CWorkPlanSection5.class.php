<?php

class CWorkPlanSection5 extends CWorkPlanSection1 {
    public function getFieldName()
    {
        return "Название пятого раздела";
    }

	public function getNumberSection()
    {
    	$str = get_class($this);
    	return preg_replace('|[^0-9]*|','',$str);
    }
}