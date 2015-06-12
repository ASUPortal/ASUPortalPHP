<?php

/**
 * Class CWorkPlanContentControl
 *
 * @property int section_id
 */
class CWorkPlanContentControl extends CActiveModel {
    protected $_table = TABLE_WORK_PLAN_CONTENT_CONTROLS;

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