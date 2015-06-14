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
					array(
							"title" => "Добавить",
							"link" => "?action=addPreview",
							"icon" => "actions/list-add.png"
							),
					array(
							"title" => "Статистика",
							"link" => "?action=statistic",
							"icon" => "mimetypes/x-office-spreadsheet.png"
			    			),
    		)
    	);
    	//Предзащиты зимой
    	if (CRequest::getInt("winterPreviews")==1) {
    		$query->condition('preview.date_preview between "'.(date("Y")-1)."-12-01".'" and "'.(date("Y"))."-02-28".'"');
    	}
    	//Предзащиты летом
    	if (CRequest::getInt("summerPreviews")==1) {
    		$query->condition('preview.date_preview between "'.(date("Y"))."-05-01".'" and "'.(date("Y"))."-06-30".'"');
    	}
    	//Прошедшие предзащиту зимой
    	if (CRequest::getInt("winterCompletePreviews")==1) {
    		$query->condition('preview.date_preview between "'.(date("Y")-1)."-12-01".'" and "'.(date("Y"))."-02-28".'" and (preview.diplom_percent!=0 and preview.another_view=0)');
    	}
    	//Прошедшие предзащиту летом
    	if (CRequest::getInt("summerCompletePreviews")==1) {
    		$query->condition('preview.date_preview between "'.(date("Y"))."-05-01".'" and "'.(date("Y"))."-06-30".'" and (preview.diplom_percent!=0 and preview.another_view=0)');
    	}
    	//Не прошедшие предзащиту зимой
    	if (CRequest::getInt("winterNotComplete")==1) {
    		$query->condition('preview.date_preview between "'.(date("Y")-1)."-12-01".'" and "'.(date("Y"))."-02-28".'" and (preview.diplom_percent=0 or preview.another_view!=0)');
    	}
    	//Не прошедшие предзащиту летом
    	if (CRequest::getInt("summerNotComplete")==1) {
    		$query->condition('preview.date_preview between "'.(date("Y"))."-05-01".'" and "'.(date("Y"))."-06-30".'" and (preview.diplom_percent=0 or preview.another_view!=0)');
    	}
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
    	$date_previews = array();
    	$date_previews = array_count_values($prevs);
    	
    	//Общее количество предзащит
    	$count_previews = count($prevs);
    	
    	//Общее количество защит
    	$queryAll = new CQuery();
    	$queryAll->select("diplom.*")
    	->from(TABLE_DIPLOMS." as diplom");
    	if (!$isArchive) {
    		$queryAll->condition('diplom.date_act between "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" and "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_end)).'"');
    	}
    	$count_all = $queryAll->execute()->getCount();
    	
    	//Всего защит зимой
    	$queryWinterAll = new CQuery();
    	$queryWinterAll->select("diplom.*")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition('diplom.date_act between "'.(date("Y")-1)."-12-01".'" and "'.(date("Y"))."-02-28".'"');
    	$count_winter_all = $queryWinterAll->execute()->getCount();
    	
    	//Всего защит летом
    	$querySummerAll = new CQuery();
    	$querySummerAll->select("diplom.*")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition('diplom.date_act between "'.(date("Y"))."-05-01".'" and "'.(date("Y"))."-06-30".'"');
    	$count_summer_all = $querySummerAll->execute()->getCount();
    	
    	//Из них не имеющие предзащиты зимой
    	$queryNotPreviewsWinter = new CQuery();
    	$queryNotPreviewsWinter->select("diplom.*")
    	->from(TABLE_DIPLOMS." as diplom")
    	->leftJoin(TABLE_DIPLOM_PREVIEWS." as preview", "diplom.student_id = preview.student_id")
    	->condition('preview.student_id is null and diplom.date_act between "'.(date("Y")-1)."-12-01".'" and "'.(date("Y"))."-02-28".'"');
    	$count_not_previews_winter = $queryNotPreviewsWinter->execute()->getCount();
    	
    	//Из них не имеющие предзащиты летом
    	$queryNotPreviewsSummer = new CQuery();
    	$queryNotPreviewsSummer->select("diplom.*")
    	->from(TABLE_DIPLOMS." as diplom")
    	->leftJoin(TABLE_DIPLOM_PREVIEWS." as preview", "diplom.student_id = preview.student_id")
    	->condition('preview.student_id is null and diplom.date_act between "'.(date("Y"))."-05-01".'" and "'.(date("Y"))."-06-30".'"');
    	$count_not_previews_summer = $queryNotPreviewsSummer->execute()->getCount();
    	
    	//Количество предзащит зимой
    	$queryWinter = new CQuery();
    	$queryWinter->select("preview.*")
    	->from(TABLE_DIPLOM_PREVIEWS." as preview")
    	->condition('preview.date_preview between "'.(date("Y")-1)."-12-01".'" and "'.(date("Y"))."-02-28".'"');
    	$count_previews_winter = $queryWinter->execute()->getCount();
    	
    	//Количество предзащит летом
    	$querySummer = new CQuery();
    	$querySummer->select("preview.*")
    	->from(TABLE_DIPLOM_PREVIEWS." as preview")
    	->condition('preview.date_preview between "'.(date("Y"))."-05-01".'" and "'.(date("Y"))."-06-30".'"');
    	$count_previews_summer = $querySummer->execute()->getCount();
    	
    	//Прошедшие предзащиту зимой
    	$queryWinterComplete = new CQuery();
    	$queryWinterComplete->select("preview.*")
    	->from(TABLE_DIPLOM_PREVIEWS." as preview")
    	->condition('preview.date_preview between "'.(date("Y")-1)."-12-01".'" and "'.(date("Y"))."-02-28".'" and (preview.diplom_percent!=0 and preview.another_view=0)');
    	$count_previews_winter_complete = $queryWinterComplete->execute()->getCount();
    	
    	//Прошедшие предзащиту летом
    	$querySummerComplete = new CQuery();
    	$querySummerComplete->select("preview.*")
    	->from(TABLE_DIPLOM_PREVIEWS." as preview")
    	->condition('preview.date_preview between "'.(date("Y"))."-05-01".'" and "'.(date("Y"))."-06-30".'" and (preview.diplom_percent!=0 and preview.another_view=0)');
    	$count_previews_summer_complete = $querySummerComplete->execute()->getCount();
    	
    	//Не прошедшие предзащиту зимой
    	$queryWinterNotComplete = new CQuery();
    	$queryWinterNotComplete->select("preview.*")
    	->from(TABLE_DIPLOM_PREVIEWS." as preview")
    	->condition('preview.date_preview between "'.(date("Y")-1)."-12-01".'" and "'.(date("Y"))."-02-28".'" and (preview.diplom_percent=0 or preview.another_view!=0)');
    	$count_previews_winter_not_complete = $queryWinterNotComplete->execute()->getCount();
    	
    	//Не прошедшие предзащиту летом
    	$querySummerNotComplete = new CQuery();
    	$querySummerNotComplete->select("preview.*")
    	->from(TABLE_DIPLOM_PREVIEWS." as preview")
    	->condition('preview.date_preview between "'.(date("Y"))."-05-01".'" and "'.(date("Y"))."-06-30".'" and (preview.diplom_percent=0 or preview.another_view!=0)');
    	$count_previews_summer_not_complete = $querySummerNotComplete->execute()->getCount();
    	
        $this->setData("date_previews", $date_previews);
        $this->setData("count_all", $count_all);
        $this->setData("count_previews", $count_previews);
        $this->setData("count_winter_all", $count_winter_all);
        $this->setData("count_summer_all", $count_summer_all);
        $this->setData("count_not_previews_winter", $count_not_previews_winter);
        $this->setData("count_not_previews_summer", $count_not_previews_summer);
        $this->setData("count_previews_winter", $count_previews_winter);
        $this->setData("count_previews_summer", $count_previews_summer);
        $this->setData("count_previews_winter_complete", $count_previews_winter_complete);
        $this->setData("count_previews_winter_not_complete", $count_previews_winter_not_complete);
        $this->setData("count_previews_summer_complete", $count_previews_summer_complete);
        $this->setData("count_previews_summer_not_complete", $count_previews_summer_not_complete);
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