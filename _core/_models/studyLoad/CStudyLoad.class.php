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
                "targetClass" => "CStudentGroupVersionControllable"
            ),
    		"createdBy" => array(
    			"relationPower" => RELATION_HAS_ONE,
    			"storageProperty" => "_createdBy",
    			"storageField" => "_created_by",
    			"managerClass" => "CStaffManager",
    			"managerGetObject" => "getPerson"
            ),
            "works" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_works",
                "storageTable" => TABLE_WORKLOAD_WORKS,
                "storageCondition" => "workload_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CStudyLoadWork"
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
    		"groups_count" => "Число&nbsp;групп", // &nbsp; - для написания без переноса в заголовке таблицы
    		"students_count" => "Число&nbsp;студентов",
    		"comment" => "Комментарий",
    		"on_filial" => "Надбавка&nbsp;за филиалы",
    		"students_count_add" => "число коммерческих студентов",
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
     * Нагрузка по виду (бюджет/контракт)
     * и типу (лекция, практика, ргр)
     *
     * @param $kind
     * @param $type
     * @return int
     */
    public function getLoadByKindAndType($kind, $type) {
    	$result = 0;
    	foreach ($this->getWorksByType($type)->getItems() as $work) {
    		if ($work->kind_id == $kind) {
    			$result += $work->workload;
    		}
    	}
    	return $result;
    }
    
    /**
     * Нагрузка по типу (лекция, практика, ргр)
     *
     * @param $type
     * @return int
     */
    public function getLoadByType($type) {
    	$result = 0;
    	foreach ($this->getWorksByType($type)->getItems() as $work) {
    		$result += $work->workload;
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
     * Сумма часов по видам работ учебной нагрузки для выездов в филиалы
     * 
     * @return float
     */
    public function getWorkWithFilialsTotals() {
    	$value = 0;
    	if ($this->on_filial) {
    		foreach ($this->works->getItems() as $work) {
    			$value += $work->workload;
    		}
    		$value = $value*0.5;
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