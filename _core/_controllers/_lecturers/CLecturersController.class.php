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
            ->condition("user.status!='администратор'")
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
        ->condition("user.status='преподаватель'")
        ->group(1)
        ->order("user.FIO asc");  
        $resRus = array();
        foreach ($queryLetter->execute()->getItems() as $ar) {
        	$res = new CUser(new CActiveRecord($ar));
        	$resRus[$res->id] = $res->name;
        }
        $resRusLetters = array();
        $resRusLetters = array_count_values($resRus);
        if (isset($_GET['getsub']) and !isset($_GET['filter'])) {
        	$query->condition('user.FIO like "'.$letter.'%" and user.status!="администратор"');
        }
        if (CSession::isAuth() and CSession::getCurrentUser()->status=='преподаватель') {
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
    	$this->setData("lect", $lect);
    	$this->addActionsMenuItem(array(
			array(
				"title" => "Назад",
				"link" => WEB_ROOT."_modules/_lecturers/index.php",
				"icon" => "actions/edit-undo.png"
			)
		));
    	
    	//Биография  
    	$setBiog = new CRecordSet();
    	$queryBiog = new CQuery();
    	$queryBiog->select("biog.*")
    	->from(TABLE_BIOGRAPHY." as biog")
    	->condition("biog.user_id=".CRequest::getInt("id"));
    	$biogs = new CArrayList();
    	$setBiog->setQuery($queryBiog);
    	foreach ($setBiog->getItems() as $ar) {
    		$biog = new CBiography($ar);
    		$biogs->add($ar->getId(), $biog);
    	}
    	$this->setData("biogs", $biogs);
    	foreach ($biogs->getItems() as $biog) {
    		$photo_biog=$biog->image;
    	}
    	
    	$setKadri = new CRecordSet();
    	$queryKadri = new CQuery();
    	$queryKadri->select("person.*")
    	->from(TABLE_PERSON." as person")
    	->innerJoin(TABLE_USERS." as users", "users.kadri_id=person.id")
    	->condition("users.id=".CRequest::getInt("id"));
    	$persons = new CArrayList();
    	$setKadri->setQuery($queryKadri);
    	foreach ($setKadri->getItems() as $ar) {
    		$person = new CPerson($ar);
    		$persons->add($ar->getId(), $person);
    	}
    	foreach ($persons->getItems() as $person) {
    		$photo_kadri=$person->photo;
    	}
    	
    	if ($biogs->getCount() != 0) {
    		if ($photo_biog!="") {
    			$filename = CORE_CWD.CORE_DS."images".CORE_DS."lects".CORE_DS."small".CORE_DS."sm_".$photo_biog;
    			if (file_exists($filename)) {
    				$pathPhoto = '<img src="'.WEB_ROOT.'images/lects/small/sm_'.$photo_biog.'" border="0" align="left" hspace="10" vspace="0" title="фото из биографии">';
    			}
    			else {
    				$pathPhoto = '<img src="'.WEB_ROOT.'_modules/_thumbnails/?src=images/lects/'.$photo_biog.'" border="0" align="left" hspace="10" vspace="0" title="фото из биографии">';
    			}
    		} elseif ($photo_kadri!="") {
    			$filename = CORE_CWD.CORE_DS."images".CORE_DS."lects".CORE_DS."small".CORE_DS."sm_".$photo_kadri;
    			if (file_exists($filename)) {
    				$pathPhoto = '<img src="'.WEB_ROOT.'images/lects/small/sm_'.$photo_kadri.'" border="0" align="left" hspace="10" vspace="0" title="фото из анкеты">';
    			}
    			else {
    				$pathPhoto = '<img src="'.WEB_ROOT.'_modules/_thumbnails/?src=images/lects/'.$photo_kadri.'" border="0" align="left" hspace="10" vspace="0" title="фото из анкеты">';
    			}
    		}
    		$this->setData("pathPhoto", $pathPhoto);
    	}
    	
    	//Веб-страницы на портале
    	$setPage = new CRecordSet();
    	$resPage = new CQuery();
    	$resPage->select("page.*")
    	->from(TABLE_PAGES." as page")
    	->condition("page.user_id_insert=".CRequest::getInt("id")." and page.type_id<>1");
    	$pages = new CArrayList();
    	$setPage->setQuery($resPage);
    	foreach ($setPage->getItems() as $ar) {
    		$page = new CPage($ar);
    		$pages->add($ar->getId(), $page);
    	}
    	$this->setData("pages", $pages);
    	
    	//Список пособий на портале 	
    	$setSubj = new CRecordSet();
    	$resSubj = new CQuery();
    	$resSubj->select("subj.*, doc.nameFolder as nameFolder, (select count(*) from files f where f.nameFolder = doc.nameFolder) as f_cnt")
    	->from(TABLE_DISCIPLINES." as subj")
    	->leftJoin(TABLE_LIBRARY_DOCUMENTS." as doc", "subj.id = doc.subj_id")
    	->condition("doc.user_id=".CRequest::getInt("id"));
    	$subjects = new CArrayList();
    	$setSubj->setQuery($resSubj);
    	foreach ($setSubj->getItems() as $ar) {
    		$subject = new CDiscipline($ar);
    		$subjects->add($ar->getId(), $subject);
    	}
    	$this->setData("subjects", $subjects);
    	
    	//Объявления текущего учебного года
    	$setNews = new CRecordSet();
    	$resNews = new CQuery();
    	$resNews->select("news.*")
    	->from(TABLE_NEWS." as news")
    	->condition('news.user_id_insert="'.CRequest::getInt("id").'" and news.date_time>="'.CUtils::getCurrentYear()->date_start.'"')
    	->order("news.date_time desc");
    	$news = new CArrayList();
    	$setNews->setQuery($resNews);
    	foreach ($setNews->getItems() as $ar) {
    		$new = new CNewsItem($ar);
    		$news->add($ar->getId(), $new);
    	}
    	$this->setData("news", $news);
    	
    	//Объявления прошлых учебных лет
    	$setNewsOld = new CRecordSet();
    	$resNewsOld = new CQuery();
    	$resNewsOld->select("news.*")
    	->from(TABLE_NEWS." as news")
    	->condition('news.user_id_insert="'.CRequest::getInt("id").'" and news.date_time<"'.CUtils::getCurrentYear()->date_start.'"')
    	->order("news.date_time desc");
    	$newsOld = new CArrayList();
    	$setNewsOld->setQuery($resNewsOld);
    	foreach ($setNewsOld->getItems() as $ar) {
    		$newOld = new CNewsItem($ar);
    		$newsOld->add($ar->getId(), $newOld);
    	}
    	$this->setData("newsOld", $newsOld);
    	
    	//Дипломники текущего учебного года
    	$setDipl = new CRecordSet();
    	$resDipl = new CQuery();
    	$resDipl->select("diploms.*, pp.name as pract_place, students.fio as student_fio, study_groups.name as group_name")
    	->from(TABLE_DIPLOMS." as diploms")
    	->leftJoin(TABLE_STUDENTS." as students", "diploms.student_id=students.id")
    	->leftJoin(TABLE_PRACTICE_PLACES." as pp", "pp.id=diploms.pract_place_id")
    	->leftJoin(TABLE_PERSON." as kadri", "diploms.kadri_id=kadri.id")
    	->leftJoin(TABLE_STUDENT_GROUPS." as study_groups", "study_groups.id=students.group_id")
    	->leftJoin(TABLE_USERS." as users", "users.kadri_id=kadri.id")
    	->condition('users.kadri_id>0 and users.id="'.CRequest::getInt("id").'" and (diploms.date_act>="'.CUtils::getCurrentYear()->date_start.'" or diploms.date_act is NULL)')
    	->order("students.fio asc");
    	$diploms = new CArrayList();
    	$setDipl->setQuery($resDipl);
    	foreach ($setDipl->getItems() as $ar) {
    		$diplom = new CDiplom($ar);
    		$diploms->add($ar->getId(), $diplom);
    	}
    	$this->setData("diploms", $diploms);
    	
    	//Дипломники предыдущих учебных лет	
    	$setDiplOld = new CRecordSet();
    	$resDiplOld = new CQuery();
    	$resDiplOld->select("diploms.*, pp.name as pract_place, students.fio as student_fio, study_groups.name as group_name")
    	->from(TABLE_DIPLOMS." as diploms")
    	->leftJoin(TABLE_STUDENTS." as students", "diploms.student_id=students.id")
    	->leftJoin(TABLE_PRACTICE_PLACES." as pp", "pp.id=diploms.pract_place_id")
    	->leftJoin(TABLE_PERSON." as kadri", "diploms.kadri_id=kadri.id")
    	->leftJoin(TABLE_STUDENT_GROUPS." as study_groups", "study_groups.id=students.group_id")
    	->leftJoin(TABLE_USERS." as users", "users.kadri_id=kadri.id")
    	->condition('users.kadri_id>0 and users.id="'.CRequest::getInt("id").'" and diploms.date_act<"'.CUtils::getCurrentYear()->date_start.'"')
    	->order("students.fio asc");
    	$diplomsOld = new CArrayList();
    	$setDiplOld->setQuery($resDiplOld);
    	foreach ($setDiplOld->getItems() as $ar) {
    		$diplomOld = new CDiplom($ar);
    		$diplomsOld->add($ar->getId(), $diplomOld);
    	}
    	$this->setData("diplomsOld", $diplomsOld);
    	
    	//Подготовка аспирантов, текущие 	
    	$setAspir = new CRecordSet();
    	$resAspir = new CQuery();
    	$resAspir->select("disser.*, kadri.fio as fio")
    	->from(TABLE_PERSON_DISSER." as disser")
    	->innerJoin(TABLE_PERSON." as kadri", "kadri.id=disser.kadri_id")
    	->condition('disser.kadri_id>0 and disser.scinceMan=(select users.kadri_id from users where users.id="'.CRequest::getInt("id").'") and disser.god_zach>="'.date("Y").'"')
    	->order("kadri.fio asc");
    	$aspirs = new CArrayList();
    	$setAspir->setQuery($resAspir);
    	foreach ($setAspir->getItems() as $ar) {
    		$aspir = new CPersonPaper($ar);
    		$aspirs->add($ar->getId(), $aspir);
    	}
    	$this->setData("aspirs", $aspirs);
    	
    	//Подготовка аспирантов, архив
    	$setAspirOld = new CRecordSet();
    	$resAspirOld = new CQuery();
    	$resAspirOld->select("disser.*, kadri.fio as fio")
    	->from(TABLE_PERSON_DISSER." as disser")
    	->innerJoin(TABLE_PERSON." as kadri", "kadri.id=disser.kadri_id")
    	->condition('disser.kadri_id>0 and disser.scinceMan=(select users.kadri_id from users where users.id="'.CRequest::getInt("id").'") and disser.scinceMan>0 and (disser.god_zach<"'.date("Y").'" or disser.god_zach is null)')
    	->order("kadri.fio asc");
    	$aspirsOld = new CArrayList();
    	$setAspirOld->setQuery($resAspirOld);
    	foreach ($setAspirOld->getItems() as $ar) {
    		$aspirOld = new CPersonPaper($ar);
    		$aspirsOld->add($ar->getId(), $aspirOld);
    	}
    	$this->setData("aspirsOld", $aspirsOld);
    	
    	//Расписание
    	$setRasp = new CRecordSet();
    	$resRasp = new CQuery();
    	$resRasp->select("time.*")
    	->from(TABLE_SCHEDULE." as time")
    	->condition('time.id="'.CRequest::getInt("id").'" and time.year="'.CUtils::getCurrentYear()->getId().'" and time.month="'.CUtils::getCurrentYearPart()->getId().'"');
    	$rasps = new CArrayList();
    	$setRasp->setQuery($resRasp);
    	foreach ($setRasp->getItems() as $ar) {
    		$rasp = new CSchedule($ar);
    		$rasps->add($ar->getId(), $rasp);
    	}
    	$this->setData("rasps", $rasps);
    	
    	//Вопросы и ответы на них преподавателя
    	$setQuest = new CRecordSet();
    	$resQuest = new CQuery();
    	$resQuest->select("quest.*")
    	->from(TABLE_QUESTION_TO_USERS." as quest")
    	->condition('quest.status=3 and quest.answer_text is not null and quest.answer_text!="" and quest.user_id="'.CRequest::getInt("id").'"')
    	->order("quest.datetime_quest desc");
    	$quests = new CArrayList();
    	$setQuest->setQuery($resQuest);
    	foreach ($setQuest->getItems() as $ar) {
    		$quest = new CQuestion($ar);
    		$quests->add($ar->getId(), $quest);
    	}
    	$this->setData("quests", $quests);
    	
    	//Кураторство учебных групп
    	$setGroup = new CRecordSet();
    	$resGroup = new CQuery();
    	$resGroup->select("sg.id, sg.name")
    	->from(TABLE_STUDENT_GROUPS." as sg")
    	->leftJoin(TABLE_USERS." as users", "users.kadri_id=sg.curator_id")
    	->condition("users.id=".CRequest::getInt("id"));
    	$groups = new CArrayList();
    	$setGroup->setQuery($resGroup);
    	foreach ($setGroup->getItems() as $ar) {
    		$group = new CStudentGroup($ar);
    		$groups->add($ar->getId(), $group);
    	}
    	$this->setData("groups", $groups);
    	
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
    	->condition("user.FIO like '%".$term."%' and user.status!='администратор'")
    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CUser"
    		);
    	}
        echo json_encode($res);
    }
}