<?php
/**
 * Учебное расписание
 */
class CPublicScheduleController extends CBaseController {
	public $allowedAnonymous = array(
			"index",
			"lecturers",
			"viewLecturers",
			"viewGroups",
			"printView",
			"allSchedule",
			"printAll",
			"showSchedules",
			"search"
	);
    public function __construct() {
        if (!CSession::isAuth()) {
        	$action = CRequest::getString("action");
        	if ($action == "") {
        		$action = "index";
        	}
        	if (!in_array($action, $this->allowedAnonymous)) {
        		$this->redirectNoAccess();
        	}
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Расписание");

        parent::__construct();
    }
    /**
     * Контроллер открытого доступа?
     *
     * @return boolean
     */
    protected function isPublic() {
    	return true;
    }
    protected function getLink() {
    	if ($this->isPublic()) {
    		return "public.php";
    	} else {
    		return "index.php";
    	}
    }
    public function actionIndex() {
    	$set = new CRecordSet();
    	$query = new CQuery();
    	$query->select("distinct st_group.*")
	    	->from(TABLE_STUDENT_GROUPS." as st_group")
	    	->innerJoin(TABLE_SCHEDULE." as schedule", "st_group.id = schedule.grup")
	    	->condition("st_group.year_id =".CUtils::getCurrentYear()->id." and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId())
	    	->order("st_group.name asc");
    	$set->setQuery($query);
    	$queryLetter = new CQuery();
    	$queryLetter->select("st_group.*, UPPER(left(st_group.name,1)) as name, count(*) as cnt")
	    	->from(TABLE_STUDENT_GROUPS." as st_group")
	    	->innerJoin(TABLE_SCHEDULE." as schedule", "st_group.id = schedule.grup")
	    	->condition("st_group.year_id = ".CUtils::getCurrentYear()->id." and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId())
	    	->group(1)
	    	->order("st_group.name asc");
    	$resRus = array();
    	foreach ($queryLetter->execute()->getItems() as $ar) {
    		$res = new CStudentGroup(new CActiveRecord($ar));
    		$resRus[$res->id] = $res->name;
    	}
    	$resRusLetters = array();
    	$resRusLetters = array_count_values($resRus);
    	$firstLet = array(1);
    	foreach ($resRusLetters as $key=>$value) {
    		$firstLet[] = $key;
    	}
    	$letter = $firstLet[CRequest::getInt("getsub")];
    	$letterId = -1;
    	if (CRequest::getInt("getsub") > 0 and is_null(CRequest::getFilter("id"))) {
    		if (CRequest::getInt("getsub")>0) {
    			$letterId = CRequest::getInt("getsub");
    		}
    		$query->condition('st_group.name like "'.$letter.'%" and st_group.year_id ='.CUtils::getCurrentYear()->id);
    	}
    	$groups = new CArrayList();
    	foreach($set->getPaginated()->getItems() as $item) {
    		$group = new CStudentGroup($item);
    		$groups->add($group->getId(), $group);
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Расписание по преподавателям",
    			"link" => WEB_ROOT."_modules/_schedule/public.php?action=lecturers",
    			"icon" => "apps/office-calendar.png"
    		),
    		array(
    			"title" => "Общее расписание",
    			"link" => WEB_ROOT."_modules/_schedule/public.php?action=allSchedule",
    			"icon" => "apps/office-calendar.png"
    		)
    	));
    	$this->setData("resRusLetters", $resRusLetters);
    	$this->setData("letterId", $letterId);
    	$this->setData("firstLet", $firstLet);
    	$this->setData("groups", $groups);
    	$this->setData("paginator", $set->getPaginator());
    	$this->renderView("__public/_schedule/index.tpl");
    }
    public function actionLecturers() {
    	if (CSettingsManager::getSettingValue("hide_personal_data")) {
    		$set = new CRecordSet();
    		$query = new CQuery();
    		$query->select("distinct users.*")
	    		->from(TABLE_USERS." as users")
	    		->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
	    		->innerJoin(TABLE_SCHEDULE." as schedule", "users.id = schedule.user_id")
	    		->condition("userGroup.group_id=1 and users.FIO not like '%/_%'ESCAPE'/' and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId())
	    		->order("users.FIO asc");
    		$queryLetter = new CQuery();
    		$queryLetter->select("users.*, UPPER(left(users.FIO,1)) as name, count(*) as cnt")
	    		->from(TABLE_USERS." as users")
	    		->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
	    		->innerJoin(TABLE_SCHEDULE." as schedule", "users.id = schedule.user_id")
	    		->condition("userGroup.group_id=1 and users.FIO not like '%/_%'ESCAPE'/' and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId())
	    		->group(1)
	    		->order("users.FIO asc");
    		$resRus = array();
    		foreach ($queryLetter->execute()->getItems() as $ar) {
    			$res = new CLecturerOuter(new CActiveRecord($ar));
    			$resRus[$res->id] = $res->name;
    		}
    		$resRusLetters = array();
    		$resRusLetters = array_count_values($resRus);
    		$firstLet = array(1);
    		foreach ($resRusLetters as $key=>$value) {
    			$firstLet[] = $key;
    		}
    		$letter = $firstLet[CRequest::getInt("getsub")];
    		$letterId = -1;
    		if (CRequest::getInt("getsub") > 0 and is_null(CRequest::getFilter("user.id"))) {
    			if (CRequest::getInt("getsub") > 0) {
    				$letterId = CRequest::getInt("getsub");
    			}
    			$query->condition('users.FIO like "'.$letter.'%" and userGroup.group_id=1');
    		}
    		$lects = new CArrayList();
    		$set->setQuery($query);
    		foreach ($set->getPaginated()->getItems() as $ar) {
    			$lect = new CLecturerOuter($ar);
    			$lects->add($lect->getId(), $lect);
    		}
    	} else {
    		$set = new CRecordSet();
    		$query = new CQuery();
    		$query->select("distinct person.*")
	    		->from(TABLE_PERSON." as person")
	    		->innerJoin(TABLE_USERS." as users", "users.kadri_id=person.id")
	    		->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
	    		->innerJoin(TABLE_SCHEDULE." as schedule", "users.id = schedule.user_id")
	    		->condition("userGroup.group_id=1 and person.fio not like '%/_%'ESCAPE'/' and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId())
	    		->order("person.fio asc");
    		$queryLetter = new CQuery();
    		$queryLetter->select("person.*, UPPER(left(person.fio,1)) as name, count(*) as cnt")
	    		->from(TABLE_PERSON." as person")
	    		->innerJoin(TABLE_USERS." as users", "users.kadri_id=person.id")
	    		->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
	    		->innerJoin(TABLE_SCHEDULE." as schedule", "users.id = schedule.user_id")
	    		->condition("userGroup.group_id=1 and person.fio not like '%/_%'ESCAPE'/' and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId())
	    		->group(1)
	    		->order("person.fio asc");
    		$resRus = array();
    		foreach ($queryLetter->execute()->getItems() as $ar) {
    			$res = new CPerson(new CActiveRecord($ar));
    			$resRus[$res->id] = $res->name;
    		}
    		$resRusLetters = array();
    		$resRusLetters = array_count_values($resRus);
    		$firstLet = array(1);
    		foreach ($resRusLetters as $key=>$value) {
    			$firstLet[] = $key;
    		}
    		$letter = $firstLet[CRequest::getInt("getsub")];
    		$letterId = -1;
    		if (CRequest::getInt("getsub") > 0 and is_null(CRequest::getFilter("person.id"))) {
    			if (CRequest::getInt("getsub") > 0) {
    				$letterId = CRequest::getInt("getsub");
    			}
    			$query->condition('person.fio like "'.$letter.'%" and userGroup.group_id=1 and schedule.year='.CUtils::getCurrentYear()->getId().' and schedule.month='.CUtils::getCurrentYearPart()->getId());
    		}
    		$lects = new CArrayList();
    		$set->setQuery($query);
    		foreach ($set->getPaginated()->getItems() as $ar) {
    			$lect = new CPerson($ar);
    			$lects->add($lect->getId(), $lect);
    		}
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Расписание по группе",
    			"link" => WEB_ROOT."_modules/_schedule/public.php",
    			"icon" => "apps/office-calendar.png"
    		),
    		array(
    			"title" => "Общее расписание",
    			"link" => WEB_ROOT."_modules/_schedule/public.php?action=allSchedule",
    			"icon" => "apps/office-calendar.png"
    		)
    	));
    	$this->setData("resRusLetters", $resRusLetters);
    	$this->setData("letterId", $letterId);
    	$this->setData("firstLet", $firstLet);
    	$this->setData("paginator", $set->getPaginator());
    	$this->setData("lects", $lects);
    	$this->renderView("__public/_schedule/lecturers.tpl");
    }
    public function actionViewLecturers() {
    	$this->setData("isPublic", $this->isPublic());
    	
    	$selectedName = null;
    	if (CRequest::getInt("year") != 0) {
    		$year = CTaxonomyManager::getYear(CRequest::getInt("year"));
    	} else {
    		$year = CUtils::getCurrentYear();
    	}
    	if (CRequest::getInt("yearPart") != 0) {
    		$yearPart = CTaxonomyManager::getYearPart(CRequest::getInt("yearPart"));
    	} else {
    		$yearPart = CUtils::getCurrentYearPart();
    	}
    	$lecturers = array();
    	foreach (CScheduleService::getLecturersWithSchedulesByYearAndPart($year, $yearPart)->getItems() as $lecturer) {
    		$lecturers[$lecturer->getId()] = $lecturer->getName();
    	}
    	$user = CStaffManager::getUser(CRequest::getInt("id"));
    	$schedules = new CArrayList();
    	if (!is_null($user)) {
    		$schedules = CScheduleService::getScheduleUserByYearAndPart($user, $year, $yearPart);
    		$selectedName = $user->getId();
    		if (empty($lecturers)) {
    			$lecturers[$user->getId()] = $user->getName();
    		}
    	}
    	if ($this->isPublic()) {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "К списку преподавателей",
    				"link" => WEB_ROOT."_modules/_schedule/public.php?action=lecturers",
    				"icon" => "actions/edit-undo.png"
    			)
    		));
    	} else {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Назад",
    				"link" => WEB_ROOT."_modules/_schedule/index.php",
    				"icon" => "actions/edit-undo.png"
    			)
    		));
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Выгрузить в Excel",
    			"link" => UrlBuilder::newBuilder($this->getLink())
    				->addParameter("action", "printView")
    				->addParameter("name", CRequest::getInt("id"))
    				->addParameter("nameInCell", "studentGroup")
    				->addParameter("year", $year->getId())
    				->addParameter("yearPart", $yearPart->getId())
    				->addParameter("invert", CRequest::getInt("invert"))
    				->build(),
    			"icon" => "actions/document-print-preview.png"
    		),
    		array(
    			"title" => "Общее расписание",
    			"link" => UrlBuilder::newBuilder($this->getLink())
    				->addParameter("action", "allSchedule")
    				->build(),
    			"icon" => "apps/office-calendar.png"
    		)
    	));
    	if (CRequest::getInt("invert") == 1) {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Перевернуть",
    				"link" => UrlBuilder::newBuilder($this->getLink())
    					->addParameter("action", "viewLecturers")
    					->addParameter("id", CRequest::getInt("id"))
    					->addParameter("year", $year->getId())
    					->addParameter("yearPart", $yearPart->getId())
    					->addParameter("invert", 0)
    					->build(),
    				"icon" => "actions/go-jump.png"
    			)
    		));
    		$this->setData("invert", true);
    	} else {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Перевернуть",
    				"link" => UrlBuilder::newBuilder($this->getLink())
    					->addParameter("action", "viewLecturers")
    					->addParameter("id", CRequest::getInt("id"))
    					->addParameter("year", $year->getId())
    					->addParameter("yearPart", $yearPart->getId())
    					->addParameter("invert", 1)
    					->build(),
    				"icon" => "actions/go-jump.png"
    			)
    		));
    		$this->setData("invert", false);
    	}
    	if (!$this->isPublic()) {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Добавить",
    				"link" => UrlBuilder::newBuilder("index.php")
    					->addParameter("action", "add")
    					->addParameter("nameId", $user->getId())
    					->addParameter("year", $year->getId())
    					->addParameter("yearPart", $yearPart->getId())
    					->addParameter("redirect", CRequest::getString("action"))
    					->addParameter("nameInCell", "studentGroup")
    					->build(),
    				"icon" => "actions/list-add.png"
    			)
    		));
    	}
    	$this->setData("link", $this->getLink());
    	$this->setData("year", $year);
    	$this->setData("yearPart", $yearPart);
    	$this->setData("name", $user);
    	$this->setData("selectedName", $selectedName);
    	$this->setData("nameInCell", "studentGroup");
    	$this->setData("time", $this->getTime());
    	$this->setData("existDays", $this->getDay());
    	$this->setData("schedules", $schedules);
    	$this->setData("lecturers", $lecturers);
    	$this->renderView("__public/_schedule/view.tpl");
    }
    public function actionViewGroups() {
    	$this->setData("isPublic", $this->isPublic());
    	
    	$selectedName = null;
    	if (CRequest::getInt("year") != 0) {
    		$year = CTaxonomyManager::getYear(CRequest::getInt("year"));
    	} else {
    		$year = CUtils::getCurrentYear();
    	}
    	if (CRequest::getInt("yearPart") != 0) {
    		$yearPart = CTaxonomyManager::getYearPart(CRequest::getInt("yearPart"));
    	} else {
    		$yearPart = CUtils::getCurrentYearPart();
    	}
    	$groups = array();
    	foreach (CScheduleService::getGroupsWithSchedulesByYearAndPart($year, $yearPart)->getItems() as $studentGroup) {
    		$groups[$studentGroup->getId()] = $studentGroup->getName();
    	}
    	$group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
    	$schedules = new CArrayList();
    	if (!is_null($group)) {
    		$schedules = CScheduleService::getScheduleGroupByYearAndPart($group, $year, $yearPart);
    		$selectedName = $group->getId();
    		if (empty($groups)) {
    			$groups[$group->getId()] = $group->getName();
    		}
    	}
    	if ($this->isPublic()) {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "К списку групп",
    				"link" => WEB_ROOT."_modules/_schedule/public.php",
    				"icon" => "actions/edit-undo.png"
    			)
    		));
    	} else {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Назад",
    				"link" => WEB_ROOT."_modules/_schedule/index.php",
    				"icon" => "actions/edit-undo.png"
    			)
    		));
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Выгрузить в Excel",
    			"link" => UrlBuilder::newBuilder($this->getLink())
    				->addParameter("action", "printView")
    				->addParameter("name", CRequest::getInt("id"))
    				->addParameter("nameInCell", "lecturer")
    				->addParameter("year", $year->getId())
    				->addParameter("yearPart", $yearPart->getId())
    				->addParameter("invert", CRequest::getInt("invert"))
    				->build(),
    			"icon" => "actions/document-print-preview.png"
    		),
    		array(
    			"title" => "Общее расписание",
    			"link" => UrlBuilder::newBuilder($this->getLink())
    				->addParameter("action", "allSchedule")
    				->build(),
    			"icon" => "apps/office-calendar.png"
    		)
    	));
    	if (CRequest::getInt("invert") == 1) {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Перевернуть",
    				"link" => UrlBuilder::newBuilder($this->getLink())
    					->addParameter("action", "viewGroups")
    					->addParameter("id", CRequest::getInt("id"))
    					->addParameter("year", $year->getId())
    					->addParameter("yearPart", $yearPart->getId())
    					->addParameter("invert", 0)
    					->build(),
    				"icon" => "actions/go-jump.png"
    			)
    		));
    		$this->setData("invert", true);
    	} else {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Перевернуть",
    				"link" => UrlBuilder::newBuilder($this->getLink())
    					->addParameter("action", "viewGroups")
    					->addParameter("id", CRequest::getInt("id"))
    					->addParameter("year", $year->getId())
    					->addParameter("yearPart", $yearPart->getId())
    					->addParameter("invert", 1)
    					->build(),
    				"icon" => "actions/go-jump.png"
    			)
    		));
    		$this->setData("invert", false);
    	}
    	if (!$this->isPublic()) {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Добавить",
    				"link" => UrlBuilder::newBuilder("index.php")
    					->addParameter("action", "add")
    					->addParameter("nameId", $group->getId())
    					->addParameter("year", $year->getId())
    					->addParameter("yearPart", $yearPart->getId())
    					->addParameter("redirect", CRequest::getString("action"))
    					->addParameter("nameInCell", "lecturer")
    					->build(),
    				"icon" => "actions/list-add.png"
    			)
    		));
    	}
    	$this->setData("link", $this->getLink());
    	$this->setData("year", $year);
    	$this->setData("yearPart", $yearPart);
    	$this->setData("name", $group);
    	$this->setData("selectedName", $selectedName);
    	$this->setData("nameInCell", "lecturer");
    	$this->setData("time", $this->getTime());
    	$this->setData("existDays", $this->getDay());
    	$this->setData("schedules", $schedules);
    	$this->setData("groups", $groups);
    	$this->renderView("__public/_schedule/view.tpl");
    }
    public function actionPrintView() {
    	$this->setData("isPublic", true);
    	
    	$year = CTaxonomyManager::getYear(CRequest::getInt("year"));
    	$yearPart = CTaxonomyManager::getYearPart(CRequest::getInt("yearPart"));
    	if (CRequest::getString("nameInCell") == "studentGroup") {
    		$user = CStaffManager::getUser(CRequest::getInt("name"));
    		$schedules = CScheduleService::getScheduleUserByYearAndPart($user, $year, $yearPart);
    		$this->setData("name", $user);
    	} elseif (CRequest::getString("nameInCell") == "lecturer") {
    		$group = CStaffManager::getStudentGroup(CRequest::getInt("name"));
    		$schedules = CScheduleService::getScheduleGroupByYearAndPart($group, $year, $yearPart);
    		$this->setData("name", $group);
    	}
    	$this->setData("invert", CRequest::getInt("invert") == 1);
    	$this->setData("link", $this->getLink());
    	$this->setData("year", $year);
    	$this->setData("yearPart", $yearPart);
    	$this->setData("nameInCell", CRequest::getString("nameInCell"));
    	$this->setData("time", $this->getTime());
    	$this->setData("existDays", $this->getDay());
    	$this->setData("schedules", $schedules);
    	$this->renderView("__public/_schedule/printView.tpl");
    }
    public function actionAllSchedule() {
    	$this->setData("isPublic", $this->isPublic());
    	
    	if (CRequest::getInt("year") != 0) {
    		$year = CTaxonomyManager::getYear(CRequest::getInt("year"));
    	} else {
    		$year = CUtils::getCurrentYear();
    	}
    	if (CRequest::getInt("yearPart") != 0) {
    		$yearPart = CTaxonomyManager::getYearPart(CRequest::getInt("yearPart"));
    	} else {
    		$yearPart = CUtils::getCurrentYearPart();
    	}
    	
    	$countLecturers = CScheduleService::getLecturersWithSchedulesByYearAndPart($year, $yearPart)->getCount();
    	if ($this->isPublic()) {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "К списку преподавателей",
    				"link" => WEB_ROOT."_modules/_schedule/public.php?action=lecturers",
    				"icon" => "actions/edit-undo.png"
    			)
    		));
    	} else {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Назад",
    				"link" => WEB_ROOT."_modules/_schedule/index.php",
    				"icon" => "actions/edit-undo.png"
    			)
    		));
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Выгрузить в Excel",
    			"link" => UrlBuilder::newBuilder($this->getLink())
    				->addParameter("action", "printAll")
    				->addParameter("year", $year->getId())
    				->addParameter("yearPart", $yearPart->getId())
    				->build(),
    			"icon" => "actions/document-print-preview.png"
    		)
    	));
    	
    	$this->setData("link", $this->getLink());
    	$this->setData("print", false);
    	$this->setData("name", "all");
    	$this->setData("selectedName", null);
    	$this->setData("nameInCell", "all");
    	$this->setData("year", $year);
    	$this->setData("yearPart", $yearPart);
    	$this->setData("time", $this->getTime());
    	$this->setData("existDays", $this->getAllDay());
    	$this->setData("countLecturers", $countLecturers);
    	$this->renderView("__public/_schedule/viewAll.tpl");
    }
    public function actionPrintAll() {
    	$this->setData("isPublic", true);
    	
    	$year = CTaxonomyManager::getYear(CRequest::getInt("year"));
    	$yearPart = CTaxonomyManager::getYearPart(CRequest::getInt("yearPart"));
    	$countLecturers = CScheduleService::getLecturersWithSchedulesByYearAndPart($year, $yearPart)->getCount();
    	
    	$this->setData("link", $this->getLink());
    	$this->setData("print", true);
    	$this->setData("year", $year);
    	$this->setData("yearPart", $yearPart);
    	$this->setData("time", $this->getTime());
    	$this->setData("existDays", $this->getAllDay());
    	$this->setData("countLecturers", $countLecturers);
    	$this->renderView("__public/_schedule/printAll.tpl");
    }
    /**
     * Показать расписание по выбранному семестру и году
     */
    public function actionShowSchedules() {
    	$this->redirect(UrlBuilder::newBuilder($this->getLink())
    			->addParameter("action", CRequest::getString("redirect"))
    			->addParameter("id", CRequest::getInt("name"))
    			->addParameter("year", CRequest::getInt("year"))
    			->addParameter("yearPart", CRequest::getInt("yearPart"))
    			->build());
    }
    /**
     * Время занятий
     * 
     * @return array
     */
    public function getTime() {
    	$time = array();
    	$time[1] = '8.00-9.35';
    	$time[2] = '9.45-11.20';
    	$time[3] = '12.10-13.45';
    	$time[4] = '13.55-15.30';
    	$time[5] = '16.10-17.45';
    	$time[6] = '17.55-19.30';
    	$time[7] = 'Вечерники';
    	return $time;
    }
    /**
     * Дни занятий (сокращённые названия)
     * 
     * @return array
     */
    public function getDay() {
    	$existDays = array(1=>'Пн',2=>'Вт',3=>'Ср',4=>'Чт',5=>'Пт',6=>'Сб');
    	return $existDays;
    }
    /**
     * Дни занятий (полные названия)
     *
     * @return array
     */
    public function getAllDay() {
    	$existDays = array(1=>'Понедельник',2=>'Вторник',3=>'Среда',4=>'Четверг',5=>'Пятница',6=>'Суббота');
    	return $existDays;
    }
    /**
     * Строка для отображения индивидуального расписания в ячейке
     *
     * @param CSchedule $schedule - объект расписания
     * @param CUser/CStudentGroup $nameInCell - пользователь, либо учебная группа в зависимости от выбора
     * @param boolean $cellLab - признак лабораторной работы
     * @return string
     */
    public static function getCellForSchedule(CSchedule $schedule, $nameInCell, $cellLab = false) {
    	$libraryDocument = null;
    	$disciplineValue = "";
    	$disciplineAlias = "";
    	$name = "";
    	if (!is_null($nameInCell)) {
    		$name = $nameInCell->getName();
    	}
    	if (!is_null($schedule->discipline)) {
    		if (!is_null($schedule->lecturer)) {
    			$libraryDocument = CLibraryManager::getLibraryDocumentByUserAndDiscipline($schedule->lecturer, $schedule->discipline);
    		}
    		$disciplineValue = $schedule->discipline->getValue();
    		$disciplineAlias = $schedule->discipline->getAlias();
    	}
    	if (!is_null($libraryDocument) and !$cellLab) {
    		$cell = $schedule->length.' нед. '.$name.', <br> ауд. '.$schedule->place.',
    			<a href="../../_modules/_library/index.php?action=publicView&id='.$libraryDocument->nameFolder.'
    				"title="'.$disciplineValue.'" target="_blank"><b>'.$disciplineAlias.'</b></a> ('.$schedule->kindWork->getValue().')';
    	} elseif ($cellLab) {
    		$cell = '<font color="silver">'.$schedule->length.' нед. '.$name.', <br> ауд. '.$schedule->place.',
    			<a style="color:silver;" title="'.$disciplineValue.'"><b>'.$disciplineAlias.'</b></a> ('.$schedule->kindWork->getValue().')</font>';
    	} else {
    		$cell = $schedule->length.' нед. '.$name.', <br> ауд. '.$schedule->place.',
    			<a style="color:silver;" title="'.$disciplineValue.'"><b>'.$disciplineAlias.'</b></a> ('.$schedule->kindWork->getValue().')';
    	}
    	return $cell;
    } 
    /**
     * Строка для отображения общего расписания в ячейке
     *
     * @param CSchedule $schedule - объект расписания
     * @return string
     */
    public static function getCellForAllSchedule(CSchedule $schedule) {
    	$name = "";
    	if (!is_null($schedule->studentGroup)) {
    		$name = $schedule->studentGroup->getName();
    	}
    	if (!is_null($schedule->discipline)) {
    		$disciplineValue = $schedule->discipline->getValue();
    		$disciplineAlias = $schedule->discipline->getAlias();
    	} else {
    		$disciplineValue = "";
    		$disciplineAlias = "";
    	}
    	$cell = $schedule->length.' нед. '.$name.', ауд. '.$schedule->place.',
    		<a title="'.$disciplineValue.'"><b>'.$disciplineAlias.'</b></a> ('.$schedule->kindWork->getValue().')';
    	return $cell;
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по ФИО преподавателя
    	*/
    	if (CSettingsManager::getSettingValue("hide_personal_data")) {
    		$query = new CQuery();
    		$query->select("distinct(users.id) as id, users.FIO as name")
	    		->from(TABLE_USERS." as users")
	    		->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
	    		->innerJoin(TABLE_SCHEDULE." as schedule", "users.id = schedule.user_id")
	    		->condition("users.FIO like '%".$term."%' and userGroup.group_id=1 and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId())
	    		->limit(0, 5);
    		foreach ($query->execute()->getItems() as $item) {
    			$res[] = array(
    				"field" => "users.id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CLecturerOuter"
    			);
    		}
    	} else {
    		$query = new CQuery();
    		$query->select("distinct(person.id) as id, person.fio as name")
	    		->from(TABLE_PERSON." as person")
	    		->innerJoin(TABLE_USERS." as users", "users.kadri_id=person.id")
	    		->innerJoin(TABLE_SCHEDULE." as schedule", "users.id = schedule.user_id")
	    		->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
	    		->condition("person.fio like '%".$term."%' and userGroup.group_id=1 and schedule.year=".CUtils::getCurrentYear()->getId()." and schedule.month=".CUtils::getCurrentYearPart()->getId())
	    		->limit(0, 5);
    		foreach ($query->execute()->getItems() as $item) {
    			$res[] = array(
    				"field" => "person.id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CPerson"
    			);
    		}
    	}
    	echo json_encode($res);
    }
}