<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 24.02.13
 * Time: 17:19
 * To change this template use File | Settings | File Templates.
 */
class CDiplomsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Темы ВКР студентов");

        parent::__construct();
    }
    public function actionIndex() {
    	$set = new CRecordSet(false);
        $query = new CQuery();
        $currentPerson = null;
        $currentGroup = null;
        $query->select("diplom.*")
            ->from(TABLE_DIPLOMS." as diplom")
			->order("diplom.date_act desc");
        $managersQuery = new CQuery();
        $managersQuery->select("person.*")
        	->from(TABLE_PERSON." as person")
        	->order("person.fio asc")
        	->innerJoin(TABLE_DIPLOMS." as diplom", "person.id = diplom.kadri_id");
        $groupsQuery = new CQuery();
        $groupsQuery->select("stgroup.*")
        	->from(TABLE_STUDENT_GROUPS." as stgroup")
        	->order("stgroup.name asc")
        	->innerJoin(TABLE_STUDENTS." as student", "stgroup.id = student.group_id")
        	->innerJoin(TABLE_DIPLOMS." as diplom", "student.id =  diplom.student_id");
        $set->setQuery($query);
        $isApprove = (CRequest::getString("isApprove") == "1");
        $isArchive = (CRequest::getString("isArchive") == "1");
        if (!$isArchive) {
        	$query->condition('diplom.date_act between "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_start)).'" and "'.date("Y-m-d", strtotime(CUtils::getCurrentYear()->date_end)).'"');
        }
        if (CRequest::getString("order") == "st_group.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
        		$query->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id");
        		$query->order("st_group.name ".$direction);
        }
        elseif (CRequest::getString("order") == "dipl_prew.date_preview") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
        		$query->leftJoin(TABLE_DIPLOM_PREVIEWS." as dipl_prew", "student.id = dipl_prew.student_id");
        		$query->order("dipl_prew.date_preview ".$direction);
        }
        elseif (CRequest::getString("order") == "prepod.fio") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_PERSON." as prepod", "diplom.kadri_id = prepod.id");
        		$query->order("prepod.fio ".$direction);
        }        
        elseif (CRequest::getString("order") == "student.fio") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
        		$query->order("student.fio ".$direction);
        }
        elseif (CRequest::getString("order") == "diplom_confirm") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("diplom_confirm ".$direction);
        }
        elseif (CRequest::getString("order") == "dipl_name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("dipl_name ".$direction);
        }
        elseif (CRequest::getString("order") == "pract_place_id") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("pract_place_id ".$direction);
        }
        elseif (CRequest::getString("order") == "date_act") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("date_act ".$direction);
        }
        elseif (CRequest::getString("order") == "foreign_lang") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("foreign_lang ".$direction);
        }
        elseif (CRequest::getString("order") == "protocol_2aspir_id") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("protocol_2aspir_id ".$direction);
        }
        elseif (CRequest::getString("order") == "recenz_id") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("recenz_id ".$direction);
        }
        elseif (CRequest::getString("order") == "study_mark") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("study_mark ".$direction);
        }
        elseif (CRequest::getString("order") == "gak_num") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("gak_num ".$direction);
        }
        elseif (CRequest::getString("order") == "comment") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->order("comment ".$direction);
        }
        // фильтр по руководителю
        if (!is_null(CRequest::getFilter("person"))) {
        	$query->innerJoin(TABLE_PERSON." as person", "diplom.kadri_id = person.id and person.id = ".CRequest::getFilter("person"));
        	$currentPerson = CRequest::getFilter("person");
        	// фильтруем еще и группы
        	$groupsQuery->innerJoin(TABLE_PERSON." as person", "diplom.kadri_id = person.id and person.id = ".CRequest::getFilter("person"));
        }
        // фильтр по группе
        if (!is_null(CRequest::getFilter("group"))) {
        	$currentGroup = CRequest::getFilter("group");
        	$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
        	$query->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id and st_group.id = ".CRequest::getFilter("group"));
        	$managersQuery->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id = student.id");
        	$managersQuery->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id and st_group.id = ".CRequest::getFilter("group"));
        }
        // фильтр по теме
        if (!is_null(CRequest::getFilter("theme"))) {
        	$query->condition("diplom.id = ".CRequest::getFilter("theme"));	
        }
        // фильтр по студенту
        if (!is_null(CRequest::getFilter("student"))) {
        	$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id and student.id = ".CRequest::getFilter("student"));
        }
        // фильтр по степени утверждения
        if (!is_null(CRequest::getFilter("confirm"))) {
        	$query->innerJoin(TABLE_DIPLOM_CONFIRMATIONS." as confirm", "diplom.diplom_confirm = confirm.id and confirm.id = ".CRequest::getFilter("confirm"));
        }
        // фильтр по месту практики
        if (!is_null(CRequest::getFilter("pract"))) {
        	$query->condition("diplom.id = ".CRequest::getFilter("pract"));
        }
        // фильтр по месту практики по id
        if (!is_null(CRequest::getFilter("practId"))) {
        	$query->innerJoin(TABLE_PRACTICE_PLACES." as pract", "diplom.pract_place_id = pract.id and pract.id = ".CRequest::getFilter("practId"));
        }
        // фильтр по ин.яз.
        if (!is_null(CRequest::getFilter("foreign"))) {
        	$query->innerJoin(TABLE_LANGUAGES." as lang", "diplom.foreign_lang=lang.id and lang.id = ".CRequest::getFilter("foreign"));
        }
        // фильтр по рецензенту
        if (!is_null(CRequest::getFilter("recenz"))) {
        	$query->innerJoin(TABLE_PERSON." as person", "diplom.recenz_id = person.id and person.id = ".CRequest::getFilter("recenz"));
        }
        // фильтр по оценке
        if (!is_null(CRequest::getFilter("mark"))) {
        	$query->innerJoin(TABLE_MARKS." as mark", "diplom.study_mark = mark.id and mark.id = ".CRequest::getFilter("mark"));
        }
        // фильтр по комментарию
        if (!is_null(CRequest::getFilter("comment"))) {
        	$query->condition("diplom.id = ".CRequest::getFilter("comment"));
        }
        // получение дипломных тем
        $diploms = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $diplom = new CDiplom($item);
            $diploms->add($diplom->getId(), $diplom);
        }  
		/**
		 * Формируем меню
		 */
		$this->addActionsMenuItem(array(
            array(
                "title" => "Печать по шаблону",
                "link" => "#",
                "icon" => "devices/printer.png",
                "template" => "formset_diploms_theme"
            ),
			array(
                "title" => "Добавить тему ВКР",
                "link" => "?action=add",
                "icon" => "actions/list-add.png"
            ),
			array(
				"title" => "Список студентов",
				"link" => WEB_ROOT."_modules/_students/",
				"icon" => "apps/system-users.png"
			)
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
		$this->addActionsMenuItem(array(
			array(
				"title" => "Утверждение темы",
				"link" => "#",
				"icon" => "apps/accessories-text-editor.png",
				"child" => array(
					array(
							"title" => "Утвердили полностью",
							"icon" => "actions/edit-find-replace.png",
							"form" => "#MainView",
							"link" => "index.php?type=1",
							"action" => "approveTheme"
					),
					array(
							"title" => "Утвердили c правкой",
							"icon" => "actions/edit-find-replace.png",
							"form" => "#MainView",
							"link" => "index.php?type=2",
							"action" => "approveTheme"
					),
					array(
							"title" => "Утвердили c переформулировкой",
							"icon" => "actions/edit-find-replace.png",
							"form" => "#MainView",
							"link" => "index.php?type=3",
							"action" => "approveTheme"
					),
					array(
							"title" => "Не утвердили, но смотрели",
							"icon" => "actions/edit-find-replace.png",
							"form" => "#MainView",
							"link" => "index.php?type=4",
							"action" => "approveTheme"
					),
					array(
							"title" => "Отменить утверждение темы",
							"icon" => "actions/edit-find-replace.png",
							"form" => "#MainView",
							"link" => "index.php?type=0",
							"action" => "approveTheme"
					)
				)
			)
		));
		$managers = array();
		foreach ($managersQuery->execute()->getItems() as $ar) {
			$person = new CPerson(new CActiveRecord($ar));
			$managers[$person->getId()] = $person->getName();
		}
		$studentGroups = array();
		foreach ($groupsQuery->execute()->getItems() as $ar) {
			$group = new CStudentGroup(new CActiveRecord($ar));
			$studentGroups[$group->getId()] = $group->getName();
		}
		$this->setData("isArchive", $isArchive);
		$this->setData("isApprove", $isApprove);
		$this->setData("studentGroups", $studentGroups);
		$this->setData("diplomManagers", $managers);
        $this->setData("currentPerson", $currentPerson);
        $this->setData("currentGroup", $currentGroup);
        $this->setData("diploms", $diploms);
        $this->setData("paginator", $set->getPaginator());
		if (!$isApprove) {
			$requestParams = array();
			foreach (CRequest::getGlobalRequestVariables()->getItems() as $key=>$value) {
				$requestParams[] = $key."=".$value;
			}
			$requestParams[] = "isApprove=1";
			$this->addActionsMenuItem(array(
					array(
							"title" => "Утверждение тем ВКР",
							"link" => "?".implode("&", $requestParams),
							"icon" => "actions/bookmark-new.png"
					),
			));
			$this->renderView("_diploms/index.tpl");
		} else {
			$requestParams = array();
			foreach (CRequest::getGlobalRequestVariables()->getItems() as $key=>$value) {
				if ($key != "isApprove") {
					$requestParams[] = $key."=".$value;
				}
			}
			$this->addActionsMenuItem(array(
					array(
							"title" => "Список тем ВКР",
							"link" => "?".implode("&", $requestParams),
							"icon" => "actions/format-justify-center.png"
					),
			));
			$this->renderView("_diploms/approve.tpl");
		}
    }
    public function actionApproveTheme() {
    	$type = CRequest::getInt("type");
    	foreach (CRequest::getArray("selectedDoc") as $id) {
    		$diplom = CStaffManager::getDiplom($id);
    		if (!is_null($diplom)) {
    			$diplom->diplom_confirm = $type;
    			$diplom->save();
    		}
    	}
    	$this->redirect("?action=index");
    }
    public function actionAdd() {
        $diplom = new CDiplom();
        $this->setData("diplom", $diplom);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => WEB_ROOT."_modules/_diploms/",
                "icon" => "actions/edit-undo.png"
            )
        ));		
        $this->renderView("_diploms/add.tpl");
    }
    public function actionEdit() {
        $diplom = CStaffManager::getDiplom(CRequest::getInt("id"));
        // сконвертим дату из MySQL date в нормальную дату
        $diplom->date_act = date("d.m.Y", strtotime($diplom->date_act));
        $this->setData("diplom", $diplom);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => WEB_ROOT."_modules/_diploms/",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Печать по шаблону",
                "link" => "#",
                "icon" => "devices/printer.png",
                "template" => "formset_diploms"
            ),
			array(
				"title" => "Добавить предзащиту",
				"link" => WEB_ROOT."_modules/_diploms/preview.php?action=add&id=".$diplom->getId(),
				"icon" => "actions/list-add.png"
			)
        ));	
        $this->renderView("_diploms/edit.tpl");
    }
    public function actionDelete() {
    	$diplom = CStaffManager::getDiplom(CRequest::getInt("id"));
    	$diplom->remove();
    	$this->redirect("?action=index");
    }
    public function actionSave() {
        $diplom = new CDiplom();
        $diplom->setAttributes(CRequest::getArray($diplom::getClassName()));
        $oldDate = $diplom->date_act;
        if ($diplom->validate()) {
            // дату нужно сконвертить в MySQL date
            $diplom->date_act = date("Y-m-d", strtotime($diplom->date_act));
            $diplom->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$diplom->getId());
            } else {
                $this->redirect(WEB_ROOT."_modules/_diploms/");
            }
            //$this->redirect("?action=index");
            return true;
        }
        // сконвертим дату из MySQL date в нормальную дату
        $diplom->date_act = date("d.m.Y", strtotime($diplom->date_act));
        $commissions = array();
        foreach (CSABManager::getCommissionsList() as $id=>$c) {
            $commission = CSABManager::getCommission($id);
            $nv = $commission->title;
            if (!is_null($commission->manager)) {
                $nv .= " ".$commission->manager->getName();
            }
            if (!is_null($commission->secretar)) {
                $nv .= " (".$commission->secretar->getName().")";
            }
            $cnt = 0;
            foreach ($commission->diploms->getItems() as $d) {
                if (strtotime($diplom->date_act) == strtotime($d->date_act)) {
                    $cnt++;
                }
            }
            $nv .= " ".$cnt;
            $commissions[$commission->getId()] = $nv;
        }
        if (!array_key_exists($diplom->gak_num, $commissions)) {
            $diplom->gak_num = null;
        }
        $reviewers = CStaffManager::getPersonsListWithType(TYPE_REVIEWER);
        if (!array_key_exists($diplom->recenz_id, $reviewers)) {
            $reviewer = CStaffManager::getPerson($diplom->recenz_id);
            if (!is_null($reviewer)) {
                $reviewers[$reviewer->getId()] = $reviewer->getName();
            }
        }
        $this->setData("reviewers", $reviewers);
        $this->setData("commissions", $commissions);
        $this->setData("diplom", $diplom);
        $this->renderView("_diploms/edit.tpl");
    }
    public function actionGetAverageMark() {
    	$mark = 0;
    	$diplom = CStaffManager::getDiplom(CRequest::getInt("id"));
    	if (!is_null($diplom)) {
            $precise = 2;
            if (CRequest::getInt("p") != 0) {
                $precise = CRequest::getInt("p");
            }
    		$mark = $diplom->getAverageMarkComputed($precise);
    	}
    	if ($mark !== 0) {
    		echo $mark;
    	}
    }
 	public function actionSearch() {
 		$term = CRequest::getString("query");
 		$res = array();
 		/**
 		 * Поиск по теме диплома
 		 */
 		$query = new CQuery();
 		$query->select("distinct(diplom.id) as id, diplom.dipl_name as name")
 		->from(TABLE_DIPLOMS." as diplom")
 		->condition("diplom.dipl_name like '%".$term."%'")
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
 		 * Поиск по ФИО студента
 		 */
 		$query = new CQuery();
 		$query->select("distinct(diplom.student_id) as id, student.fio as name");
 		$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id")
 		->from(TABLE_DIPLOMS." as diplom")
 		->condition("student.fio like '%".$term."%'")
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
 		 * Поиск по степени утверждения диплома
 		 */
 		$query = new CQuery();
 		$query->select("distinct(diplom.diplom_confirm) as id, confirm.name as name");
 		$query->innerJoin(TABLE_DIPLOM_CONFIRMATIONS." as confirm", "diplom.diplom_confirm = confirm.id")
 		->from(TABLE_DIPLOMS." as diplom")
 		->condition("confirm.name like '%".$term."%'")
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
 		 * Поиск по месту практики
 		 */
 		$query = new CQuery();
 		$query->select("distinct(diplom.id) as id, diplom.pract_place as name")
 		->from(TABLE_DIPLOMS." as diplom")
 		->condition("diplom.pract_place like '%".$term."%'")
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
 		 * Поиск по месту практики по id
 		 */
 		$query = new CQuery();
 		$query->select("distinct(diplom.pract_place_id) as id, pract.name as name");
 		$query->innerJoin(TABLE_PRACTICE_PLACES." as pract", "diplom.pract_place_id = pract.id")
 		->from(TABLE_DIPLOMS." as diplom")
 		->condition("pract.name like '%".$term."%'")
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 5
 			);
 		}
 		/**
 		 * Поиск по руководителю
 		 */
 		$query = new CQuery();
 		$query->select("distinct(diplom.kadri_id) as id, person.fio as name")
 		->from(TABLE_DIPLOMS." as diplom")
 		->innerJoin(TABLE_PERSON." as person", "diplom.kadri_id = person.id")
 		->condition("person.fio like '%".$term."%'")		
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 6
 			);
 		}
 		/**
 		 * Поиск по группе
 		 */
 		$query = new CQuery();
 		$query->select("distinct(student.group_id) as id, st_group.name as name");
 		$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
 		$query->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id")
 		->from(TABLE_DIPLOMS." as diplom")
 		->condition("st_group.name like '%".$term."%'")
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 7
 			);
 		}
 		/**
 		 * Поиск по ин.яз.
 		 */
 		$query = new CQuery();
 		$query->select("distinct(diplom.foreign_lang) as id, lang.name as name");
 		$query->innerJoin(TABLE_LANGUAGES." as lang", "diplom.foreign_lang=lang.id")
 		->from(TABLE_DIPLOMS." as diplom")
 		->condition("lang.name like '%".$term."%'")
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 8
 			);
 		}
 		/**
 		 * Поиск по рецензенту
 		 */
 		$query = new CQuery();
 		$query->select("distinct(diplom.recenz_id) as id, prepod.fio as name");
 		$query->innerJoin(TABLE_PERSON." as prepod", "diplom.recenz_id = prepod.id")
 		->from(TABLE_DIPLOMS." as diplom")
 		->condition("prepod.fio like '%".$term."%'")
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 9
 			);
 		}
 		/**
 		 * Поиск по оценке
 		 */
 		$query = new CQuery();
 		$query->select("distinct(diplom.study_mark) as id, mark.name as name");
 		$query->innerJoin(TABLE_MARKS." as mark", "diplom.study_mark = mark.id")
 		->from(TABLE_DIPLOMS." as diplom")
 		->condition("mark.name like '%".$term."%'")
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 10
 			);
 		}
 		/**
 		 * Поиск по комментарию
 		 */
 		$query = new CQuery();
 		$query->select("distinct(diplom.id) as id, diplom.comment as name")
 		->from(TABLE_DIPLOMS." as diplom")
 		->condition("diplom.comment like '%".$term."%'")
 		->limit(0, 5);
 		foreach ($query->execute()->getItems() as $item) {
 			$res[] = array(
 					"label" => $item["name"],
 					"value" => $item["name"],
 					"object_id" => $item["id"],
 					"type" => 11
 			);
 		}
 		/**
 		 * Поиск по дате предзащиты
 		 */
 		/*$query = new CQuery();
		$query->select("distinct(dipl_prew.id) as id, dipl_prew.date_preview as name");
		$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
		$query->innerJoin(TABLE_DIPLOM_PREVIEWS." as dipl_prew", "student.id = dipl_prew.student_id")
		->from(TABLE_DIPLOMS." as diplom")
		->condition("dipl_prew.date_preview like '%".$term."%'")
		->limit(0, 5);
		foreach ($query->execute()->getItems() as $item) {
			$res[] = array(
					"label" => $item["name"],
					"value" => $item["name"],
					"object_id" => $item["id"],
					"type" => 12
			);
		}
 		/**
 		 * Поиск по дате защиты
 		 */
		/*$query = new CQuery();
		$query->select("distinct(diplom.id) as id, diplom.date_act as name")
		->from(TABLE_DIPLOMS." as diplom")
		->condition("diplom.date_act like '%".$term."%'")
		->limit(0, 5);
		foreach ($query->execute()->getItems() as $item) {
		$res[] = array(
				"label" => $item["name"],
				"value" => $item["name"],
				"object_id" => $item["id"],
				"type" => 13
 		 	);
		}*/
		echo json_encode($res);
    }
    public function actionUpdateThemeApprove() {
    	$diplom = CStaffManager::getDiplom(CRequest::getInt("id"));
    	$result = array(
    			"title" => "не рассматривали",
    			"color" => "white"
    	);
    	// меняем на следующий статус утверждения
    	$diplom->diplom_confirm += 1;
    	if (is_null($diplom->confirmation)) {
    		$diplom->diplom_confirm = 0;
    	} else {
    		$result["title"] = $diplom->confirmation->getValue();
    		$result["color"] = $diplom->confirmation->color_mark;
    	}
    	$diplom->save();
    	echo json_encode($result);
    }
}