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
    	if (CRequest::getInt("isBudget") == -1) {
    		$isBudget = false;
    	}
    	$isContract = true;
    	if (CRequest::getInt("isContract") == -1) {
    		$isContract = false;
    	}
    	
    	// фильтр по году
    	$selectedYear = CUtils::getCurrentYear()->getId();
    	if (!is_null(CRequest::getFilter("year.id"))) {
    		$selectedYear = CRequest::getFilter("year.id");
    	}
    	
    	// сотрудники с нагрузкой в указанном году
    	if (CSessionService::hasAnyRole([ACCESS_LEVEL_READ_OWN_ONLY, ACCESS_LEVEL_WRITE_OWN_ONLY])) {
    		$personsWithLoad = CStudyLoadService::getPersonsWithLoadByYear($isBudget, $isContract, $selectedYear, CSession::getCurrentPerson());
    	} else {
    		$personsWithLoad = CStudyLoadService::getPersonsWithLoadByYear($isBudget, $isContract, $selectedYear);
    	}
    	
    	$mainTotal = 0;
    	$additionalTotal = 0;
    	$premiumTotal = 0;
    	$byTimeTotal = 0;
    	$sumTotal = 0;
    	if (count($personsWithLoad) != 0) {
    		if ($isBudget or $isContract) {
    			foreach ($personsWithLoad as $person) {
    				$mainTotal += $person['hours_sum_base'];
    				$additionalTotal += $person['hours_sum_additional'];
    				$premiumTotal += $person['hours_sum_premium'];
    				$byTimeTotal += $person['hours_sum_by_time'];
    				$sumTotal += $person['hours_sum'];
    			}
    		}
    	}

    	// сотрудники без нагрузки в указанном году
    	$personsWithoutLoad = CStudyLoadService::getPersonsWithoutLoadByYear($selectedYear);
    	
        if (CSessionService::hasAnyRole([ACCESS_LEVEL_READ_ALL, ACCESS_LEVEL_WRITE_ALL])) {
            $this->setTableFilter("dataTable");
            
            $this->addActionsMenuItem(array(
            	array(
            		"title" => "Сведения о ППС",
            		"link" => WEB_ROOT."_modules/_study_loads/index.php?action=information",
            		"icon" => "mimetypes/x-office-spreadsheet.png"
            	)
            ));
        }
        $this->setTableSort("dataTable");
        
        /**
         * Параметры для групповой печати по шаблону
         */
        $parameters = array("year_id" => $selectedYear, "base" => 1, "additional" => 1, "premium" => 1, "byTime" => 1);
        $this->setData("parameters", $parameters);
        $this->setData("template", "formset_study_loads");
        $this->setData("selectedDoc", true);
        $this->setData("url", null);
        $this->setData("action", null);
        $this->setData("id", null);
        
        $this->setData("personsWithLoad", $personsWithLoad);
        $this->setData("personsWithoutLoad", $personsWithoutLoad);
        $this->setData("selectedYear", $selectedYear);
        $this->setData("isBudget", $isBudget);
        $this->setData("isContract", $isContract);
        $this->setData("mainTotal", $mainTotal);
        $this->setData("additionalTotal", $additionalTotal);
        $this->setData("premiumTotal", $premiumTotal);
        $this->setData("byTimeTotal", $byTimeTotal);
        $this->setData("sumTotal", $sumTotal);
        $this->renderView("_study_loads/index.tpl");
    }
    public function actionAdd() {
        $studyLoad = new CStudyLoad();
        $studyLoad->person_id = CRequest::getInt("kadri_id");
        $studyLoad->year_id = CRequest::getInt("year_id");
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
        $studyLoad = CStudyLoadService::getStudyLoad(CRequest::getInt("id"));
        $kadriId = $studyLoad->person_id;
        $yearId = $studyLoad->year_id;
        $this->setData("studyLoad", $studyLoad);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => UrlBuilder::newBuilder("index.php")
							->addParameter("action", "editLoads")
							->addParameter("kadri_id", $kadriId)
							->addParameter("year_id", $yearId)
							->addParameter("base", 1)
							->addParameter("additional", 1)
							->addParameter("premium", 1)
							->addParameter("byTime", 1)
							->build(),
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_study_loads/edit.tpl");
    }
    public function actionEditLoads() {
    	$selectedPerson = null;
    	$selectedYear = null;
    	if (CSessionService::hasAnyRole([ACCESS_LEVEL_READ_OWN_ONLY, ACCESS_LEVEL_WRITE_OWN_ONLY])) {
    		$lecturer = CSession::getCurrentPerson();
    	} else {
    		$lecturer = CStaffManager::getPerson(CRequest::getInt("kadri_id"));
    	}
    	if (!is_null($lecturer)) {
    		$selectedPerson = $lecturer->getId();
    	}
    	$year = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
    	if (!is_null($year)) {
    		$selectedYear = $year->getId();
    	}
    	
    	$loadTypes = array();
    	$base = true;
    	if (CRequest::getInt("base") == 0) {
    		$base = false;
    	} else {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::BASE)->getId();
    	}
    	$additional = true;
    	if (CRequest::getInt("additional") == 0) {
    		$additional = false;
    	} else {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::ADDITIONAL)->getId();
    	}
    	$premium = true;
    	if (CRequest::getInt("premium") == 0) {
    		$premium = false;
    	} else {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::PREMIUM)->getId();
    	}
    	$byTime = true;
    	if (CRequest::getInt("byTime") == 0) {
    		$byTime = false;
    	} else {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::BY_TIME)->getId();
    	}
    	// Должность, ученая степень, ученое звание
    	$position = "";
    	if (!is_null($lecturer) and !is_null($year) and !empty($loadTypes)) {
    		$loads = CStudyLoadService::getStudyLoadsByYearAndLoadType($lecturer, $year, $loadTypes);
    		
    		if (!is_null($lecturer->getPost())) {
    			$position = $lecturer->getPost()->getValue();
    		}
    		if (!is_null($lecturer->title)) {
    			$position .= ", ".$lecturer->title->getValue();
    		}
    		if (!is_null($lecturer->degree)) {
    			$position .= ", ".$lecturer->degree->getValue();
    		}
    	} else {
    		$loads = new CArrayList();
    	}
    	$this->setData("base", $base);
    	$this->setData("additional", $additional);
    	$this->setData("premium", $premium);
    	$this->setData("byTime", $byTime);
    	$this->setData("isBudget", 1);
    	$this->setData("isContract", 1);
    	
    	$loadsFall = CStudyLoadService::getStudyLoadsByPart($loads, CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::FALL));
    	$loadsSpring = CStudyLoadService::getStudyLoadsByPart($loads, CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::SPRING));
    	
    	$this->setData("lecturer", $lecturer);
    	$this->setData("position", $position);
    	$this->setData("year", $year);
    	$this->setData("selectedYear", $selectedYear);
    	$this->setData("selectedPerson", $selectedPerson);
    	$this->setData("loadsFall", $loadsFall);
    	$this->setData("loadsSpring", $loadsSpring);
    	$this->setData("loadTypes", $loadTypes);
    	$this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => WEB_ROOT."_modules/_study_loads/index.php",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Печать по шаблону",
                "link" => "#",
                "icon" => "devices/printer.png",
                "template" => "formset_study_loads"
            )
    	));
    	if (CSessionService::hasAnyRole([ACCESS_LEVEL_READ_ALL, ACCESS_LEVEL_WRITE_ALL])) {
    		$this->addActionsMenuItem(array(
    			array(
    				"title" => "Добавить",
    				"link" => UrlBuilder::newBuilder("index.php")
							->addParameter("action", "add")
							->addParameter("kadri_id", $selectedPerson)
							->addParameter("year_id", $selectedYear)
							->build(),
    				"icon" => "actions/list-add.png"
    			),
    			array(
    				"title" => "Редактировать нагрузку по бюджету",
    				"link" => UrlBuilder::newBuilder("index.php")
							->addParameter("action", "editLoadsByType")
							->addParameter("kadri_id", $selectedPerson)
							->addParameter("year_id", $selectedYear)
							->addParameter("base", 1)
							->addParameter("additional", 1)
							->addParameter("premium", 1)
							->addParameter("byTime", 1)
							->addParameter("isBudget", 1)
							->addParameter("isContract", 0)
							->build(),
    				"icon" => "apps/accessories-text-editor.png"
    			),
    			array(
    				"title" => "Редактировать нагрузку по контракту",
    				"link" => UrlBuilder::newBuilder("index.php")
							->addParameter("action", "editLoadsByType")
							->addParameter("kadri_id", $selectedPerson)
							->addParameter("year_id", $selectedYear)
							->addParameter("base", 1)
							->addParameter("additional", 1)
							->addParameter("premium", 1)
							->addParameter("byTime", 1)
							->addParameter("isBudget", 0)
							->addParameter("isContract", 1)
							->build(),
    				"icon" => "apps/accessories-text-editor.png"
    			)
    		));
    	}
    	
    	$copyWays = array();
    	$copyWays[0] = "копировать с перемещением (удаляем у одного - добавляем другому)";
    	$copyWays[1] = "только копирование (сохраняем у одного и добавляем другому)";
    	$this->setData("copyWays", $copyWays);
    	
    	// Типы занятий: л, пр, л/р для сверки с расписанием
    	$kindTypes = array();
    	$kindTypes[] = CStudyLoadWorkTypeConstants::LABOR_LECTURE;
    	$kindTypes[] = CStudyLoadWorkTypeConstants::LABOR_PRACTICE;
    	$kindTypes[] = CStudyLoadWorkTypeConstants::LABOR_LAB_WORK;
    	$this->setData("kindTypes", $kindTypes);
    	
    	$this->renderView("_study_loads/editLoads.tpl");
    }
    public function actionEditLoadsByType() {
    	if (CSessionService::hasAnyRole([ACCESS_LEVEL_READ_OWN_ONLY, ACCESS_LEVEL_WRITE_OWN_ONLY])) {
    		$lecturer = CSession::getCurrentPerson();
    		$selectedPerson = $lecturer->getId();
    	} else {
    		$lecturer = CStaffManager::getPerson(CRequest::getInt("kadri_id"));
    		$selectedPerson = $lecturer->getId();
    	}
    	$year = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
    	$selectedYear = $year->getId();
    	 
    	$loadTypes = array();
    	$base = true;
    	if (CRequest::getInt("base") == 0) {
    		$base = false;
    	} else {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::BASE)->getId();
    	}
    	$additional = true;
    	if (CRequest::getInt("additional") == 0) {
    		$additional = false;
    	} else {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::ADDITIONAL)->getId();
    	}
    	$premium = true;
    	if (CRequest::getInt("premium") == 0) {
    		$premium = false;
    	} else {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::PREMIUM)->getId();
    	}
    	$byTime = true;
    	if (CRequest::getInt("byTime") == 0) {
    		$byTime = false;
    	} else {
    		$loadTypes[] = CStudyLoadService::getStudyLoadTypeByAlias(CStudyLoadTypeConstants::BY_TIME)->getId();
    	}
    	$this->setData("base", $base);
    	$this->setData("additional", $additional);
    	$this->setData("premium", $premium);
    	$this->setData("byTime", $byTime);
    	$this->setData("selectedYear", $selectedYear);
    	$this->setData("selectedPerson", $selectedPerson);

    	if (!is_null($lecturer) and !is_null($year) and !empty($loadTypes)) {
    		$loads = CStudyLoadService::getStudyLoadsByYearAndLoadType($lecturer, $year, $loadTypes);
    	} else {
    		$loads = new CArrayList();
    	}
    	$loadsFall = CStudyLoadService::getStudyLoadsByPart($loads, CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::FALL));
    	$loadsSpring = CStudyLoadService::getStudyLoadsByPart($loads, CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::SPRING));
    	
    	$this->setData("lecturer", $lecturer);
    	$this->setData("year", $year);
    	$this->setData("loadsFall", $loadsFall);
    	$this->setData("loadsSpring", $loadsSpring);
    	$this->setData("loadTypes", $loadTypes);
    	$this->setData("isBudget", CRequest::getInt("isBudget"));
    	$this->setData("isContract", CRequest::getInt("isContract"));
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => UrlBuilder::newBuilder("index.php")
						->addParameter("action", "editLoads")
						->addParameter("kadri_id", $selectedPerson)
						->addParameter("year_id", $selectedYear)
						->addParameter("base", 1)
						->addParameter("additional", 1)
						->addParameter("premium", 1)
						->addParameter("byTime", 1)
						->build(),
    			"icon" => "actions/edit-undo.png"
    		),
            array(
                "title" => "Добавить",
                "link" => UrlBuilder::newBuilder("index.php")
						->addParameter("action", "add")
						->addParameter("kadri_id", $selectedPerson)
						->addParameter("year_id", $selectedYear)
						->build(),
                "icon" => "actions/list-add.png"
            )
    	));
    	$this->renderView("_study_loads/form.editLoads.tpl");
    }
    /**
     * Показать выбранные типы нагрузки (основная, дополнительная, надбавка, почасовка)
     */
    public function actionShowLoadTypes() {
        $kadriId = CRequest::getInt("kadri_id");
        $yearId = CRequest::getInt("year_id");
        $base = CRequest::getInt("base");
        $additional = CRequest::getInt("additional");
        $premium = CRequest::getInt("premium");
        $byTime = CRequest::getInt("byTime");
        $isBudget = CRequest::getInt("isBudget");
        $isContract = CRequest::getInt("isContract");
        
        $this->redirect(UrlBuilder::newBuilder("index.php")
        		->addParameter("action", CRequest::getString("redirect"))
        		->addParameter("kadri_id", $kadriId)
        		->addParameter("year_id", $yearId)
        		->addParameter("base", $base)
        		->addParameter("additional", $additional)
        		->addParameter("premium", $premium)
        		->addParameter("byTime", $byTime)
        		->addParameter("isBudget", $isBudget)
        		->addParameter("isContract", $isContract)
        		->build());
    }
    public function actionCopy() {
    	$choice = CRequest::getInt("choice");
    	$lecturerId = CRequest::getInt("lecturer");
    	$yearId = CRequest::getInt("year");
    	$partId = CRequest::getInt("part");
    	$loadsToCopy = CRequest::getArray("selectedDoc");
    	
    	if ($lecturerId != 0 and $yearId != 0 and $partId != 0) {
    		CStudyLoadService::copySelectedLoads($choice, $lecturerId, $yearId, $partId, $loadsToCopy);
    	}
    	
    	$this->redirect(UrlBuilder::newBuilder("index.php")
				->addParameter("action", "editLoads")
				->addParameter("kadri_id", CRequest::getInt("kadri_id"))
				->addParameter("year_id", CRequest::getInt("year_id"))
				->addParameter("base", 1)
				->addParameter("additional", 1)
				->addParameter("premium", 1)
				->addParameter("byTime", 1)
				->build());
    }
    public function actionDelete() {
    	$studyLoad = CStudyLoadService::getStudyLoad(CRequest::getInt("id"));
    	
    	$kadriId = $studyLoad->person_id;
    	$yearId = $studyLoad->year_id;
    	if (!is_null($studyLoad)) {
    		CStudyLoadService::deleteStudyLoad($studyLoad);
    	}
    	$this->redirect(UrlBuilder::newBuilder("index.php")
				->addParameter("action", "editLoads")
				->addParameter("kadri_id", $kadriId)
				->addParameter("year_id", $yearId)
				->addParameter("base", 1)
				->addParameter("additional", 1)
				->addParameter("premium", 1)
				->addParameter("byTime", 1)
				->build());
    }
    public function actionSave() {
        $studyLoad = new CStudyLoad();
        $studyLoad->setAttributes(CRequest::getArray($studyLoad::getClassName()));
        if ($studyLoad->validate()) {
            $lastId = $studyLoad->save();
            
            $object = new CStudyLoadTable($studyLoad);
            $object->setAttributes(CRequest::getArray($object::getClassName()));
            $object->save($lastId);
            
            $kadriId = $studyLoad->person_id;
            $yearId = $studyLoad->year_id;
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$lastId);
            } else {
                $this->redirect(UrlBuilder::newBuilder("index.php")
						->addParameter("action", "editLoads")
						->addParameter("kadri_id", $kadriId)
						->addParameter("year_id", $yearId)
						->addParameter("base", 1)
						->addParameter("additional", 1)
						->addParameter("premium", 1)
						->addParameter("byTime", 1)
						->build());
            }
            return true;
        }
        $this->setData("studyLoad", $studyLoad);
        $this->renderView("_study_loads/edit.tpl");
    }
    /**
     * Сохранение значений для редактирования всех нагрузок преподавателя
     */
    public function actionSaveAll() {
    	$data = CRequest::getArray("data");
    	foreach ($data as $studyLoadId=>$types) {
    		$studyLoad = CStudyLoadService::getStudyLoad($studyLoadId);
    		if (CRequest::getInt("isBudget") == 1) {
    			$kindId = CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::BUDGET)->getId();
    		}
    		if (CRequest::getInt("isContract") == 1) {
    			$kindId = CTaxonomyManager::getTaxonomy(CStudyLoadKindsConstants::TAXONOMY_HOURS_KIND)->getTerm(CStudyLoadKindsConstants::CONTRACT)->getId();
    		}
    		// удаляем старые данные
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORKLOAD_WORKS, "workload_id=".$studyLoad->getId()." and kind_id=".$kindId)->getItems() as $ar) {
    			$ar->remove();
    		}
    
    		// добавляем новые
    		foreach ($types as $typeId=>$works) {
    			foreach ($works as $kindId=>$value) {
    				$obj = new CStudyLoadWork();
    				$obj->workload_id = $studyLoadId;
    				$obj->type_id = $typeId;
    				$obj->kind_id = $kindId;
    				$obj->workload = $value;
    				$obj->save();
    			}
    		}
    	}
    	if ($this->continueEdit()) {
    		$this->redirect(UrlBuilder::newBuilder("index.php")
					->addParameter("action", "editLoadsByType")
					->addParameter("kadri_id", CRequest::getInt("kadri_id"))
					->addParameter("year_id", CRequest::getInt("year_id"))
					->addParameter("base", 1)
					->addParameter("additional", 1)
					->addParameter("premium", 1)
					->addParameter("byTime", 1)
					->addParameter("isBudget", CRequest::getInt("isBudget"))
					->addParameter("isContract", CRequest::getInt("isContract"))
					->build());
    	} else {
    		$this->redirect(UrlBuilder::newBuilder("index.php")
					->addParameter("action", "editLoads")
					->addParameter("kadri_id", CRequest::getInt("kadri_id"))
					->addParameter("year_id", CRequest::getInt("year_id"))
					->addParameter("base", 1)
					->addParameter("additional", 1)
					->addParameter("premium", 1)
					->addParameter("byTime", 1)
					->build());
    	}
    }
    public function actionInformation() {
    	// фильтр по году
    	$selectedYear = CUtils::getCurrentYear()->getId();
    	if (!is_null(CRequest::getFilter("year.id"))) {
    		$selectedYear = CRequest::getFilter("year.id");
    	}
    	 
    	// сотрудники с нагрузкой в указанном году
    	$personsWithLoad = CStudyLoadService::getPersonsWithLoadByYearForStatistic($selectedYear);
    	
    	// общее количество часов по нагрузке
    	$sumTotal = 0;
    	// общее количество ставок по приказам
    	$rateSum = 0;
    	// общее количество ставок фактически
    	$rateSumFact = 0;
    	$posts = array();
    	if (count($personsWithLoad) != 0) {
    		foreach ($personsWithLoad as $personLoad) {
    			if (!is_null($personLoad['dolgnost'])) {
    				$posts[] = $personLoad['dolgnost'];
    			}
    			$sumTotal += $personLoad['hours_sum'];
    			$rateSum += $personLoad['rate_sum'];
    			if ($personLoad['rate'] != 0) {
    				$rateSumFact += $personLoad['hours_sum']/$personLoad['rate'];
    			}
    		}
    	}
    	$values = array();
    	foreach (array_count_values($posts) as $post=>$count) {
    		$rate = 0;
    		$rateBudget = 0;
    		$rateContract = 0;
    		$rateTotal = 0;
    		if (count($personsWithLoad) != 0) {
    			foreach ($personsWithLoad as $personLoad) {
    				$person = CStaffManager::getPerson($personLoad['kadri_id']);
    				if ($personLoad['dolgnost'] == $post) {
    					if ($personLoad['rate'] != 0) {
    						$rate += $personLoad['hours_sum']/$personLoad['rate'];
    					}
    					$rateBudget += $person->getSumOrdersRateByTypeMoney(COrderConstants::TYPE_MONEY_BUDGET);
    					$rateContract += $person->getSumOrdersRateByTypeMoney(COrderConstants::TYPE_MONEY_CONTRACT);
    					$rateTotal += $person->getOrdersRate();
    				}
    			}
    		}
    		$values[] = 
    		// название должности
    		$post.";"
    		// количество человек по должностям
    		.$count.";"
    		// количество ставок по приказам (бюджет)
    		.number_format($rateBudget,2,',','').";"
    		// количество ставок по приказам (контракт)
    		.number_format($rateContract,2,',','').";"
    		// общее количество ставок по приказам
    		.number_format($rateTotal,2,',','').";"
    		// общее количество ставок фактически по нагрузке
    		.number_format($rate,2,',','');
    	}
    	$postsWithRates = array();
    	foreach ($values as $value) {
    		$postsWithRates[] = explode(";", $value);
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_study_loads/index.php",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	
    	$this->setData("personsWithLoad", $personsWithLoad);
    	$this->setData("selectedYear", $selectedYear);
    	$this->setData("sumTotal", number_format($sumTotal,2,',',''));
    	$this->setData("rateSum", number_format($rateSum,2,',',''));
    	$this->setData("rateSumFact", number_format($rateSumFact,2,',',''));
    	$this->setData("postsWithRates", $postsWithRates);
    	$this->renderView("_study_loads/info.tpl");
    }
}