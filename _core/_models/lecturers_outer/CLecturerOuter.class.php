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
    
    public function attributeLabels() {
    	return array(
    			"fio" => "ФИО",
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
    			)
    	);
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
}