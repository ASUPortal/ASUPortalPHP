<?php

/**
 * Class CWorkPlanContentControl
 *
 * @property int section_id
 * @property CTerm control
 */
class CWorkPlanContentControl extends CActiveModel {
    protected $_table = TABLE_WORK_PLAN_CONTENT_CONTROLS;

    protected function relations() {
        return array(
            "control" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "control_id",
                "targetClass" => "CTerm"
            )
        );
    }


    public function attributeLabels() {
        return array(
            "control_id" => "Форма контроля"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "control_id"
            )
        );
    }


}