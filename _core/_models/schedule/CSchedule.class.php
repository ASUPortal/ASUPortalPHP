<?php
/**
 * Расписание занятий
 */
class CSchedule extends CActiveModel{
    protected $_table = TABLE_SCHEDULE;
    protected $_lecturer = null;
    protected $_year = null;
    protected $_part = null;
    protected $_discipline = null;
    protected $_kindWork = null;
    protected $_studentGroup = null;
    
    protected function relations() {
    	return array(
    		"lecturer" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_lecturer",
    			"storageField" => "user_id",
    			"managerClass" => "CStaffManager",
    			"managerGetObject" => "getUser"
    		),
    		"yearName" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_year",
    			"storageField" => "year",
    			"managerClass" => "CTaxonomyManager",
    			"managerGetObject" => "getYear"
    		),
    		"part" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_part",
    			"storageField" => "month",
    			"managerClass" => "CTaxonomyManager",
    			"managerGetObject" => "getYearPart"
    		),
    		"discipline" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_discipline",
    			"storageField" => "study",
    			"managerClass" => "CBaseManager",
    			"managerGetObject" => "getDiscipline"
    		),
    		"kindWork" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_kindWork",
    			"storageField" => "kind",
    			"managerClass" => "CBaseManager",
    			"managerGetObject" => "getScheduleKindWork"
    		),
    		"studentGroup" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_studentGroup",
    			"storageField" => "grup",
    			"managerClass" => "CStaffManager",
    			"managerGetObject" => "getStudentGroup"
    		)
    	);
    }
    public function attributeLabels() {
    	return array(
    		"user_id" => "ФИО преподавателя",
    		"year" => "Учебный год",
    		"month" => "Семестр",
    		"day" => "День недели",
    		"number" => "Время занятия",
    		"kind" => "Вид занятия",
    		"length" => "Период (нед.)",
    		"study" => "Предмет",
    		"grup" => "Группа (курс)",
    		"place" => "Аудитория"
    	);
    }
    public function validationRules() {
    	return array(
    		"selected" => array(
    			"user_id",
    			"year",
    			"month",
    			"day",
    			"number",
    			"kind",
    			"study",
    			"grup"
    		),
    		"required" => array(
    			"length",
    			"place"
    		)
    	);
    }
}