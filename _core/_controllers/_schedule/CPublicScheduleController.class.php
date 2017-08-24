<?php
/**
 * Учебное расписание
 */
class CPublicScheduleController extends CPublicStudentGroupsController {
	public $allowedAnonymous = array(
			"index",
			"viewLecturers",
			"viewGroups",
			"printView",
			"allSchedule",
			"printAll",
			"showSchedules"
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

        CBaseController::__construct();
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
    				"link" => WEB_ROOT."_modules/_lecturers/index.php",
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
    				"link" => WEB_ROOT."_modules/_student_groups/public.php",
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
    				"link" => WEB_ROOT."_modules/_lecturers/index.php",
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
}