<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 12.12.12
 * Time: 21:35
 * To change this template use File | Settings | File Templates.
 */
class CStudentActivity extends CActiveModel {
    protected $_table = TABLE_STUDENTS_ACTIVITY;
    protected $_discipline = null;
    protected $_person = null;
    protected $_student = null;
    protected $_controlType = null;
    protected $_mark = null;
    public $group_id = null;

    public function getDate() {
        return date("d.m.Y", strtotime($this->date_act));
    }
    public function relations() {
        return array(
            "discipline" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_discipline",
                "storageField" => "subject_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
            ),
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "kadri_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "student" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_student",
                "storageField" => "student_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudent"
            ),
            "controlType" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_controlType",
                "storageField" => "study_act_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getControlType"
            ),
            "mark" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_mark",
                "storageField" => "study_mark",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getMark"
            ),
        );
    }
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
                "date_act",
                "subject_id",
                "kadri_id",
                "student_id",
                "study_act_id",
                "study_mark"
            ),
            "selected" => array(
                "subject_id",
                "student_id",
                "kadri_id",
                "study_act_id",
                "study_mark"
            )
        );
    }
}
