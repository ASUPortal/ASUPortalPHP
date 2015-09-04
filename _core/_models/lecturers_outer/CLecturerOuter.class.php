<?php
/**
 * Класс для страницы преподавателей на внешнем портале
 */
class CLecturerOuter extends CPerson {
    protected $_table = TABLE_USERS;
    private $_biographies = null;
    private $_documents = null;
    private $_newsCurrentYear = null;
    private $_newsOld = null;
    private $_schedules = null;
    private $_pages = null;
    private $_manuals = null;
    private $_questions = null;
    private $_graduatesCurrentYear = null;
    private $_graduatesOld = null;
    private $_aspirantsCurrent = null;
    private $_aspirantsOld = null;
    private $_supervisedGroups = null;
    
    public function attributeLabels() {
    	return array(
    			"FIO" => "ФИО",
    			"FIO_short" => "ФИО (краткое)",
    			"comment" => "Комментарий"
    	);
    }
    
    public function relations() {
    	return array(
    			"biographies" => array(
    					"relationPower" => RELATION_HAS_MANY,
    					"storageProperty" => "_biographies",
    					"storageTable" => TABLE_BIOGRAPHY,
    					"storageCondition" => "user_id = ".$this->id,
    					"managerClass" => "CBiographyManager",
    					"managerGetObject" => "getBiography"
    			),
    			"documents" => array(
    					"relationPower" => RELATION_HAS_MANY,
    					"storageProperty" => "_documents",
    					"storageTable" => TABLE_LIBRARY_DOCUMENTS,
    					"storageCondition" => "user_id = ".$this->id,
    					"managerClass" => "CLibraryManager",
    					"managerGetObject" => "getDocument"
    			),
    			"newsCurrentYear" => array(
    					"relationPower" => RELATION_HAS_MANY,
    					"storageProperty" => "_newsCurrentYear",
    					"storageTable" => TABLE_NEWS,
    					"storageCondition" => 'user_id_insert = '.$this->id.' and date_time>="'.CUtils::getCurrentYear()->date_start.'"',
    					"managerClass" => "CNewsManager",
    					"managerGetObject" => "getNewsItem",
						"managerOrder" => "`date_time` desc"
    			),
    			"newsOld" => array(
    					"relationPower" => RELATION_HAS_MANY,
    					"storageProperty" => "_newsOld",
    					"storageTable" => TABLE_NEWS,
    					"storageCondition" => 'user_id_insert = '.$this->id.' and date_time<"'.CUtils::getCurrentYear()->date_start.'"',
    					"managerClass" => "CNewsManager",
    					"managerGetObject" => "getNewsItem",
    					"managerOrder" => "`date_time` desc"
    			),
    			"pages" => array(
    					"relationPower" => RELATION_HAS_MANY,
    					"storageProperty" => "_pages",
    					"storageTable" => TABLE_PAGES,
    					"storageCondition" => "user_id_insert = ".$this->id." and type_id<>1",
    					"managerClass" => "CPageManager",
    					"managerGetObject" => "getPage"
    			),
    			"questions" => array(
    					"relationPower" => RELATION_HAS_MANY,
    					"storageProperty" => "_questions",
    					"storageTable" => TABLE_QUESTION_TO_USERS,
    					"storageCondition" => 'status!=5 and answer_text is not null and answer_text!="" and user_id="'.$this->id.'"',
    					"managerClass" => "CQuestionManager",
    					"managerGetObject" => "getQuestion",
    					"managerOrder" => "`datetime_quest` desc"
    			),
    			"supervisedGroups" => array(
    					"relationPower" => RELATION_HAS_MANY,
    					"storageProperty" => "_supervisedGroups",
    					"storageTable" => TABLE_STUDENT_GROUPS,
    					"storageCondition" => "curator_id = -1",
    					"managerClass" => "CStaffManager",
    					"managerGetObject" => "getStudentGroup"
    			)
    	);
    }
       
    /**
     * Пользователь
     *
     * @return CUser
     */
    public function getUser() {
    	return $this;
    }
    
    /**
     * Расписание
     * 
     * @return CArrayList
     */
    public function getSchedule() {
    	if (is_null($this->_schedules)) {
    		$this->_schedules = new CArrayList();
    		$query = new CQuery();
    		$query->select("schedule.*")
	    		->from(TABLE_SCHEDULE." as schedule")
	    		->condition("schedule.id=".$this->getId()." and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId());
    		foreach ($query->execute()->getItems() as $item) {
    			$schedule = new CSchedule(new CActiveRecord($item));
    			$this->_schedules->add($schedule->getId(), $schedule);
    		}
    	}
    	return $this->_schedules;
    }

    /**
     * Список пособий на портале
     * 
     * @return CArrayList
     */
    public function getManuals() {
    	if (is_null($this->_manuals)) {
    		$this->_manuals = new CArrayList();
    		$query = new CQuery();
    		$query->select("subj.*, doc.nameFolder as nameFolder, (select count(*) from files f where f.nameFolder = doc.nameFolder) as f_cnt")
	    		->from(TABLE_DISCIPLINES." as subj")
	    		->leftJoin(TABLE_LIBRARY_DOCUMENTS." as doc", "subj.id = doc.subj_id")
	    		->condition("doc.user_id=".$this->id);
    		foreach ($query->execute()->getItems() as $item) {
    			$subject = new CDiscipline(new CActiveRecord($item));
    			$this->_manuals->add($subject->getId(), $subject);
    		}
    	}
    	return $this->_manuals;
    }
    
    /**
     * Дипломники текущего учебного года
     *
     * @return CArrayList
     */
    public function getGraduatesCurrentYear() {
    	$this->_graduatesCurrentYear = new CArrayList();
    	return $this->_graduatesCurrentYear;
    }
    
    /**
     * Дипломники предыдущих учебных лет
     *
     * @return CArrayList
     */
    public function getGraduatesOld() {
    	$this->_graduatesOld = new CArrayList();
    	return $this->_graduatesOld;
    }
    
    /**
     * Подготовка аспирантов, текущие
     *
     * @return CArrayList
     */
    public function getAspirantsCurrent() {
    	$this->_aspirantsCurrent = new CArrayList();
    	return $this->_aspirantsCurrent;
    }
    
    /**
     * Подготовка аспирантов, архив
     *
     * @return CArrayList
     */
    public function getAspirantsOld() {
    	$this->_aspirantsOld = new CArrayList();
    	return $this->_aspirantsOld;
    }
}