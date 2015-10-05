<?php
/**
 * 
 * @property int plan_id
 *
 * @property string question_title
 */
class CWorkPlanQuestionsToExamination extends CActiveModel {
    protected $_table = TABLE_WORK_PLAN_QUESTIONS_TO_EXAMINATION;

    public function attributeLabels() {
        return array(
            "question_title" => "Вопрос"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "question_title"
            )
        );
    }

}