<?php
/**
 * Задание на курсовое проектирование
 */
class CCourseProjectsTask extends CActiveModel {
    protected $_table = TABLE_COURSE_PROJECTS_TASKS;

    protected function relations() {
        return array(
            "student" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "student_id",
                "targetClass" => "CStudent"
            ),
            "courseProject" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageField" => "course_project_id",
                "targetClass" => "CCourseProject"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "student_id" => "ФИО студента",
            "theme" => "Тема"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "student_id"
            )
        );
    }

}