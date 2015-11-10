<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 16.06.15
 * Time: 22:39
 *
 * @property int plan_id
 * @property int load_id
 * @property string question_title
 * @property string question_hours
 */
class CWorkPlanSelfEducationBlock extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_SELFEDUCATION;
    
    protected function relations() {
    	return array(
    		"load" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_load",
    			"storageField" => "load_id",
    			"targetClass" => "CWorkPlanContentSectionLoad"
    		)
    	);
    }

    public function attributeLabels() {
        return array(
            "question_title" => "Вопрос",
            "question_hours" => "Количество часов",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "question_title",
                "question_hours"
            )
        );
    }


}