<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 05.04.15
 * Time: 17:39
 *
 * @property CWorkPlanTerm term
 */
class CWorkPlanTermPractice extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_TERM_PRACTICES;

    protected function relations() {
        return array(
            "term" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "term_id",
                "targetClass" => "CWorkPlanTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "term_id" => "Семестр",
            "practice_num" => "Номер",
            "section_num" => "Номер раздела",
            "title" => "Тема",
            "hours" => "Количество часов"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "term_id"
            ),
            "required" => array(
                "practice_num",
                "section_num",
                "title",
                "hours"
            )
        );
    }


}