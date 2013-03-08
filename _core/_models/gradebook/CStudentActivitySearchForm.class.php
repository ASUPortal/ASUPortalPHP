<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 26.12.12
 * Time: 21:10
 * To change this template use File | Settings | File Templates.
 */
class CStudentActivitySearchForm extends CFormModel{
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "date_start" => "Начало периода",
            "date_end" => "Окончание периода",
            "subject_id" => "Дисциплина",
            "kadri_id" => "Преподаватель",
            "group_id" => "Группа"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "date_start",
                "date_end"
            ),
            "selected" => array(
                "subject_id",
                "kadri_id",
                "group_id"
            )
        );
    }
}
