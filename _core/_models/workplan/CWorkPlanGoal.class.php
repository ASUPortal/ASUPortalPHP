<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 02.04.15
 * Time: 18:27
 *
 * @property int plan_id
 * @property string goal
 * @property CArrayList tasks
 * @property CWorkPlan plan
 */
class CWorkPlanGoal extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_GOALS;

    protected function relations() {
        return array(
            "tasks" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_tasks",
                "storageTable" => TABLE_WORK_PLAN_TASKS,
                "storageCondition" => "goal_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanTask",
                "managerOrder" => "`ordering` asc"
            ),
            "plan" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "plan_id",
                "targetClass" => "CWorkPlan"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "goal" => "Цель",
            "ordering" => "Порядковый номер"
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