<?php
/**
 * Класс для страницы преподавателей
 */
class CLecturer extends CActiveModel{
    protected $_table = TABLE_USERS;
    private $_person = null;
    private $_biography = null;
    private $_diploms = null;
    private $_diplomsCurrentYear = null;
    private $_diplomsOld = null;
    private $_documents = null;
    private $_news = null;
    private $_newsCurrentYear = null;
    private $_newsOld = null;
    private $_times = null;
    private $_pages = null;
    private $_subjects = null;
    private $_aspirCurrent = null;
    private $_aspirOld = null;
    private $_questions = null;
    private $_groups = null;
    
    public function attributeLabels() {
    	return array(
    			"FIO" => "ФИО",
    			"FIO_short" => "ФИО (краткое)",
    			"kadri_id" => "Сотрудник кафедры",
    			"comment" => "Комментарий"
    	);
    }
    
    public function relations() {
    	return array(
    			"biography" => array(
    					"relationPower" => RELATION_HAS_MANY,
    					"storageProperty" => "_biography",
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
    			"news" => array(
    					"relationPower" => RELATION_HAS_MANY,
    					"storageProperty" => "_news",
    					"storageTable" => TABLE_NEWS,
    					"storageCondition" => "user_id_insert = ".$this->id,
    					"managerClass" => "CNewsManager",
    					"managerGetObject" => "getNewsItem"
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
    					"storageCondition" => 'status=3 and answer_text is not null and answer_text!="" and user_id="'.$this->id.'"',
    					"managerClass" => "CQuestionManager",
    					"managerGetObject" => "getQuestion",
    					"managerOrder" => "`datetime_quest` desc"
    			)
    	);
    }
    /**
     * Сотрудник, с которым связан преподаватель
     *
     * @return CPerson
     */
    public function getPerson() {
    	if (is_null($this->_person)) {
    		if ($this->getRecord()->getItemValue("kadri_id") != 0) {
    			$person = CStaffManager::getPersonById($this->getRecord()->getItemValue("kadri_id"));
    			if (!is_null($person)) {
    				$this->_person = $person;
    			}
    		}
    	}
    	return $this->_person;
    }
    /**
     * Биография
     */
    public function getBiography() {
    	$result = new CArrayList();
    	foreach ($this->biography->getItems() as $biography) {
    		$result->add($biography->getId(), $biography);
    	}
    	return $result;
    }
    /**
     * Дипломники
     */
    public function getDiploms() {
    	if (is_null($this->_diploms)) {
    		$this->_diploms = new CArrayList();
    		$query = new CQuery();
	    	$query->select("diplom.*")
		    	->from(TABLE_DIPLOMS." as diplom")
		    	->leftJoin(TABLE_PERSON." as person", "diplom.kadri_id=person.id")
		    	->leftJoin(TABLE_USERS." as users", "users.kadri_id=person.id")
		    	->condition("users.id=".$this->id." and users.kadri_id>0");
    		foreach ($query->execute()->getItems() as $item) {
    			$diplom = new CDiplom(new CActiveRecord($item));
    			$this->_diploms->add($diplom->getId(), $diplom);
    		}
    	}
    	return $this->_diploms;
    }
    /**
     * Дипломники текущего учебного года
     */
    public function getDiplomsCurrentYear() {
    	if (is_null($this->_diplomsCurrentYear)) {
    		$this->_diplomsCurrentYear = new CArrayList();
    		$query = new CQuery();
    		$query->select("diploms.*, pp.name as pract_place, students.fio as student_fio, study_groups.name as group_name")
		    	->from(TABLE_DIPLOMS." as diploms")
		    	->leftJoin(TABLE_STUDENTS." as students", "diploms.student_id=students.id")
		    	->leftJoin(TABLE_PRACTICE_PLACES." as pp", "pp.id=diploms.pract_place_id")
		    	->leftJoin(TABLE_PERSON." as kadri", "diploms.kadri_id=kadri.id")
		    	->leftJoin(TABLE_STUDENT_GROUPS." as study_groups", "study_groups.id=students.group_id")
		    	->leftJoin(TABLE_USERS." as users", "users.kadri_id=kadri.id")
		    	->condition('users.kadri_id>0 and users.id="'.$this->id.'" and (diploms.date_act>="'.CUtils::getCurrentYear()->date_start.'" or diploms.date_act is NULL)')
		    	->order("students.fio asc");
    		foreach ($query->execute()->getItems() as $item) {
    			$diplom = new CDiplom(new CActiveRecord($item));
    			$this->_diplomsCurrentYear->add($diplom->getId(), $diplom);
    		}
    	}
    	return $this->_diplomsCurrentYear;
    }
    /**
     * Дипломники предыдущих учебных лет
     */
    public function getDiplomsOld() {
    	if (is_null($this->_diplomsOld)) {
    		$this->_diplomsOld = new CArrayList();
    		$query = new CQuery();
    		$query->select("diploms.*, pp.name as pract_place, students.fio as student_fio, study_groups.name as group_name")
	    		->from(TABLE_DIPLOMS." as diploms")
	    		->leftJoin(TABLE_STUDENTS." as students", "diploms.student_id=students.id")
	    		->leftJoin(TABLE_PRACTICE_PLACES." as pp", "pp.id=diploms.pract_place_id")
	    		->leftJoin(TABLE_PERSON." as kadri", "diploms.kadri_id=kadri.id")
	    		->leftJoin(TABLE_STUDENT_GROUPS." as study_groups", "study_groups.id=students.group_id")
	    		->leftJoin(TABLE_USERS." as users", "users.kadri_id=kadri.id")
	    		->condition('users.kadri_id>0 and users.id="'.$this->id.'" and (diploms.date_act<"'.CUtils::getCurrentYear()->date_start.'" or diploms.date_act is NULL)')
	    		->order("students.fio asc");
    		foreach ($query->execute()->getItems() as $item) {
    			$diplom = new CDiplom(new CActiveRecord($item));
    			$this->_diplomsOld->add($diplom->getId(), $diplom);
    		}
    	}
    	return $this->_diplomsOld;
    }
    /**
     * Предметы
     */
    public function getDoc() {
    	$result = new CArrayList();
    	foreach ($this->documents->getItems() as $document) {
    		$result->add($document->getId(), $document);
    	}
    	return $result;
    }
    /**
     * Объявления
     */
    public function getNews() {
    	$result = new CArrayList();
    	foreach ($this->news->getItems() as $new) {
    		$result->add($new->getId(), $new);
    	}
    	return $result;
    }
    /**
     * Объявления текущего учебного года
     */
    public function getNewsCurrentYear() {
    	$result = new CArrayList();
    	foreach ($this->newsCurrentYear->getItems() as $new) {
    		$result->add($new->getId(), $new);
    	}
    	return $result;
    }
    /**
     * Объявления прошлых учебных лет
     */
    public function getNewsOld() {
    	$result = new CArrayList();
    	foreach ($this->newsOld->getItems() as $new) {
    		$result->add($new->getId(), $new);
    	}
    	return $result;
    }
    /**
     * Расписание
     */
    public function getTime() {
    	if (is_null($this->_times)) {
    		$this->_times = new CArrayList();
    		$query = new CQuery();
    		$query->select("time.*")
		    	->from(TABLE_SCHEDULE." as time")
		    	->condition("time.id=".$this->id." and time.year=".CUtils::getCurrentYear()->getId()." and time.month=".CUtils::getCurrentYearPart()->getId());
    		foreach ($query->execute()->getItems() as $item) {
    			$time = new CSchedule(new CActiveRecord($item));
    			$this->_times->add($time->getId(), $time);
    		}
    	}
    	return $this->_times;
    }
    /**
     * Веб-страницы на портале
     */
    public function getPage() {
    	$result = new CArrayList();
    	foreach ($this->pages->getItems() as $page) {
    		$result->add($page->getId(), $page);
    	}
    	return $result;
    }
    /**
     * Список пособий на портале
     */
    public function getSubjects() {
    	if (is_null($this->_subjects)) {
    		$this->_subjects = new CArrayList();
    		$query = new CQuery();
    		$query->select("subj.*, doc.nameFolder as nameFolder, (select count(*) from files f where f.nameFolder = doc.nameFolder) as f_cnt")
		    	->from(TABLE_DISCIPLINES." as subj")
		    	->leftJoin(TABLE_LIBRARY_DOCUMENTS." as doc", "subj.id = doc.subj_id")
		    	->condition("doc.user_id=".$this->id);
    		foreach ($query->execute()->getItems() as $item) {
    			$subject = new CDiscipline(new CActiveRecord($item));
    			$this->_subjects->add($subject->getId(), $subject);
    		}
    	}
    	return $this->_subjects;
    }
    /**
     * Подготовка аспирантов, текущие
     */
    public function getAspirCurrent() {
    	if (is_null($this->_aspirCurrent)) {
    		$this->_aspirCurrent = new CArrayList();
    		$query = new CQuery();
    		$query->select("disser.*, kadri.fio as fio")
		    	->from(TABLE_PERSON_DISSER." as disser")
		    	->innerJoin(TABLE_PERSON." as kadri", "kadri.id=disser.kadri_id")
		    	->condition('disser.kadri_id>0 and disser.scinceMan=(select users.kadri_id from users where users.id="'.$this->id.'") and disser.god_zach>="'.date("Y").'"')
		    	->order("kadri.fio asc");
    		foreach ($query->execute()->getItems() as $item) {
    			$aspir = new CPersonPaper(new CActiveRecord($item));
    			$this->_aspirCurrent->add($aspir->getId(), $aspir);
    		}
    	}
    	return $this->_aspirCurrent;
    }
    /**
     * Подготовка аспирантов, архив
     */
    public function getAspirOld() {
    	if (is_null($this->_aspirOld)) {
    		$this->_aspirOld = new CArrayList();
    		$query = new CQuery();
    		$query->select("disser.*, kadri.fio as fio")
		    	->from(TABLE_PERSON_DISSER." as disser")
		    	->innerJoin(TABLE_PERSON." as kadri", "kadri.id=disser.kadri_id")
		    	->condition('disser.kadri_id>0 and disser.scinceMan=(select users.kadri_id from users where users.id="'.$this->id.'") and disser.scinceMan>0 and (disser.god_zach<"'.date("Y").'" or disser.god_zach is null)')
		    	->order("kadri.fio asc");
    		foreach ($query->execute()->getItems() as $item) {
    			$aspir = new CPersonPaper(new CActiveRecord($item));
    			$this->_aspirOld->add($aspir->getId(), $aspir);
    		}
    	}
    	return $this->_aspirOld;
    }
    /**
     * Вопросы и ответы на них преподавателя
     */
    public function getQuestions() {
    	$result = new CArrayList();
    	foreach ($this->questions->getItems() as $question) {
    		$result->add($question->getId(), $question);
    	}
    	return $result;
    }
    /**
     * Кураторство учебных групп
     */
    public function getGroups() {
    	if (is_null($this->_groups)) {
    		$this->_groups = new CArrayList();
    		$query = new CQuery();
	    	$query->select("sg.id, sg.name")
		    	->from(TABLE_STUDENT_GROUPS." as sg")
		    	->leftJoin(TABLE_USERS." as users", "users.kadri_id=sg.curator_id")
		    	->condition("users.kadri_id>0 and users.id=".$this->id);
    		foreach ($query->execute()->getItems() as $item) {
    			$group = new CStudentGroup(new CActiveRecord($item));
    			$this->_groups->add($group->getId(), $group);
    		}
    	}
    	return $this->_groups;
    }
}