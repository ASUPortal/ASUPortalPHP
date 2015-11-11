<?php
/**
 * 
 * @property int activity_id
 * @property string mark
 */
class CWorkPlanMarkStudyActivity extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_MARKS_STUDY_ACTIVITY;
    
    protected function relations() {
    	return array(
    		"activity" => array(
    				"relationPower" => RELATION_HAS_ONE,
    				"storageField" => "activity_id",
    				"targetClass" => "CWorkPlanControlTypes"
    		)
    	);
    }

    public function attributeLabels() {
        return array(
            "mark" => "Описание и количество баллов за учебную деятельность",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "mark"
            )
        );
    }

}