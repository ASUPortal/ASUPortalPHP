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
    	$personsWithLoad = CStudyLoadService::getPersonsWithLoadByYear($isBudget, $isContract, $selectedYear);
    	
    	$lectsTotal = 0;
    	$diplTotal = 0;
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
    	
        $this->setTableFilter("dataTable");
        $this->setTableSort("dataTable");
        
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
        $studyLoad->person_id = CRequest::getInt("kadri_id");
        $studyLoad->year_id = CRequest::getInt("year_id");
        $studyLoad->_created_by = CSession::getCurrentUser()->getId();
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
        $studyLoad->_created_by = CSession::getCurrentUser()->getId();
        $this->setData("studyLoad", $studyLoad);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "index.php?action=editLoads&kadri_id=".$kadriId."&year_id=".$yearId."&base=1&additional=1&premium=1&byTime=1",
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_study_loads/edit.tpl");
    }
    public function actionEditLoads() {
    	$lecturer = CStaffManager::getPerson(CRequest::getInt("kadri_id"));
    	$year = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
    	
    	$loadTypes = array();
    	$base = true;
    	if (CRequest::getInt("base") == 0) {
    		$base = false;
    	} else {
    		$loadTypes[] = CStudyLoadTypeIDConstants::MAIN;
    	}
    	$additional = true;
    	if (CRequest::getInt("additional") == 0) {
    		$additional = false;
    	} else {
    		$loadTypes[] = CStudyLoadTypeIDConstants::ADDITIONAL;
    	}
    	$premium = true;
    	if (CRequest::getInt("premium") == 0) {
    		$premium = false;
    	} else {
    		$loadTypes[] = CStudyLoadTypeIDConstants::PREMIUM;
    	}
    	$byTime = true;
    	if (CRequest::getInt("byTime") == 0) {
    		$byTime = false;
    	} else {
    		$loadTypes[] = CStudyLoadTypeIDConstants::BY_TIME;
    	}
    	if (!is_null($lecturer) and !is_null($year) and !empty($loadTypes)) {
    		$loads = CStudyLoadService::getStudyLoadsByYearAndLoadType($lecturer, $year, implode($loadTypes, ", "));
    	} else {
    		$loads = new CArrayList();
    	}
    	$this->setData("base", $base);
    	$this->setData("additional", $additional);
    	$this->setData("premium", $premium);
    	$this->setData("byTime", $byTime);
    	
    	$loadsFall = CStudyLoadService::getStudyLoadsByPart($loads, CStudyLoadYearPartsConstants::FALL);
    	$loadsSpring = CStudyLoadService::getStudyLoadsByPart($loads, CStudyLoadYearPartsConstants::SPRING);
    	
    	$this->setData("lecturer", $lecturer);
    	$this->setData("year", $year);
    	$this->setData("loadsFall", $loadsFall);
    	$this->setData("loadsSpring", $loadsSpring);
    	$this->setData("loadTypes", implode($loadTypes, ", "));
    	$this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => WEB_ROOT."_modules/_study_loads/index.php",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Добавить",
                "link" => "index.php?action=add&kadri_id=".CRequest::getInt("kadri_id")."&year_id=".CRequest::getInt("year_id"),
                "icon" => "actions/list-add.png"
            )
    	));
    	
    	$copyWays = array();
    	$copyWays[0] = "копировать с перемещением (удаляем у одного - добавляем другому)";
    	$copyWays[1] = "только копирование (сохраняем у одного и добавляем другому)";
    	$this->setData("copyWays", $copyWays);
    	$this->renderView("_study_loads/editLoads.tpl");
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
        $this->redirect("?action=editLoads&kadri_id=".$kadriId."&year_id=".$yearId."&base=".$base."&additional=".$additional."&premium=".$premium."&byTime=".$byTime);
    }
    public function actionCopy() {
    	$choice = CRequest::getInt("choice");
    	$lecturerId = CRequest::getInt("lecturer");
    	$yearId = CRequest::getInt("year");
    	$partId = CRequest::getInt("part");
    	$loadsToCopy = CRequest::getArray("selectedDoc");
    	
    	if ($lecturer != 0 and $year != 0 and $part != 0) {
    		CStudyLoadService::copySelectedLoads($choice, $lecturerId, $yearId, $partId, $loadsToCopy);
    	}
    	
    	$this->redirect("?action=editLoads&kadri_id=".CRequest::getInt("kadri_id")."&year_id=".CRequest::getInt("year_id")."&base=1&additional=1&premium=1&byTime=1");
    }
    public function actionDelete() {
    	$studyLoad = CStudyLoadService::getStudyLoad(CRequest::getInt("id"));
    	
    	// очистка кэша
    	CStudyLoadService::clearCache($studyLoad);
    	
    	$kadriId = $studyLoad->person_id;
    	$yearId = $studyLoad->year_id;
    	if (!is_null($studyLoad)) {
    		CStudyLoadService::deleteStudyLoad($studyLoad);
    	}
    	$this->redirect("?action=editLoads&kadri_id=".$kadriId."&year_id=".$yearId."&base=1&additional=1&premium=1&byTime=1");
    }
    public function actionSave() {
        $studyLoad = new CStudyLoad();
        $studyLoad->setAttributes(CRequest::getArray($studyLoad::getClassName()));
        if ($studyLoad->validate()) {
            $studyLoad->save();
            
            // очистка кэша
            CStudyLoadService::clearCache($studyLoad);
            
            $object = new CStudyLoadTable($studyLoad);
            $object->setAttributes(CRequest::getArray($object::getClassName()));
            $object->save();
            
            $kadriId = $studyLoad->person_id;
            $yearId = $studyLoad->year_id;
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$studyLoad->getId());
            } else {
                $this->redirect("?action=editLoads&kadri_id=".$kadriId."&year_id=".$yearId."&base=1&additional=1&premium=1&byTime=1");
            }
            return true;
        }
        $this->setData("studyLoad", $studyLoad);
        $this->renderView("_study_loads/edit.tpl");
    }
}