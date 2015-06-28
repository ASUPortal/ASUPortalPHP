<?php

class CLecturersController extends CBaseController {
	public $allowedAnonymous = array(
			"index",
			"view"
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
        $query_letter_rus = "select UPPER(left(u.fio,1)) as name, COUNT(*) AS cnt from users u where u.status='преподаватель' group by 1 order by 1";
        $res_rus = mysql_query($query_letter_rus);
        if (isset($_GET['getsub'])) {
        	$query->condition('user.FIO like "'.$letter.'%" and user.status!="администратор"');
        }
        $lects = new CArrayList();
        $set->setQuery($query);      
        foreach ($set->getPaginated()->getItems() as $ar) {
            $lect = new CLect($ar);
            $lects->add($lect->getId(), $lect);
        }
        $this->setData("paginator", $set->getPaginator());
        $this->setData("lects", $lects);
        $this->setData("firstLet", $firstLet);
        $this->setData("letterId", $letterId);
        $this->setData("query_letter_rus", $query_letter_rus);
        $this->setData("res_rus", $res_rus);
        $this->renderView("__public/_lecturers/index.tpl");
    }
    public function biographyView() {
		$printFullBox = false;
    	if (mb_strlen(CBiographyManager::getBiographyByUser(CRequest::getInt("id"))->main_text) > 450) {
    		echo mb_substr(CLecturersController::msg_replace(CBiographyManager::getBiographyByUser(CRequest::getInt("id"))->main_text), 0, 450)."...";
    		echo '<p><a href="#modal" data-toggle="modal">Читать полностью</a></p>';
    		$printFullBox = true;
    	} else {
    		echo CLecturersController::msg_replace(CBiographyManager::getBiographyByUser(CRequest::getInt("id"))->main_text);
    	}
    	if ($printFullBox) {
    		echo '
                    <div id="modal" class="modal hide fade">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h3 id="myModalLabel">Биография</h3>
                        </div>
                        <div class="modal-body">
                            '.CLecturersController::msg_replace(CBiographyManager::getBiographyByUser(CRequest::getInt("id"))->main_text).'
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                        </div>
                    </div>
                ';
    	}
    }
    public function msg_replace($s) {
    	//замена при выводе сообщений на экран для форматирования
	    $s=str_replace ("\r\n","<br>",$s);
	    
	    $s=str_replace ("[url]","<a href='http://",$s);
	    $s=str_replace ("[/url]","' target='_blank' style='font-weight:normal; text-decoration:underline;'>Ресурс</a>",$s);
	    
	    $s=str_replace ("[quote]","<u>Цитата</u><br><span style='color:grey;background:white;'>",$s);
	    $s=str_replace ("[/quote]","</span><br>",$s);
	    
	    $s=str_replace ("[b]","<b>",$s);
	    $s=str_replace ("[/b]","</b>",$s);
	    
	    $s=str_replace ("[u]","<u>",$s);
	    $s=str_replace ("[/u]","</u>",$s);
	    
	    $s=str_replace ("[i]","<i>",$s);
	    $s=str_replace ("[/i]","</i>",$s);
	    	
	    return $s;
    }
    public function actionView() {
    	$lect = CLectManager::getLect(CRequest::getInt("id"));
    	$this->setData("lect", $lect);
    	
    	//Биография   	
    	$queryBiog = new CQuery();
    	$queryBiog->select("biog.*")
    	->from(TABLE_BIOGRAPHY." as biog")
    	->condition("biog.user_id=".CRequest::getInt("id"));
    	$biogs = $queryBiog->execute()->getCount();
    	$this->setData("biogs", $biogs);
    	if ($biogs != 0) {
    		$photo_b=mysql_query("select image from biography where user_id='".CRequest::getInt("id")."' limit 0,1 ");
    		$photo_biog=mysql_result($photo_b,0);
    		 
    		$photo_k=mysql_query("select photo from kadri where id in(select kadri_id as id from users where id='".CRequest::getInt("id")."') limit 0,1");
    		$photo_kadri=mysql_result($photo_k,0);
    		
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
    	$resPage=mysql_query('select `id`,`title` from `pg_uploads` where `user_id_insert`='.CRequest::getInt("id").' and type_id<>1');
    	$this->setData("resPage", $resPage);
    	
    	//Список пособий на портале
    	$resSubj=mysql_query('SELECT d.nameFolder, s.name AS nameSubject, (select count(*) from files f where f.nameFolder= d.nameFolder) as f_cnt
							FROM subjects s
							LEFT OUTER JOIN documents d
							ON (s.id = d.subj_id)
							WHERE d.user_id ="'.CRequest::getInt("id").'"');
    	$this->setData("resSubj", $resSubj);
    	
    	//Объявления текущего учебного года
    	$resNews=mysql_query('select id,title,date_time,file,file_attach,image from news
				where user_id_insert="'.CRequest::getInt("id").'" and date_time>="'.CUtils::getCurrentYear()->date_start.'"
				order by date_time DESC');
    	$this->setData("resNews", $resNews);
    	
    	//Объявления прошлых учебных лет
    	$resNewsOld=mysql_query ('select id,title,date_time,file,file_attach,image from news
				where user_id_insert="'.CRequest::getInt("id").'" and date_time<"'.CUtils::getCurrentYear()->date_start.'"
				order by date_time DESC');
    	$this->setData("resNewsOld", $resNewsOld);
    	
    	//Дипломники текущего учебного года
    	$queryDipl='SELECT diploms.id,diploms.dipl_name,pp.name as pract_place,kadri.id as kadri_id,kadri.fio as kadri_fio,students.fio as student_fio,study_groups.name as group_name,diploms.comment
		FROM diploms
		left join students on diploms.student_id=students.id
		left join pract_places pp on pp.id=diploms.pract_place_id
		left join kadri on diploms.kadri_id=kadri.id
		left join study_groups on study_groups.id=students.group_id
		inner join users on users.kadri_id=kadri.id
			where users.kadri_id>0 and users.id="'.CRequest::getInt("id").'" and (diploms.date_act>="'.CUtils::getCurrentYear()->date_start.'" or date_act is NULL)	order by students.fio';
    	$resDipl=mysql_query($queryDipl);
		$this->setData("resDipl", $resDipl);	
    	
    	//Дипломники предыдущих учебных лет
    	$queryDiplOld='SELECT diploms.id,diploms.dipl_name,pp.name as pract_place,kadri.id as kadri_id,kadri.fio as kadri_fio,students.fio as student_fio,study_groups.name as group_name,diploms.comment
		FROM diploms
		left join students on diploms.student_id=students.id
		left join kadri on diploms.kadri_id=kadri.id
		left join study_groups on study_groups.id=students.group_id
		left join pract_places pp on pp.id=diploms.pract_place_id
		left join users on users.kadri_id=kadri.id
			where users.kadri_id>0 and users.id="'.CRequest::getInt("id").'" and (diploms.date_act<"'.CUtils::getCurrentYear()->date_start.'" )
			order by students.fio';
    	$resDiplOld=mysql_query($queryDiplOld);
    	$this->setData("resDiplOld", $resDiplOld);
    	
    	//Подготовка аспирантов, текущие
    	$queryAspir='SELECT k.fio,d.`tema` FROM `disser` d inner join kadri k on k.id=d.`kadri_id`
					WHERE d.`kadri_id`>0 and `scinceMan`=(select kadri_id from users where id='.CRequest::getInt("id").') and `god_zach`>="'.date("Y").'" order by k.fio';
    	$resAspir=mysql_query($queryAspir);
    	$this->setData("resAspir", $resAspir);
    	
    	//Подготовка аспирантов, архив
    	$queryAspirOld='SELECT k.fio,d.`tema` FROM `disser` d inner join kadri k on k.id=d.`kadri_id`
					WHERE d.`kadri_id`>0 and `scinceMan`=(select kadri_id from users where id='.CRequest::getInt("id").') and `scinceMan`>0
						and (`god_zach`<"'.date("Y").'" or `god_zach` is null) order by k.fio';
    	$resAspirOld=mysql_query($queryAspirOld);
    	$this->setData("resAspirOld", $resAspirOld);
    	
    	//Расписание
    	$resRasp=mysql_query('select id from time where id="'.CRequest::getInt("id").'" and
				time.year="'.CUtils::getCurrentYear()->getId().'" and time.month="'.CUtils::getCurrentYearPart()->getId().'" limit 0,1');
    	$this->setData("resRasp", $resRasp);
    	
    	//Вопросы и ответы на них преподавателя
    	$resQuest=mysql_query('select q2u.user_id,q2u.question_text,q2u.contact_info,q2u.answer_text,q2u.datetime_quest,q2u.datetime_answ
							from question2users q2u
							where q2u.status=3 and answer_text is not null and answer_text!="" and user_id='.CRequest::getInt("id").'
							order by q2u.datetime_quest');
    	$this->setData("resQuest", $resQuest);
    	
    	//Кураторство учебных групп
    	$resGroup=mysql_query ('select sg.id,sg.name
				from study_groups sg
					left join users u on u.kadri_id=sg.curator_id
					where u.kadri_id>0 and u.id='.CRequest::getInt("id"));
    	$this->setData("resGroup", $resGroup);
    	
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