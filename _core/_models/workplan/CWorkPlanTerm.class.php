<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 03.04.15
 * Time: 15:05
 *
 * @property int plan_id
 * @property String number
 * @property CWorkPlan plan

 */
class CWorkPlanTerm extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TERMS;

    protected function relations(){
        return array(
            "plan" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "plan_id",
                "targetClass" => "CWorkPlan"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "number" => "Номер семестра"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "number"
            )
        );
    }

    function __toString() {
        return $this->number;
    }


}