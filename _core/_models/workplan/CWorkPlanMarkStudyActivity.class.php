<?php
/**
 * 
 * @property int activity_id
 * @property string mark
 */
class CWorkPlanMarkStudyActivity extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_MARKS_STUDY_ACTIVITY;

    public function attributeLabels() {
        return array(
            "mark" => "Описание и количество баллов за учебную деятельность"
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