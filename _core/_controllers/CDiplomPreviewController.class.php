<?php
class CDiplomPreviewController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
        	$action = CRequest::getString("action");
        	if ($action == "") {
        		$action = "index";
        	}
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }
        $this->_smartyEnabled = true;
        $this->setPageTitle("Предзащита ВКР - студенты");
        parent::__construct();
    }
    public function actionIndex() {
    	$set = new CRecordSet(false);
    	$query = new CQuery();
    	$currentCommission = null;
    	$currentGroup = null;
    	$isArchive = (CRequest::getString("isArchive") == "1");
    	$set->setQuery($query);
    	$query->select("preview.*")
    	->from(TABLE_DIPLOM_PREVIEWS." as preview")
    	->order("preview.date_preview desc");
    	$set->setQuery($query);
    	$commQuery = new CQuery();
    	$commQuery->select("comm.*")
    	->from(TABLE_DIPLOM_PREVIEW_COMISSIONS." as comm")
    	->order("comm.name asc")
    	->innerJoin(TABLE_DIPLOM_PREVIEWS." as preview", "comm.id = preview.comm_id");
    	if (!$isArchive) {
    		$commQuery->condition('preview.date_preview between "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" and "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_end)).'"');
    	}
    	$groupsQuery = new CQuery();
    	$groupsQuery->select("stgroup.*")
    	->from(TABLE_STUDENT_GROUPS." as stgroup")
    	->order("stgroup.name asc")
    	->innerJoin(TABLE_STUDENTS." as student", "stgroup.id = student.group_id")
    	->innerJoin(TABLE_DIPLOM_PREVIEWS." as preview", "student.id =  preview.student_id");
    	if (!$isArchive) {
    		$groupsQuery->condition('preview.date_preview between "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" and "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_end)).'"');
    	}
    	if (CRequest::getString("order") == "student.fio") {
    		$direction = "asc";
    		if (CRequest::getString("direction") != "") {
    			$direction = CRequest::getString("direction");}
    			$query->innerJoin(TABLE_STUDENTS." as student", "preview.student_id=student.id");
    			$query->order("student.fio ".$direction);
    	}
        elseif (CRequest::getString("order") == "st_group.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_STUDENTS." as student", "preview.student_id=student.id");
        		$query->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id");
        		$query->order("st_group.name ".$direction);
        }
        elseif (CRequest::getString("order") == "diplom.dipl_name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_DIPLOMS." as diplom", "diplom.student_id = preview.student_id");
        		$query->order("diplom.dipl_name ".$direction);
        }
        elseif (CRequest::getString("order") == "diplom_percent") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("diplom_percent ".$direction);
        }
        elseif (CRequest::getString("order") == "another_view") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("another_view ".$direction);
        }
        elseif (CRequest::getString("order") == "person.fio") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_DIPLOMS." as diplom", "diplom.student_id = preview.student_id");
        		$query->leftJoin(TABLE_PERSON." as person", "person.id = diplom.recenz_id");
        		$query->order("person.fio ".$direction);
        }
        elseif (CRequest::getString("order") == "date_preview") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("date_preview ".$direction);
        }
        elseif (CRequest::getString("order") == "comm.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_DIPLOM_PREVIEW_COMISSIONS." as comm", "preview.comm_id = comm.id");
        		$query->order("comm.name ".$direction);
        }
        elseif (CRequest::getString("order") == "comment") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("comment ".$direction);
        }
    	// фильтр по комисии
    	if (!is_null(CRequest::getFilter("commission"))) {
    		$query->innerJoin(TABLE_DIPLOM_PREVIEW_COMISSIONS." as comm", "preview.comm_id = comm.id and comm.id = ".CRequest::getFilter("commission"));
    		$currentCommission = CRequest::getFilter("commission");
    		// фильтруем еще и группы
    		$groupsQuery->innerJoin(TABLE_DIPLOM_PREVIEW_COMISSIONS." as comm", "preview.comm_id = comm.id and comm.id = ".CRequest::getFilter("commission"));
    	}
    	// фильтр по группе
    	if (!is_null(CRequest::getFilter("group"))) {
    		$arr = explode(",", CRequest::getFilter("group"));
    		foreach ($arr as $key=>$value) {
    			$arrs[] = 'st_group.id = '.$value;
    		}
    		$currentGroup = CRequest::getFilter("group");
    		$query->innerJoin(TABLE_STUDENTS." as student", "preview.student_id=student.id");
    		$query->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id and (".implode(" or ", $arrs).")");
    		$commQuery->innerJoin(TABLE_STUDENTS." as student", "preview.student_id = student.id");
    		$commQuery->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id and (".implode(" or ", $arrs).")");
    	}
    	// фильтр по студенту, теме ВКР, рецензенту
    	if (!is_null(CRequest::getFilter("student"))) {
    		$query->innerJoin(TABLE_STUDENTS." as student", "preview.student_id=student.id and student.id = ".CRequest::getFilter("student"));
    	}
    	// фильтр по комментарию
    	if (!is_null(CRequest::getFilter("comment"))) {
    		$query->condition("preview.id = ".CRequest::getFilter("comment"));
    	}
    	if (!$isArchive) {
    		$query->condition('preview.date_preview between "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" and "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_end)).'"');
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
    	$this->addActionsMenuItem(array(
    			"title" => "Добавить",
    			"link" => "?action=addPreview",
    			"icon" => "actions/list-add.png"
    	));
    	$previews = new CArrayList();
    	foreach ($set->getPaginated()->getItems() as $item) {
    		$preview = new CDiplomPreview($item);
    		$previews->add($preview->getId(), $preview);
    	}
    	$commissions = array();
    	foreach ($commQuery->execute()->getItems() as $ar) {
    		$comm = new CDiplomPreviewComission(new CActiveRecord($ar));
    		$secretar = CStaffManager::getPersonById($comm->secretary_id)->fio;
    		$commissions[$comm->getId()] = $comm->name." ($secretar)";
    	}
    	$studentGroups = array();
    	foreach ($groupsQuery->execute()->getItems() as $ar) {
    		$group = new CStudentGroup(new CActiveRecord($ar));
    		$studentGroups[$group->getId()] = $group->getName();
    	}
    	$this->setData("isArchive", $isArchive);
    	$this->setData("studentGroups", $studentGroups);
    	$this->setData("commissions", $commissions);
    	$this->setData("currentCommission", $currentCommission);
    	$this->setData("currentGroup", $currentGroup);
    	$this->setData("previews", $previews);
    	$this->setData("paginator", $set->getPaginator());
    	$this->renderView("_diploms/diplom_preview/index.tpl");
    }
    public function actionAdd() {
        $preview = new CDiplomPreview();
        $preview->diplom_id = CRequest::getInt("id");
        if (!is_null($preview->diplom)) {
        	$student = $preview->diplom->student;
        	if (!is_null($student)) {
        		$preview->student_id = $student->getId();
        	}
        }
        $preview->date_preview = date("d.m.Y", mktime());
        $this->setData("preview", $preview);
        $this->addActionsMenuItem(array(
        		array(
					"title" => "Назад",
					"link" => WEB_ROOT."_modules/_diploms/index.php?action=edit&id=".$preview->diplom_id,
					"icon" => "actions/edit-undo.png"
        		)
        ));
        $this->renderView("_diploms/diplom_preview/add.tpl");
    }
    public function actionAddPreview() {
    	$preview = new CDiplomPreview();
    	$this->setData("preview", $preview);
    	$this->addActionsMenuItem(array(
    			"title" => "Назад",
    			"link" => "?action=index",
    			"icon" => "actions/edit-undo.png"
    	));
    	$this->renderView("_diploms/diplom_preview/addPreview.tpl");
    }
    public function actionEdit() {
        $preview = CStaffManager::getDiplomPreview(CRequest::getInt("id"));
        $this->setData("preview", $preview);
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => WEB_ROOT."_modules/_diploms/index.php?action=edit&id=".$preview->diplom_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->renderView("_diploms/diplom_preview/edit.tpl");
    }
    public function actionEditPreview() {
    	$preview = CStaffManager::getDiplomPreview(CRequest::getInt("id"));
        $this->setData("preview", $preview);
    	$this->addActionsMenuItem(array(
    			"title" => "Назад",
    			"link" => "?action=index",
    			"icon" => "actions/edit-undo.png"
    	));
    	$this->renderView("_diploms/diplom_preview/editPreview.tpl");
    }
    public function actionDelete() {
        $preview = CStaffManager::getDiplomPreview(CRequest::getInt("id"));
        $diplom = $preview->diplom;
        $preview->remove();
        $this->redirect("index.php?action=edit&id=".$diplom->getId());
    }
    public function actionDeletePreview() {
		$preview = CStaffManager::getDiplomPreview(CRequest::getInt("id"));
    	$preview->remove();
    	$this->redirect("preview.php?action=index");
    }
    public function actionSave() {
        $preview = new CDiplomPreview();
        $preview->setAttributes(CRequest::getArray($preview::getClassName()));
        if ($preview->validate()) {
            $preview->save();
            if ($this->continueEdit()) {
                $this->redirect("preview.php?action=edit&id=".$preview->getId());
            } else {
                $this->redirect("index.php?action=edit&id=".$preview->diplom_id);
            }
            return true;
        }
        $this->setData("preview", $preview);
        $this->renderView("_diploms/diplom_preview/edit.tpl");
    }
    public function actionSavePreview() {
		$preview = new CDiplomPreview();
    	$preview->setAttributes(CRequest::getArray($preview::getClassName()));
    	if ($preview->validate()) {
    		$preview->save();
    		if ($this->continueEdit()) {
    			$this->redirect("?action=editPreview&id=".$preview->getId());
    		} else {
    			$this->redirect("preview.php?action=index");
    		}
    		return true;
    	}
    	$this->setData("preview", $preview);
    	$this->renderView("_diploms/diplom_preview/editPreview.tpl");
    }
    public function actionStatistic() {
		$set = new CRecordSet();
        $query = new CQuery();
        $query->select("preview.*")
            ->from(TABLE_DIPLOM_PREVIEWS." as preview")
            ->order("preview.date_preview desc");
        $set->setQuery($query);
        $isArchive = (CRequest::getString("isArchive") == "1");
        if (!$isArchive) {
        	$query->condition('preview.date_preview between "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" and "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_end)).'"');
        }
        $this->addActionsMenuItem(array(
        		"title" => "Назад",
        		"link" => "?action=index",
        		"icon" => "actions/edit-undo.png"
        ));
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
		$prevs = array();
    	foreach ($query->execute()->getItems() as $ar) {
    		$prev = new CDiplomPreview(new CActiveRecord($ar));
    		$prevs[$prev->getId()] = $prev->date_preview;
    	}
    	$previews = array();
    	$previews = array_count_values($prevs);
        $this->setData("paginator", $set->getPaginator());
        $this->setData("previews", $previews);
        $this->setData("previews", $previews);
        $this->renderView("_diploms/diplom_preview/stat.tpl");
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по ФИО студента
    	 */
    	$query = new CQuery();
    	$query->select("distinct(preview.student_id) as id, student.fio as name");
    	$query->innerJoin(TABLE_STUDENTS." as student", "preview.student_id=student.id")
    	->from(TABLE_DIPLOM_PREVIEWS." as preview")
    	->condition("student.fio like '%".$term."%'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"label" => $item["name"],
    				"value" => $item["name"],
    				"object_id" => $item["id"],
    				"type" => 1
    		);
    	}
    	/**
    	 * Поиск по теме ВКР
    	 */
 		$query = new CQuery();
 		$query->select("distinct(preview.student_id) as id, diplom.dipl_name as name");
 		$query->innerJoin(TABLE_DIPLOMS." as diplom", "diplom.student_id = preview.student_id")
 		->from(TABLE_DIPLOM_PREVIEWS." as preview")
 		->condition("diplom.dipl_name like '%".$term."%'")
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 2
 			);
 		}
 		/**
 		 * Поиск по рецензенту
 		 */
		$query = new CQuery();
 		$query->select("distinct(preview.student_id) as id, diplom.recenz as name");
 		$query->innerJoin(TABLE_DIPLOMS." as diplom", "diplom.student_id = preview.student_id")
 		->from(TABLE_DIPLOM_PREVIEWS." as preview")
 		->condition("diplom.recenz like '%".$term."%'")
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 3
 			);
 		}
 		/**
 		 * Поиск по рецензенту по id
 		 */
		$query = new CQuery();
 		$query->select("distinct(preview.student_id) as id, person.fio as name");
 		$query->innerJoin(TABLE_DIPLOMS." as diplom", "diplom.student_id = preview.student_id");
 		$query->innerJoin(TABLE_PERSON." as person", "diplom.recenz_id = person.id")
 		->from(TABLE_DIPLOM_PREVIEWS." as preview")
 		->condition("person.fio like '%".$term."%'")
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 4
 			);
 		}
 		/**
 		 * Поиск по комментарию
 		 */
 		$query = new CQuery();
 		$query->select("distinct(preview.id) as id, preview.comment as name")
 		->from(TABLE_DIPLOM_PREVIEWS." as preview")
 		->condition("preview.comment like '%".$term."%'")
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 5
 			);
 		}
    	echo json_encode($res);
    }
}