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
    protected $_chairman_of_commission = null;
    protected $_protocol = null;
    protected $_protocolIssuingThemes = null;
    protected $_protocolProgress = null;
    protected $_protocolResults = null;
    
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
                "targetClass" => "CCourseProjectsTask"
            ),
            "chairmanOfCommission" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_chairman_of_commission",
                "storageField" => "chairman_of_commission",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "commision_members" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_commision_members",
                "joinTable" => TABLE_COURSE_PROJECTS_COMMISSION_MEMBERS,
                "leftCondition" => "course_project_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "person_id",
                "managerClass" => "CBaseManager",
                "managerGetObject" => "getPerson"
            ),
            "protocol" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_protocol",
                "storageField" => "protocol_id",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
            ),
            "protocolIssuingThemes" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_protocolIssuingThemes",
                "storageField" => "issuing_themes",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
            ),
            "protocolProgress" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_protocolProgress",
                "storageField" => "progress",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
            ),
            "protocolResults" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_protocolResults",
                "storageField" => "results",
                "managerClass" => "CProtocolManager",
                "managerGetObject" => "getDepProtocol"
            )
        );
    }
    
    public function fieldsProperty() {
        return array(
            "order_date" => array(
                "type" => FIELD_MYSQL_DATE,
                "format" => "d.m.Y"
            ),
            "issue_date" => array(
                "type" => FIELD_MYSQL_DATE,
                "format" => "d.m.Y"
            )
        );
    }
    
    protected function modelValidators() {
        return array(
            new CCourseProjectValidator()
        );
    }
    
    public function validationRules() {
        return array(
            "checkdate" => array(
                "order_date",
                "issue_date"
            ),
            "selected" => array(
                "group_id",
                "discipline_id",
                "lecturer_id",
                "chairman_of_commission"
            )
        );
    }
    
    public function attributeLabels() {
        return array(
            "group_id" => "Учебная группа",
            "discipline_id" => "Дисциплина",
            "lecturer_id" => "Преподаватель",
            "order_number" => "Номер распоряжения",
            "order_date" => "Дата распоряжения",
            "chairman_of_commission" => "Председатель комиссии",
            "commision_members" => "Члены комиссии",
            "issue_date" => "Дата выдачи задания",
            "main_content" => "Основное содержание",
            "graduation_date" => "Даты защит",
            "graduation_time" => "Время защит",
            "auditorium" => "Аудитория",
            "protocol_id" => "Протокол заседания кафедры",
            "requirements_for_registration" => "Требования к оформлению",
            "issuing_themes" => "Выдача тем",
            "progress" => "Ход работы",
            "results" => "Результаты"
        );
    }
    
}
