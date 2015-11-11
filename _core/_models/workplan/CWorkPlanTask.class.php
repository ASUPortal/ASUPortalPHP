<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 02.04.15
 * Time: 18:25
 *
 * @property int plan_id
 * @property string task
 */
class CWorkPlanTask extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TASKS;
    
    protected function relations() {
    	return array(
    		"plan" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageField" => "plan_id",
    			"targetClass" => "CWorkPlan"
    		),
    		"goal" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageField" => "goal_id",
    			"targetClass" => "CWorkPlanGoal"
    		)
    	);
    }

    public function attributeLabels() {
        return array(
            "task" => "Задача",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "task"
            )
        );
    }


}