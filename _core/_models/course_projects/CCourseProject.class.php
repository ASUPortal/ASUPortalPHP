<?php
/**
 * Курсовой проект
 * 
 */
class CCourseProject extends CActiveModel {
    protected $_table = TABLE_COURSE_PROJECTS;
    protected $_group = null;
    protected $_discipline = null;
    protected $_lecturer = null;
    
    protected function relations() {
        return array(
            "group" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_group",
                "storageField" => "group_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudentGroup"
            ),
            "discipline" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_discipline",
                "storageField" => "discipline_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getDiscipline"
            ),
            "lecturer" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_lecturer",
                "storageField" => "lecturer_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "tasks" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_tasks",
                "storageTable" => TABLE_COURSE_PROJECTS_TASKS,
                "storageCondition" => "course_project_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CCourseProjectsTask",
                "managerOrder" => "`student_id` asc"
            )
        );
    }
    
    public function fieldsProperty() {
        return array(
            "order_date" => array(
                "type" => FIELD_MYSQL_DATE,
                "format" => "d.m.Y"
            )
        );
    }
    
    public function validationRules() {
        return array(
            "checkdate" => array(
                "order_date"
            ),
            "selected" => array(
                "group_id",
                "discipline_id",
                "lecturer_id"
            )
        );
    }
    
    public function attributeLabels() {
        return array(
            "group_id" => "Учебная группа",
            "discipline_id" => "Дисциплина",
            "lecturer_id" => "Преподаватель",
            "order_number" => "Номер распоряжения",
            "order_date" => "Дата распоряжения"
        );
    }
    
}
