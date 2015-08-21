<?php

class CLecturersController extends CBaseController {
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
        $this->setPageTitle("Преподаватели");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("person.*")
            ->from(TABLE_PERSON." as person")
            ->innerJoin(TABLE_USERS." as users", "users.kadri_id=person.id")
            ->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
            ->condition("userGroup.group_id=1")
            ->order("person.fio asc");
        $queryLetter = new CQuery();
        $queryLetter->select("person.*, UPPER(left(person.fio,1)) as name, count(*) as cnt")
        ->from(TABLE_PERSON." as person")
        ->innerJoin(TABLE_USERS." as users", "users.kadri_id=person.id")
        ->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
        ->condition("userGroup.group_id=1 ")
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
        if (CRequest::getInt("getsub")>0 and is_null(CRequest::getFilter("person.id"))) {
        	if (CRequest::getInt("getsub")>0) {
        		$letterId = CRequest::getInt("getsub");
        	}
        	$query->condition('person.fio like "'.$letter.'%" and userGroup.group_id=1');
        }
        if (CSession::isAuth() and (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY or
            CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_ALL)) {
        	$this->addActionsMenuItem(array(
        			array(
        				"title" => "Добавить биографию",
        				"link" => WEB_ROOT."_modules/_biography/index.php",
        				"icon" => "actions/list-add.png"
        			)
        		)
        	);		
        }
        $lects = new CArrayList();
        $set->setQuery($query);      
        foreach ($set->getPaginated()->getItems() as $ar) {
            $lect = new CPerson($ar);
            $lects->add($lect->getId(), $lect);
        }
        $this->setData("resRusLetters", $resRusLetters);
        $this->setData("letterId", $letterId);
        $this->setData("firstLet", $firstLet);
        $this->setData("paginator", $set->getPaginator());
        $this->setData("lects", $lects);
        $this->renderView("__public/_lecturers/index.tpl");
    }
    public function actionView() {
    	$lect = CStaffManager::getPerson(CStaffManager::getUserById(CRequest::getInt("id"))->kadri_id);
    	$this->addActionsMenuItem(array(
			array(
				"title" => "Назад",
				"link" => WEB_ROOT."_modules/_lecturers/index.php",
				"icon" => "actions/edit-undo.png"
			)
		));
    	$this->setData("lect", $lect);
    	$this->renderView("__public/_lecturers/view.tpl");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("query");
		/**
    	 * Поиск по ФИО преподавателя
    	 */
    	$query = new CQuery();
    	$query->select("distinct(person.id) as id, person.fio as name")
    	->from(TABLE_PERSON." as person")
    	->innerJoin(TABLE_USERS." as users", "users.kadri_id=person.id")
    	->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=users.id")
    	->condition("person.fio like '%".$term."%' and userGroup.group_id=1")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "person.id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CPerson"
    		);
    	}
        echo json_encode($res);
    }
}