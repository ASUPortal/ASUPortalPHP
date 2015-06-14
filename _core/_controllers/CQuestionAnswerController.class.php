<?php

class CQuestionAnswerController extends CBaseController{
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
        $this->setPageTitle("Вопрос-ответ");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet(false);
        $query = new CQuery();
        $selectedUser = null;
        $query->select("quest.*")
            ->from(TABLE_QUESTION_TO_USERS." as quest")
            ->order("quest.datetime_quest desc")
			->condition("quest.status=5");
        $set->setQuery($query);
        $showAll = false;
        if (CRequest::getString("order") == "quest.user_id") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_USERS." as user", "quest.user_id = user.id");
        		$query->order("user.fio ".$direction);
        }   
        elseif (CRequest::getString("order") == "datetime_quest") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("datetime_quest ".$direction);
        }
        elseif (CRequest::getString("order") == "datetime_answ") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("datetime_answ ".$direction);
        }
        elseif (CRequest::getString("order") == "question_text") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("question_text ".$direction);
        }
        elseif (CRequest::getString("order") == "contact_info") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("contact_info ".$direction);
        }
        elseif (CRequest::getString("order") == "st.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->leftJoin(TABLE_QUESTION_STATUS." as st", "quest.status = st.id");
        		$query->order("st.name ".$direction);
        }
        elseif (CRequest::getString("order") == "answer_text") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("answer_text ".$direction);
        }
        // фильтр по пользователю
        if (!is_null(CRequest::getFilter("user"))) {
        	$query->innerJoin(TABLE_USERS." as user", "quest.user_id = user.id and user.id = ".CRequest::getFilter("user"));
        	$selectedUser = CRequest::getFilter("user");
        }
        // фильтр по вопросу
        if (!is_null(CRequest::getFilter("question"))) {
        	$query->condition("quest.id = ".CRequest::getFilter("question"));
        }
        // фильтр по ответу
        if (!is_null(CRequest::getFilter("answer"))) {
        	$query->condition("quest.id = ".CRequest::getFilter("answer"));
        }
        // фильтр по контактам
        if (!is_null(CRequest::getFilter("contact"))) {
        	$query->condition("quest.id = ".CRequest::getFilter("contact"));
        }
        $quests = new CArrayList();
        $isArchive = (CRequest::getString("isArchive") == "1");
        if (!$isArchive) {
        	if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY or
        		CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {
        				$query->condition('quest.user_id = "'.CSession::getCurrentUser()->getId().'" and (quest.datetime_quest > "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" or quest.datetime_quest is NULL) and quest.status!=5');
        			}
        			else {
        				$query->condition('(quest.datetime_quest > "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" or quest.datetime_quest is NULL) and quest.status!=5');
					}
        }
        else {
        	if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY or
        		CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {
        				$query->condition('quest.user_id = "'.CSession::getCurrentUser()->getId().'" and quest.status!=5');
        			}
        			else {
        				$query->condition("quest.status!=5");
        			}
        }
        if (CRequest::getInt("showAll") == 1) {
			if (!$isArchive) {
	        	if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY or
	        		CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {
	        				$query->condition('quest.user_id = "'.CSession::getCurrentUser()->getId().'" and (quest.datetime_quest > "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" or quest.datetime_quest is NULL)');
	        			}
	        			else {
	        				$query->condition('quest.datetime_quest > "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" or quest.datetime_quest is NULL');
						}
	        }
	        else {
	        	if (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_READ_OWN_ONLY or
	        		CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY) {
	        				$query->condition('quest.user_id = "'.CSession::getCurrentUser()->getId().'"');
	        			}
	        }        	
	        $showAll = true;
        }
        foreach ($set->getPaginated()->getItems() as $ar) {
        	$quest = new CQuestion($ar);
        	$quests->add($quest->getId(), $quest);
        }
        if ($isArchive) {
        	$requestParams = array();
        	foreach (CRequest::getGlobalRequestVariables()->getItems() as $key=>$value) {
        		if ($key != "isArchive") {
        			$requestParams[] = $key."=".$value;
        		}
        	}
        	$this->addActionsMenuItem(array(
        			array(
        					"title" => "Текущий год",
        					"link" => "?".implode("&", $requestParams),
        					"icon" => "mimetypes/x-office-calendar.png"
        			),
        	));
        } else {
        	$requestParams = array();
        	foreach (CRequest::getGlobalRequestVariables()->getItems() as $key=>$value) {
        		$requestParams[] = $key."=".$value;
        	}
        	$requestParams[] = "isArchive=1";
        	$this->addActionsMenuItem(array(
        			array(
        					"title" => "Архив",
        					"link" => "?".implode("&", $requestParams),
        					"icon" => "devices/media-floppy.png"
        			),
        	));
        }
        $usersQuery = new CQuery();
        $usersQuery->select("user.*")
        ->from(TABLE_USERS." as user")
        ->order("user.fio asc")
		->innerJoin(TABLE_QUESTION_TO_USERS." as quest", "user.id = quest.user_id");
        $users = array();
        foreach ($usersQuery->execute()->getItems() as $ar) {
        	$user = new CUser(new CActiveRecord($ar));
        	$users[$user->getId()] = $user->getName();
        }
        $this->setData("isArchive", $isArchive);
        $this->setData("showAll", $showAll);
        $this->setData("quests", $quests);
        $this->setData("users", $users);
        $this->setData("selectedUser", $selectedUser);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_question_answ/index.tpl");
    }
    public function actionAdd() {
    	$quest = new CQuestion();
    	$this->setData("quest", $quest);
    	$this->renderView("_question_answ/add.tpl");
    }
    public function actionAddQuestion() {
    	$quest = new CQuestion();
    	$quest->user_id = CRequest::getInt("user_id");
    	$this->setData("quest", $quest);
    	$this->renderView("_question_answ/add.tpl");
    }
    public function actionEdit() {
    	$quest = CQuestionManager::getQuestion(CRequest::getInt("id"));
    	$this->setData("quest", $quest);
    	$this->renderView("_question_answ/edit.tpl");
    }
    public function actionDelete() {
    	$quest = CQuestionManager::getQuestion(CRequest::getInt("id"));
		$quest->status = 5;
		$quest->save();
    	$this->redirect("index.php?action=index");
    }
    public function actionSave() {
    	$quest = new CQuestion();
    	$quest->setAttributes(CRequest::getArray($quest::getClassName()));
    	if ($quest->validate()) {
    		$quest->contact_info .= " ".CStaffManager::getUser(CSession::getCurrentUser()->getId())->getName().'; ip '.$_SERVER["REMOTE_ADDR"];
    		if ($quest->answer_text != '') {
    			$quest->datetime_answ=date("Y-d-m H:i:s",time());
    		}
    		$quest->save();
    		if ($this->continueEdit()) {
    			$this->redirect("?action=edit&id=".$quest->getId());
    		} else {
    			$this->redirect("index.php?action=index");
    		}
    		return true;
    	}
    	$this->setData("quest", $quest);
    	$this->renderView("_question_answ/edit.tpl");
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по адресату
    	*/
    	$query = new CQuery();
    	$query->select("distinct(quest.user_id) as id, user.fio as name")
    	->from(TABLE_QUESTION_TO_USERS." as quest")
    	->innerJoin(TABLE_USERS." as user", "quest.user_id = user.id")
    	->condition("user.fio like '%".$term."%'")
    	->limit(0, 10);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"label" => $item["name"],
    				"value" => $item["name"],
    				"object_id" => $item["id"],
    				"type" => 1
    		);
    	}
    	/**
    	 * Поиск по вопросу
    	 */
    	$query = new CQuery();
    	$query->select("distinct(quest.id) as id, quest.question_text as question")
    	->from(TABLE_QUESTION_TO_USERS." as quest")
    	->condition("quest.question_text like '%".$term."%'")
    	->limit(0, 10);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"label" => $item["question"],
    				"value" => $item["question"],
    				"object_id" => $item["id"],
    				"type" => 2
    		);
    	}
    	/**
    	 * Поиск по ответу
    	 */
		$query = new CQuery();
    	$query->select("distinct(quest.id) as id, quest.answer_text as answer")
    	->from(TABLE_QUESTION_TO_USERS." as quest")
    	->condition("quest.answer_text like '%".$term."%'")
    	->limit(0, 10);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"label" => $item["answer"],
    				"value" => $item["answer"],
    				"object_id" => $item["id"],
    				"type" => 3
    		);
    	}
    	/**
    	 * Поиск по контактам
    	 */
    	$query = new CQuery();
    	$query->select("distinct(quest.id) as id, quest.contact_info as contact")
    	->from(TABLE_QUESTION_TO_USERS." as quest")
    	->condition("quest.contact_info like '%".$term."%'")
    	->limit(0, 10);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"label" => $item["contact"],
    				"value" => $item["contact"],
    				"object_id" => $item["id"],
    				"type" => 4
    		);
    	}
    	echo json_encode($res);
    }
}