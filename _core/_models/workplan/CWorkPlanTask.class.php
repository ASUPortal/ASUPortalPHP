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

    public function attributeLabels() {
        return array(
            "task" => "Задача"
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