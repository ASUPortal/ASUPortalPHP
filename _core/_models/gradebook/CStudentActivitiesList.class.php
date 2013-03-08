<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 17.12.12
 * Time: 21:26
 * To change this template use File | Settings | File Templates.
 */
class CStudentActivitiesList extends CFormModel {
    public static function getClassName() {
        return __CLASS__;
    }
    public function attributeLabels() {
        return array(
            "date_act" => "Дата записи",
            "subject_id" => "Дисциплина",
            "kadri_id" => "Преподаватель",
            "group_id" => "Учебная группа",
            "student_id" => "Студент",
            "study_act_id" => "Вид контроля",
            "study_act_comment" => "Номер занятия",
            "study_mark" => "Оценка",
            "comment" => "Комментарий"
        );
    }
    public function validationRules() {
        return array(
            "required" => array(
                "date_act"
            ),
            "selected" => array(
                "subject_id",
                "kadri_id",
                "study_act_id"
            )
        );
    }
}
