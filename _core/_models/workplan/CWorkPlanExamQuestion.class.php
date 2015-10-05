<?php
/**
 * 
 * @property int plan_id
 *
 * @property string question_title
 */
class CWorkPlanExamQuestion extends CActiveModel {
    protected $_table = TABLE_WORK_PLAN_EXAM_QUESTIONS;

    public function attributeLabels() {
        return array(
            "question" => "Вопрос"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "question"
            )
        );
    }

}