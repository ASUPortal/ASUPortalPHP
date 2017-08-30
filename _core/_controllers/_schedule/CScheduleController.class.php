<?php
/**
 * Управление учебным расписанием
 */
class CScheduleController extends CPublicScheduleController {
	public $allowedAnonymous = array();
	
    public function __construct() {
    	if (!CSession::isAuth()) {
    		if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
    			$this->redirectNoAccess();
    		}
    	}
    	$this->_smartyEnabled = true;
    	$this->setPageTitle("Управление расписанием");
    	
    	CBaseController::__construct();
    }
    /**
     * Контроллер открытого доступа?
     *
     * @return boolean
     */
    protected function isPublic() {
    	return false;
    }
    public function actionIndex() { 
    	$selectedUser = null;
    	$selectedGroup = null;
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
    	if (CSessionService::hasAnyRole([ACCESS_LEVEL_READ_ALL, ACCESS_LEVEL_WRITE_ALL])) {
    		foreach (CScheduleService::getLecturersWithSchedulesByYearAndPart($year, $yearPart)->getItems() as $lecturer) {
    			$lecturers[$lecturer->getId()] = $lecturer->getName();
    		}
    	}
    	$user = CSession::getCurrentUser();
    	$selectedUser = $user->getId();
    	if (empty($lecturers)) {
    		$lecturers[$user->getId()] = $user->getName();
    	}
    	$groups = array();
    	foreach (CScheduleService::getGroupsWithSchedulesByYearAndPart($year, $yearPart)->getItems() as $studentGroup) {
    		$groups[$studentGroup->getId()] = $studentGroup->getName();
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Общее расписание",
    			"link" => UrlBuilder::newBuilder("index.php")
    				->addParameter("action", "allSchedule")
    				->build(),
    			"icon" => "apps/office-calendar.png"
    		)
    	));
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Добавить",
    			"link" => UrlBuilder::newBuilder("index.php")
    				->addParameter("action", "add")
    				->addParameter("nameId", $user->getId())
    				->addParameter("year", $year->getId())
    				->addParameter("yearPart", $yearPart->getId())
    				->addParameter("redirect", "viewLecturers")
    				->addParameter("nameInCell", "studentGroup")
    				->build(),
    			"icon" => "actions/list-add.png"
    		)
    	));
    	$this->setData("year", $year);
    	$this->setData("yearPart", $yearPart);
    	$this->setData("name", $user);
    	$this->setData("user", $user);
    	$this->setData("selectedUser", $selectedUser);
    	$this->setData("selectedGroup", $selectedGroup);
    	$this->setData("lecturers", $lecturers);
    	$this->setData("groups", $groups);
    	$this->renderView("_schedule/index.tpl");
    }
    public function actionAdd() {
        $schedule = new CSchedule();
        if (CRequest::getString("nameInCell") == "studentGroup") {
        	$schedule->user_id = CRequest::getInt("nameId");
        } elseif (CRequest::getString("nameInCell") == "lecturer") {
        	$schedule->grup = CRequest::getInt("nameId");
        } elseif (CRequest::getString("nameInCell") == "all") {
        	$schedule->user_id = CRequest::getInt("nameId");
        }
        $schedule->year = CRequest::getInt("year");
        $schedule->month= CRequest::getInt("yearPart");
        $schedule->day = CRequest::getInt("day");
        $schedule->number = CRequest::getInt("number");
        $year = CUtils::getCurrentYear();
        $groups = array();
        foreach (CStaffManager::getStudentGroupsByYear($year)->getItems() as $group) {
        	$groups[$group->getId()] = $group->getName();
        }
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy(TABLE_SCHEDULE_KIND_WORK);
        $kindWorks = array();
        foreach ($taxonomy->getTerms()->getItems() as $term) {
        	$kindWorks[$term->getId()] = $term->getValue();
        }
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => WEB_ROOT."_modules/_schedule/index.php?action=".CRequest::getString("redirect")."&id=".CRequest::getInt("nameId"),
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->setData("nameId", CRequest::getInt("nameId"));
        $this->setData("groups", $groups);
        $this->setData("times", $this->getTime());
        $this->setData("days", $this->getAllDay());
        $this->setData("kindWorks", $kindWorks);
        $this->setData("schedule", $schedule);
        $this->renderView("_schedule/add.tpl");
    }
    public function actionEdit() {
        $schedule = CBaseManager::getSchedule(CRequest::getInt("id"));
        $year = CTaxonomyManager::getYear($schedule->year);
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy(TABLE_SCHEDULE_KIND_WORK);
        $kindWorks = array();
        foreach ($taxonomy->getTerms()->getItems() as $term) {
        	$kindWorks[$term->getId()] = $term->getValue();
        }
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => WEB_ROOT."_modules/_schedule/index.php",
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->setData("groups", CStaffManager::getAllStudentGroupsList());
        $this->setData("times", $this->getTime());
        $this->setData("days", $this->getAllDay());
        $this->setData("kindWorks", $kindWorks);
        $this->setData("day", CRequest::getInt("day"));
        $this->setData("time", CRequest::getInt("number"));
        $this->setData("part", CRequest::getInt("part"));
        $this->setData("schedule", $schedule);
        $this->renderView("_schedule/edit.tpl");
    }
    public function actionDelete() {
        $schedule = CBaseManager::getSchedule(CRequest::getInt("id"));
        if (!is_null($schedule)) {
            $schedule->remove();
        }
        $this->redirect("?action=".CRequest::getString("redirect")."&id=".CRequest::getInt("nameId"));
    }
    public function actionSave() {
        $schedule = new CSchedule();
        $schedule->setAttributes(CRequest::getArray($schedule::getClassName()));
        if ($schedule->validate()) {
            $schedule->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$schedule->getId()."&nameId=".CRequest::getInt("nameId")."&redirect=".CRequest::getString("redirect"));
            } else {
                $this->redirect(WEB_ROOT."_modules/_schedule/index.php?action=".CRequest::getString("redirect")."&id=".CRequest::getInt("nameId"));
            }
            return true;
        }
        $groups = array();
        foreach (CStaffManager::getStudentGroupsByYear(CUtils::getCurrentYear())->getItems() as $group) {
            $groups[$group->getId()] = $group->getName();
        }
        $taxonomy = CTaxonomyManager::getLegacyTaxonomy(TABLE_SCHEDULE_KIND_WORK);
        $kindWorks = array();
        foreach ($taxonomy->getTerms()->getItems() as $term) {
        	$kindWorks[$term->getId()] = $term->getValue();
        }
        $this->setData("groups", $groups);
        $this->setData("times", $this->getTime());
        $this->setData("days", $this->getAllDay());
        $this->setData("kindWorks", $kindWorks);
        $this->setData("schedule", $schedule);
        $this->renderView("_schedule/add.tpl");
    }
}