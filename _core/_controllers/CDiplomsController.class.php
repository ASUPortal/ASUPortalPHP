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
        $this->setPageTitle("Дипломные темы студентов");

        parent::__construct();
    }
    public function actionIndex() {
    	$set = new CRecordSet();
        $query = new CQuery();
        $query->select("diplom.*")
        ->from(TABLE_DIPLOMS." as diplom")
         ->order("diplom.dipl_name asc");
        $set->setQuery($query);
        
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
        		$query->innerJoin(TABLE_DIPLOM_PREVIEWS." as dipl_prew", "student.id = dipl_prew.student_id");
        		$query->order("dipl_prew.date_preview ".$direction);
        }
        elseif (CRequest::getString("order") == "prepod.fio") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_PERSON." as prepod", "diplom.kadri_id = prepod.id");
        		$query->order("prepod.fio ".$direction);
        }        
        $diploms = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $diplom = new CDiplom($item);
            $diploms->add($diplom->getId(), $diplom);
        }  
        // запрос для фильтра по руководителю
		$queryPerson = new CQuery();
		$queryPerson->select("diplom.*")
		->from(TABLE_DIPLOMS." as diplom")
		->order("diplom.kadri_id asc");
		// фильтр
		$selectedPerson = null;
		// фильтр по руководителю
		if (!is_null(CRequest::getFilter("kadri_id"))) {
			$query->innerJoin(TABLE_PERSON." as prepod", "diplom.kadri_id = prepod.id".CRequest::getFilter("kadri_id"));
			$selectedPerson = CRequest::getFilter("kadri_id");
		}
		// параметр фильтра
		$groups = array();
		foreach ($queryPerson->execute()->getItems() as $item) {
			$groups[$item["id"]] = $item["kadri_id"];
		}
		/**
		 * Формируем меню
		 */
		$this->addActionsMenuItem(array(
			array(
                "title" => "Добавить дипломную тему",
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
			$this->addActionsMenuItem(array(
					array(
							"title" => "Текущий год",
							"link" => "?action=index",
							"icon" => "mimetypes/x-office-calendar.png"
					),				
			));
		} else {
			$this->addActionsMenuItem(array(
					array(
							"title" => "Архив",
							"link" => "?action=index&isArchive=1",
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
		$this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);      
        $this->setData("diploms", $diploms);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_diploms/index.tpl");
    }
    public function actionApproveTheme() {
    	$type = CRequest::getInt("type");
    	foreach (CRequest::getArray("selectedInView") as $id) {
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
            )
        ));	
        $this->renderView("_diploms/edit.tpl");
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
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по теме диплома
    	*/
    	$query = new CQuery();
    	$query->select("distinct(diplom.id) as id, diplom.dipl_name as title")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("diplom.dipl_name like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	* Поиск по ФИО студента
    	*/
    	$query = new CQuery();
    	$query->select("distinct(diplom.student_id) as id, student.fio as title");
    	$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("student.fio like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "student_id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	 * Поиск по степени утверждения диплома
    	*/
    	$query = new CQuery();
    	$query->select("distinct(diplom.diplom_confirm) as id, confirm.name as title");
    	$query->innerJoin(TABLE_DIPLOM_CONFIRMATIONS." as confirm", "diplom.diplom_confirm = confirm.id")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("confirm.name like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "diplom_confirm",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}  	
    	/**
    	* Поиск по месту практики
    	*/
    	$query = new CQuery();
    	$query->select("distinct(diplom.id) as id, diplom.pract_place as title")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("diplom.pract_place like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	* Поиск по месту практики
    	*/
    	$query = new CQuery();
    	$query->select("distinct(diplom.pract_place_id) as id, pract.name as title");
    	$query->innerJoin(TABLE_PRACTICE_PLACES." as pract", "diplom.pract_place_id = pract.id")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("pract.name like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "pract_place_id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	* Поиск по преподавателю
    	*/
		$query = new CQuery();
    	$query->select("distinct(diplom.kadri_id) as id, prepod.fio as title");
    	$query->innerJoin(TABLE_PERSON." as prepod", "diplom.kadri_id = prepod.id")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("prepod.fio like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "kadri_id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	* Поиск по группе
    	*/
    	$query = new CQuery();
    	$query->select("distinct(student.group_id) as id, st_group.name as title");
    	$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
    	$query->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("st_group.name like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "student.group_id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	* Поиск по дате предзащиты
    	*/
    	/*$query = new CQuery();
    	$query->select("distinct(dipl_prew.id) as id, dipl_prew.date_preview as title");
    	$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
        $query->innerJoin(TABLE_DIPLOM_PREVIEWS." as dipl_prew", "student.id = dipl_prew.student_id")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("dipl_prew.date_preview like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "dipl_prew.id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}*/
    	/**
    	* Поиск по дате защиты
    	*/
    	/*$query = new CQuery();
    	$query->select("distinct(diplom.id) as id, diplom.date_act as title")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("diplom.date_act like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}*/
    	/**
    	* Поиск по ин.яз.
    	*/
		$query = new CQuery();
    	$query->select("distinct(diplom.foreign_lang) as id, lang.name as title");
    	$query->innerJoin(TABLE_LANGUAGES." as lang", "diplom.foreign_lang=lang.id")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("lang.name like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "foreign_lang",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	* Поиск по рецензенту
    	*/
    	$query = new CQuery();
    	$query->select("distinct(diplom.recenz_id) as id, prepod.fio as title");
    	$query->innerJoin(TABLE_PERSON." as prepod", "diplom.recenz_id = prepod.id")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("prepod.fio like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "recenz_id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	* Поиск по оценке
    	*/
    	$query = new CQuery();
    	$query->select("distinct(diplom.study_mark) as id, mark.name as title");
    	$query->innerJoin(TABLE_MARKS." as mark", "diplom.study_mark = mark.id")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("mark.name like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "study_mark",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	* Поиск по комментарию
    	*/
    	$query = new CQuery();
    	$query->select("distinct(diplom.id) as id, diplom.comment as title")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("diplom.comment like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	echo json_encode($res);
    }  
}
