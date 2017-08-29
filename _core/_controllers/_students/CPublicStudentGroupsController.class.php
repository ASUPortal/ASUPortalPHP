<?php

class CPublicStudentGroupsController extends CBaseController {
	public $allowedAnonymous = array(
			"index",
			"view",
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
    	$this->setPageTitle("Учебные группы студентов");
    
    	parent::__construct();
    }
    public function actionIndex() {
    	$set = new CRecordSet();
    	$query = new CQuery();
    	$query->select("st_group.*")
	    	->from(TABLE_STUDENT_GROUPS." as st_group")
	    	->condition("st_group.year_id =".CUtils::getCurrentYear()->id)
	    	->order("st_group.name asc");
    	$set->setQuery($query);
    	$queryLetter = new CQuery();
    	$queryLetter->select("st_group.*, UPPER(left(st_group.name,1)) as name, count(*) as cnt")
	    	->from(TABLE_STUDENT_GROUPS." as st_group")
	    	->condition("st_group.year_id =".CUtils::getCurrentYear()->id." ")
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
    	if (CRequest::getInt("getsub")>0 and is_null(CRequest::getFilter("id"))) {
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
    			"link" => WEB_ROOT."_modules/_lecturers/index.php",
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
    	$this->renderView("__public/_student_groups/index.tpl");
    }
    public function actionView() {
    	$group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_student_groups/public.php?action=index",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->setData("group", $group);
    	$this->renderView("__public/_student_groups/view.tpl");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("query");
        /**
         * Ищем группу по названию
         */
        $query = new CQuery();
        $query->select("st_group.id as id, st_group.name as name")
            ->from(TABLE_STUDENT_GROUPS." as st_group")
            ->condition("LCASE(st_group.name) like '%".mb_strtolower($term)."%' and st_group.year_id =".CUtils::getCurrentYear()->id)
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "field" => "st_group.id",
                "value" => $item["id"],
                "label" => $item["name"],
                "class" => "CStudentGroup"
            );
        }
        echo json_encode($res);
    }
}
