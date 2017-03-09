<?php
/**
 * Учебная нагрузка
 */
class CStudyLoad extends CActiveModel {
    protected $_table = TABLE_IND_PLAN_PLANNED;
    protected $_direction = null;
    protected $_studyLevel = null;
    protected $_studyLoadType = null;
    protected $_group = null;
    protected $_discipline = null;
    protected $_lecturer = null;
    
    protected function relations() {
    	return array(
    		"direction" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_direction",
    			"storageField" => "spec_id",
    			"managerClass" => "CTaxonomyManager",
    			"managerGetObject" => "getSpeciality"
    		),
    		"studyLevel" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_studyLevel",
    			"storageField" => "level_id",
    			"managerClass" => "CBaseManager",
    			"managerGetObject" => "getStudyLevel"
    		),
    		"studyLoadType" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_studyLoadType",
    			"storageField" => "hours_kind_type",
    			"managerClass" => "CBaseManager",
    			"managerGetObject" => "getStudyLoadType"
    		),
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
    			"storageField" => "subject_id",
    			"managerClass" => "CTaxonomyManager",
    			"managerGetObject" => "getDiscipline"
    		),
    		"lecturer" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_lecturer",
    			"storageField" => "kadri_id",
    			"managerClass" => "CStaffManager",
    			"managerGetObject" => "getPerson"
    		),
            "study_groups" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_study_groups",
                "joinTable" => TABLE_IND_PLAN_PLANNED_STUDY_GROUPS,
                "leftCondition" => "hours_kind_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "group_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudentGroup"
            )
    	);
    }
    public function attributeLabels() {
    	return array(
    		"kadri_id" => "ФИО преподавателя",
    		"year_id" => "Учебный год",
    		"part_id" => "Семестр",
    		"subject_id" => "Дисциплина",
    		"spec_id" => "Специальность",
    		"level_id" => "Курс",
    		"group_id" => "Учебная группа",
    		"hours_kind_type" => "Тип нагрузки",
    		"groups_cnt" => "Число групп",
    		"stud_cnt" => "Число студентов",
    		"comment" => "Комментарий",
    		"lects" => "лекции",
    		"practs" => "практики",
    		"labor" => "лаб. занятия",
    		"rgr" => "РГР",
    		"ksr" => "КСР",
    		"recenz" => "реценз. контр. работ",
    		"kurs_proj" => "курс. проекты",
    		"consult" => "консультация",
    		"test" => "зачеты",
    		"exams" => "экзамены",
    		"study_pract" => "уч. практики",
    		"work_pract" => "произ. практика",
    		"consult_dipl" => "консул. дип. проекта",
    		"gek" => "ГЭК",
    		"aspirants" => "занятия с асп.",
    		"aspir_manage" => "рук-во асп.",
    		"duty" => "посещение зан.",
    		"on_filial" => "Надбавка за филиалы",
    		"sum" => "сумма основной нагрузки",
    		"sum_add" => "сумма доп. нагрузки",
    		"lects_add" => "лекции доп.",
    		"practs_add" => "практики доп.",
    		"labor_add" => "лаб. занятия доп.",
    		"rgr_add" => "РГР доп.",
    		"ksr_add" => "КСР доп.",
    		"recenz_add" => "реценз. контр. работ доп.",
    		"kurs_proj_add" => "курс. проекты доп.",
    		"consult_add" => "консультация доп.",
    		"test_add" => "зачеты доп.",
    		"exams_add" => "экзамены доп.",
    		"study_pract_add" => "уч. практики доп.",
    		"work_pract_add" => "произ.практика доп.",
    		"consult_dipl_add" => "консул. дип. проекта доп.",
    		"gek_add" => "ГЭК доп.",
    		"aspirants_add" => "занятия с асп. доп.",
    		"aspir_manage_add" => "рук-во асп. доп.",
    		"duty_add" => "посещение зан. доп.",
    		"stud_cnt_add" => "число коммерческих студентов",
    		"study_groups" => "Студенческие группы"
    	);
    }
}