<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 02.04.15
 * Time: 18:27
 *
 * @property int plan_id
 * @property string goal
 */
class CWorkPlanGoal extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_GOALS;

    public function attributeLabels() {
        return array(
            "goal" => "Цель"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "goal"
            )
        );
    }


}