<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 02.04.15
 * Time: 20:19
 *
 * @property int section_id
 */
class CWorkPlanContentLecture extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_CONTENT_LECTURES;

    public function attributeLabels() {
        return array(
            "lecture_title" => "Название лекции"
        );
    }


    protected function validationRules() {
        return array(
            "required" => array(
                "lecture_title"
            )
        );
    }


}