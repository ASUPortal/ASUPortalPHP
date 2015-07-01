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
        $query->select("user.*")
            ->from(TABLE_USERS." as user")
            ->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=user.id")
            ->condition("userGroup.group_id=1")
            ->order("user.FIO asc");
        $firstLet = array("А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф",
        		"Х","Ц","Ч","Ш","Щ","Э","Ю","Я");
        $letter = $firstLet[CRequest::getInt("getsub")];
        $letterId = -1;
        if (CRequest::getInt("getsub")>0) {
        	$letterId = CRequest::getInt("getsub");
        }
        $queryLetter = new CQuery();
        $queryLetter->select("user.*, UPPER(left(user.FIO,1)) as name, count(*) as cnt")
        ->from(TABLE_USERS." as user")
        ->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=user.id")
        ->condition("userGroup.group_id=1 ")
        ->group(1)
        ->order("user.FIO asc");  
        $resRus = array();
        foreach ($queryLetter->execute()->getItems() as $ar) {
        	$res = new CLecturer(new CActiveRecord($ar));
        	$resRus[$res->id] = $res->name;
        }
        $resRusLetters = array();
        $resRusLetters = array_count_values($resRus);
        if (isset($_GET['getsub']) and !isset($_GET['filter'])) {
        	$query->condition('user.FIO like "'.$letter.'%" and userGroup.group_id=1');
        }
        if (CSession::isAuth() and (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY or
            CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_ALL)) {
        	$this->addActionsMenuItem(array(
        			array(
        				"title" => "Добавить биографию",
        				"link" => WEB_ROOT."_modules/_biography/",
        				"icon" => "actions/list-add.png"
        			)
        		)
        	);		
        }
        $lects = new CArrayList();
        $set->setQuery($query);      
        foreach ($set->getPaginated()->getItems() as $ar) {
            $lect = new CLecturer($ar);
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
    	$lect = CBaseManager::getLecturer(CRequest::getInt("id"));
    	$this->addActionsMenuItem(array(
			array(
				"title" => "Назад",
				"link" => WEB_ROOT."_modules/_lecturers/index.php",
				"icon" => "actions/edit-undo.png"
			)
		));
    	//Сокращение текста биографии  
    	if ($lect->getBiography()->getCount() != 0) {
    		foreach ($lect->getBiography()->getItems() as $biogr) {
    			$printFullBox = false;
    			if (mb_strlen($biogr->main_text) > 500) {
    				$biog = mb_substr(CUtils::msg_replace($biogr->main_text), 0, 500);
    				$printFullBox = true;
    			}
    			else {
    				$biog = CUtils::msg_replace($biogr->main_text);
    			}
    			$this->setData("printFullBox", $printFullBox);
    			$this->setData("biog", $biog);
    		}
    		
    	}
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
    	$query->select("distinct(user.id) as id, user.FIO as name")
    	->from(TABLE_USERS." as user")
    	->innerJoin(TABLE_USER_IN_GROUPS." as userGroup", "userGroup.user_id=user.id")
    	->condition("user.FIO like '%".$term."%' and userGroup.group_id=1")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "user.id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CLecturer"
    		);
    	}
        echo json_encode($res);
    }
}