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
    		if (!(!$isBudget and !$isContract)) {
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
    	}

    	// сотрудники без нагрузки в указанном году
    	$personsWithoutLoad = CStudyLoadService::getPersonsWithoutLoadByYear($selectedYear);
		
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
        $studyLoad->kadri_id = CRequest::getInt("kadri_id");
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
        $kadriId = $studyLoad->kadri_id;
        $yearId = $studyLoad->year_id;
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
    	
    	$loads = CStudyLoadService::addTotalLoads($lecturer, $year);
    	
    	$loadsFall = CStudyLoadService::getStudyLoadsByPart($loads, 1);
    	$loadsSpring = CStudyLoadService::getStudyLoadsByPart($loads, 2);
    	
    	$this->setData("loadsFall", $loadsFall);
    	$this->setData("loadsSpring", $loadsSpring);
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
    	$this->renderView("_study_loads/editLoads.tpl");
    }
    public function actionDelete() {
    	$studyLoad = CStudyLoadService::getStudyLoad(CRequest::getInt("id"));
    	$kadriId = $studyLoad->kadri_id;
    	$yearId = $studyLoad->year_id;
    	if (!is_null($studyLoad)) {
    		CStudyLoadService::deleteStudyLoad($studyLoad);
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