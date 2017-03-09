<?php
/**
 * Учебная нагрузка
 */
class CStudyLoadController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Учебная нагрузка");

        parent::__construct();
    }
    public function actionIndex() {
    	$isBudget = true;
    	if (CRequest::getInt("isBudget") == "-1") {
    		$isBudget = false;
    	}
    	$isContract = true;
    	if (CRequest::getInt("isContract") == "-1") {
    		$isContract = false;
    	}
    	
    	// фильтр по году
    	$selectedYear = CUtils::getCurrentYear()->getId();
    	if (!is_null(CRequest::getFilter("year.id"))) {
    		$selectedYear = CRequest::getFilter("year.id");
    	}
    	
    	// выражения запроса с учетом категории средств (основные, доп.)
    	$gArr = array();	
    	if ($isBudget) {
    		$gArr['lects'][] = "ifnull(hours.lects, 0)";
    		$gArr['dipl'][] = "ifnull(hours.consult_dipl, 0)";
    		$gArr['sum'][] = "ifnull(hours.practs, 0)";
    		$gArr['sum'][] = "ifnull(hours.lects, 0)";
    		$gArr['sum'][] = "ifnull(hours.labor, 0)";
    		$gArr['sum'][] = "ifnull(hours.rgr, 0)";
    		$gArr['sum'][] = "ifnull(hours.ksr, 0)";
    		$gArr['sum'][] = "ifnull(hours.recenz, 0)";
    		$gArr['sum'][] = "ifnull(hours.kurs_proj, 0)";
    		$gArr['sum'][] = "ifnull(hours.consult, 0)";
    		$gArr['sum'][] = "ifnull(hours.test, 0)";
    		$gArr['sum'][] = "ifnull(hours.exams, 0)";
    		$gArr['sum'][] = "ifnull(hours.study_pract, 0)";
    		$gArr['sum'][] = "ifnull(hours.work_pract, 0)";
    		$gArr['sum'][] = "ifnull(hours.consult_dipl, 0)";
    		$gArr['sum'][] = "ifnull(hours.gek, 0)";
    		$gArr['sum'][] = "ifnull(hours.aspirants, 0)";
    		$gArr['sum'][] = "ifnull(hours.aspir_manage, 0)";
    		$gArr['sum'][] = "ifnull(hours.duty, 0)";
    		$gArr['stud'][] = "ifnull(hours.stud_cnt, 0)";
    	}
    	if ($isContract) {
    		$gArr['lects'][] = "ifnull(hours.lects_add, 0)";
    		$gArr['dipl'][] = "ifnull(hours.consult_dipl_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.practs_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.lects_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.labor_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.rgr_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.ksr_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.recenz_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.kurs_proj_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.consult_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.test_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.exams_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.study_pract_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.work_pract_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.consult_dipl_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.gek_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.aspirants_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.aspir_manage_add, 0)";
    		$gArr['sum'][] = "ifnull(hours.duty_add, 0)";
    		$gArr['stud'][] = "ifnull(hours.stud_cnt_add, 0)";
    	}
    	
    	// текущая дата для расчета ставки по актуальным приказам ОК
    	$dateFrom = date('Y.m.d', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
    	
    	// сотрудники с нагрузкой в указанном году
    	$personsWithLoad = array();
    	$lectsTotal = 0;
    	$diplTotal = 0;
    	$mainTotal = 0;
    	$additionalTotal = 0;
    	$premiumTotal = 0;
    	$byTimeTotal = 0;
    	$sumTotal = 0;
    	if (!(!$isBudget and !$isContract)) {
    		$query = new CQuery();
    		$query->select("kadri.id as kadri_id,
					hours.year_id as year_id,
					kadri.fio as fio,
					kadri.fio_short,
					dolgnost.name_short as dolgnost,
					hr.rate,
	    		
					sum(hours.groups_cnt) as groups_cnt_sum_,
					sum(".implode("+", $gArr["stud"]).") as stud_cnt_sum_,
					sum(".implode("+", $gArr["lects"]).") as lects_sum_,
					sum(".implode("+", $gArr["dipl"]).") as dipl_sum_,
	    		
					sum(case when hours.hours_kind_type = 1 then (".implode("+", $gArr["sum"]).") else 0 end) as hours_sum1_,
					sum(case when hours.hours_kind_type = 2 then (".implode("+", $gArr["sum"]).") else 0 end) as hours_sum2_,
					sum(case when hours.hours_kind_type = 3 then (".implode("+", $gArr["sum"]).") else 0 end) as hours_sum3_,
					sum(case when hours.hours_kind_type = 4 then (".implode("+", $gArr["sum"]).") else 0 end) as hours_sum4_,
	    		
					sum(".implode("+", $gArr["sum"]).") as hours_sum")
    			->from(TABLE_PERSON." as kadri")
    			->leftJoin(TABLE_IND_PLAN_PLANNED." as hours", "hours.kadri_id = kadri.id")
    			->leftJoin(TABLE_POSTS." as dolgnost", "dolgnost.id = kadri.dolgnost")
    			->leftJoin(TABLE_HOURS_RATE." as hr", "hr.dolgnost_id = kadri.dolgnost")
    			->condition("hours.year_id = ".$selectedYear)
    			->group("kadri.id")
    			->order("kadri.fio_short asc");
    		$personsWithLoad = $query->execute()->getItems();
    		$i = 0;
    		foreach ($personsWithLoad as $person) {
    			$queryOrders = new CQuery();
    			$queryOrders->select("round(sum(rate),2) as rate_sum, count(id) as ord_cnt")
	    			->from(TABLE_STAFF_ORDERS." as orders")
	    			->condition('concat(substring(date_end, 7, 4), ".", substring(date_end, 4, 2), ".", substring(date_end, 1, 2)) >= "'.$dateFrom.'" and kadri_id = "'.$person['kadri_id'].'"');
    			foreach ($queryOrders->execute()->getItems() as $order) {
    				$personsWithLoad[$i]['rate_sum'] = $order['rate_sum'];
    				$personsWithLoad[$i]['ord_cnt'] = $order['ord_cnt'];
    				$i++;
    			}
    		}
    		foreach ($personsWithLoad as $person) {
    			$lectsTotal += $person['lects_sum_'];
    			$diplTotal += $person['dipl_sum_'];
    			$mainTotal += $person['hours_sum1_'];
    			$additionalTotal += $person['hours_sum2_'];
    			$premiumTotal += $person['hours_sum3_'];
    			$byTimeTotal += $person['hours_sum4_'];
    			$sumTotal += $person['hours_sum'];
    		}
    	}

    	// сотрудники без нагрузки в указанном году
    	$personsWithoutLoad = new CArrayList();
    	$queryWithoutLoad = new CQuery();
    	$queryWithoutLoad->select("person.*")
	    	->from(TABLE_PERSON." as person")
	    	->innerJoin(TABLE_PERSON_BY_TYPES." as types", "types.kadri_id = person.id")
	    	->innerJoin(TABLE_TYPES." as person_types", "person_types.id = types.person_type_id")
	    	->condition("person_types.name_short like '%ППС%' and person.id NOT IN (SELECT kadri_id from ".TABLE_IND_PLAN_PLANNED." where year_id='".$selectedYear."')")
	    	->order("person.fio_short asc");
    	
    	$setWithoutLoad = new CRecordSet(false);
    	$setWithoutLoad->setQuery($queryWithoutLoad);
    	foreach ($setWithoutLoad->getItems() as $item) {
    		$person = new CPerson($item);
    		$personsWithoutLoad->add($person->getId(), $person);
    	}
		
        $this->setData("personsWithLoad", $personsWithLoad);
        $this->setData("personsWithoutLoad", $personsWithoutLoad);
        $this->setData("selectedYear", $selectedYear);
        $this->setData("isBudget", $isBudget);
        $this->setData("isContract", $isContract);
        $this->setData("lectsTotal", $lectsTotal);
        $this->setData("diplTotal", $diplTotal);
        $this->setData("mainTotal", $mainTotal);
        $this->setData("additionalTotal", $additionalTotal);
        $this->setData("premiumTotal", $premiumTotal);
        $this->setData("byTimeTotal", $byTimeTotal);
        $this->setData("sumTotal", $sumTotal);
        $this->renderView("_study_loads/index.tpl");
    }
    public function actionAdd() {
        $studyLoad = new CStudyLoad();
        $this->setData("studyLoad", $studyLoad);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => WEB_ROOT."_modules/_study_loads/index.php",
                "icon" => "actions/edit-undo.png"
            )
        ));		
        $this->renderView("_study_loads/add.tpl");
    }
    public function actionEdit() {
        $studyLoad = CStaffService::getStudyLoad(CRequest::getInt("id"));
        $kadriId = $studyLoad->kadri_id;
        $yearId = $studyLoad->year_id;
        $parts = array(1 => "Осенний", 2 => "Весенний");
        $this->setData("parts", $parts);
        $this->setData("studyLoad", $studyLoad);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "index.php?action=editLoads&kadri_id=".$kadriId."&year_id=".$yearId,
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_study_loads/edit.tpl");
    }
    public function actionEditLoads() {
    	$lecturer = CStaffManager::getPerson(CRequest::getInt("kadri_id"));
    	$year = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
    	$loads = CStaffService::getStudyLoadByYear($lecturer, $year);
    	
    	$loadsFall = new CArrayList();
    	$loadsSpring = new CArrayList();
    	foreach ($loads as $study) {
    		$studyLects = 0;
    		$studyPracts = 0;
    		$studyLabor = 0;
    		$studyRgr = 0;
    		$studyKsr = 0;
    		$studyRecenz = 0;
    		$studyKursProj = 0;
    		$studyConsult = 0;
    		$studyTest = 0;
    		$studyExams = 0;
    		$studyStudyPract = 0;
    		$studyWorkPract = 0;
    		$studyConsultDipl = 0;
    		$studyGek = 0;
    		$studyAspirants = 0;
    		$studyAspirManage = 0;
    		$studyDuty = 0;
    		
    		$studyLects += $study->lects + $study->lects_add;
    		$studyPracts += $study->practs + $study->practs_add;
    		$studyLabor += $study->labor + $study->labor_add;
    		$studyRgr += $study->rgr + $study->rgr_add;
    		$studyKsr += $study->ksr + $study->ksr_add;
    		$studyRecenz += $study->recenz + $study->recenz_add;
    		$studyKursProj += $study->kurs_proj + $study->kurs_proj_add;
    		$studyConsult += $study->consult + $study->consult_add;
    		$studyTest += $study->test + $study->test_add;
    		$studyExams += $study->exams + $study->exams_add;
    		$studyStudyPract += $study->study_pract + $study->study_pract_add;
    		$studyWorkPract += $study->work_pract + $study->work_pract_add;
    		$studyConsultDipl += $study->consult_dipl + $study->consult_dipl_add;
    		$studyGek += $study->gek + $study->gek_add;
    		$studyAspirants += $study->aspirants + $study->aspirants_add;
    		$studyAspirManage += $study->aspir_manage + $study->aspir_manage_add;
    		$studyDuty += $study->duty + $study->duty_add;
    		
    		$totalLoad = $studyLects +
	    		$studyPracts +
	    		$studyLabor +
	    		$studyRgr +
	    		$studyKsr +
	    		$studyRecenz +
	    		$studyKursProj +
	    		$studyConsult +
	    		$studyTest +
	    		$studyExams +
	    		$studyStudyPract +
	    		$studyWorkPract +
	    		$studyConsultDipl +
	    		$studyGek +
	    		$studyAspirants +
	    		$studyAspirManage +
	    		$studyDuty;
    		$study->sum = $totalLoad;
    		
    		if ($study->part_id == 1) {
    			$loadsFall->add($study->getId(), $study);
    		}
    		if ($study->part_id == 2) {
    			$loadsSpring->add($study->getId(), $study);
    		}
    	}
    	
    	$this->setData("loadsFall", $loadsFall);
    	$this->setData("loadsSpring", $loadsSpring);
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_study_loads/index.php",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->renderView("_study_loads/editLoads.tpl");
    }
    public function actionDelete() {
    	$studyLoad = CStaffService::getStudyLoad(CRequest::getInt("id"));
    	$kadriId = $studyLoad->kadri_id;
    	$yearId = $studyLoad->year_id;
    	if (!is_null($studyLoad)) {
    		$studyLoad->remove();
    	}
    	$this->redirect("?action=editLoads&kadri_id=".$kadriId."&year_id=".$yearId);
    }
    public function actionSave() {
        $studyLoad = new CStudyLoad();
        $studyLoad->setAttributes(CRequest::getArray($studyLoad::getClassName()));
        if ($studyLoad->validate()) {
            $studyLoad->save();
            $kadriId = $studyLoad->kadri_id;
            $yearId = $studyLoad->year_id;
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$studyLoad->getId());
            } else {
                $this->redirect("?action=editLoads&kadri_id=".$kadriId."&year_id=".$yearId);
            }
            return true;
        }
        $this->setData("studyLoad", $studyLoad);
        $this->renderView("_study_loads/edit.tpl");
    }
}