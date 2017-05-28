<?php
/**
 * Учебная нагрузка
 */
class CStudyLoad extends CActiveModel implements IVersionControl {
    protected $_table = TABLE_WORKLOAD;
    protected $_direction = null;
    protected $_studyLevel = null;
    protected $_studyLoadType = null;
    protected $_discipline = null;
    protected $_lecturer = null;
    protected $_createdBy = null;
    protected $_works = null;
    private $_loadTable = null;
    
    protected function relations() {
    	return array(
    		"direction" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_direction",
    			"storageField" => "speciality_id",
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
    			"storageField" => "load_type_id",
    			"managerClass" => "CBaseManager",
    			"managerGetObject" => "getStudyLoadType"
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
    			"storageField" => "person_id",
    			"managerClass" => "CStaffManager",
    			"managerGetObject" => "getPerson"
    		),
            "study_groups" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_study_groups",
                "joinTable" => TABLE_WORKLOAD_STUDY_GROUPS,
                "leftCondition" => "workload_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "group_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getStudentGroup"
            ),
    		"createdBy" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_createdBy",
    			"storageField" => "_created_by",
    			"managerClass" => "CStaffManager",
    			"managerGetObject" => "getPerson"
    		),
            "works" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_works",
                "relationFunction" => "getWorks",
    		)
    	);
    }
    public function attributeLabels() {
    	return array(
    		"person_id" => "ФИО преподавателя",
    		"year_id" => "Учебный год",
    		"year_part_id" => "Семестр",
    		"discipline_id" => "Дисциплина",
    		"speciality_id" => "Специальность",
    		"level_id" => "Курс",
    		"group_id" => "Учебная группа",
    		"load_type_id" => "Тип нагрузки",
    		"groups_count" => "Число групп",
    		"students_count" => "Число студентов",
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
    protected function validationRules() {
    	return array(
    		"selected" => array(
    			"person_id",
    			"year_id",
    			"year_part_id",
    			"discipline_id",
    			"speciality_id",
    			"level_id",
    			"load_type_id"
    		)
    	);
    }
    
    /**
     * Виды работ учебной нагрузки по типу нагрузки (бюджет/контракт)
     *
     * @param $kind
     * @return CArrayList
     */
    public function getWorksByKind($kind) {
    	$result = new CArrayList();
    	foreach ($this->works->getItems() as $work) {
    		if ($work->kind_id == $kind) {
    			$result->add($work->getId(), $work);
    		}
    	}
    	return $result;
    }
    
    /**
     * Виды работ учебной нагрузки
     * 
     * @return CArrayList
     */
    public function getWorks() {
    	if (is_null($this->_works)) {
    		$this->_works = new CArrayList();
    		if (!is_null($this->getId())) {
    			foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD_WORKS, "workload_id=".$this->getId())->getItems() as $ar) {
    				$work = new CStudyLoadWork($ar);
    				$this->_works->add($work->getId(), $work);
    			}
    		}
    	}
    	return $this->_works;
    }
    
    /**
     * Виды работ учебной нагрузки по типу
     * 
     * @param $type
     * @return CArrayList
     */
    public function getWorksByType($type) {
    	$result = new CArrayList();
    	foreach ($this->works->getItems() as $work) {
    		if ($work->type_id == $type) {
    			$result->add($work->getId(), $work);
    		}
    	}
    	return $result;
    }
    
    /**
     * Число часов по видам работ учебной нагрузки по типу
     *
     * @param $type
     * @return CArrayList
     */
    public function getWorksValueByType($type) {
    	$value = 0;
    	foreach ($this->works->getItems() as $work) {
    		if ($work->type_id == $type) {
    			$value += $work->workload;
    		}
    	}
    	return $value;
    }
    
    /**
     * Сумма часов по видам работ учебной нагрузки
     *
     * @param $type
     * @return CArrayList
     */
    public function getSumWorksValue() {
    	$value = 0;
    	foreach ($this->works->getItems() as $work) {
    		$value += $work->workload;
    	}
    	return $value;
    }
    
    /**
     * Таблица учебной нагрузки отдельным классом
     *
     * @return CStudyLoadTable
     */
    public function getStudyLoadTable() {
    	if (is_null($this->_loadTable)) {
    		$this->_loadTable = new CStudyLoadTable($this);
    	}
    	return $this->_loadTable;
    }
    
}