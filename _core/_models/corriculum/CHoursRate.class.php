<?php

class CHoursRate extends CActiveModel{
    protected $_table = TABLE_HOURS_RATE;
    
    public function attributeLabels() {
    	return array(
    			"dolgnost_id" => "Должность",
    			"rate" => "Размер нагрузки, часы",
    			"comment" => "Примечание"
    	);
    }
    
    public function validationRules() {
        return array(
            "required" => array(
                "rate"
            ),
            "selected" => array(
                "dolgnost_id"
            )
        );
    }
}