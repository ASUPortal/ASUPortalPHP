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
            ->order("diplom.id desc");
        $set->setQuery($query);
        $diploms = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $diplom = new CDiplom($item);
            $diploms->add($diplom->getId(), $diplom);
        }
        $this->setData("diploms", $diploms);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_diploms/index.tpl");
    }
    public function actionAdd() {
        $diplom = new CDiplom();
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
        $students = CStaffManager::getAllStudentsThisYearList();
        $reviewers = CStaffManager::getPersonsListWithType(TYPE_REVIEWER);
        $this->setData("reviewers", $reviewers);
        $this->setData("students", $students);
        $this->setData("diplomLookup", "");
        $this->setData("commissions", $commissions);
        $this->setData("diplom", $diplom);
        $this->renderView("_diploms/add.tpl");
    }
    public function actionEdit() {
        $diplom = CStaffManager::getDiplom(CRequest::getInt("id"));
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
        $students = CStaffManager::getAllStudentsThisYearList();
        if (!array_key_exists($diplom->student_id, $students)) {
            $student = CStaffManager::getStudent($diplom->student_id);
            if (!is_null($student)) {
                $nv = $student->getName();
                if (!is_null($student->getGroup())) {
                    $nv .= " (".$student->getGroup()->getName().")";
                }
                $students[$student->getId()] = $nv;
            }
        }
        $reviewers = CStaffManager::getPersonsListWithType(TYPE_REVIEWER);
        if (!array_key_exists($diplom->recenz_id, $reviewers)) {
            $reviewer = CStaffManager::getPerson($diplom->recenz_id);
            if (!is_null($reviewer)) {
                $reviewers[$reviewer->getId()] = $reviewer->getName();
            }
        }
        $diplomLookup = "";
        if (!is_null($diplom->practPlace)) {
            $diplomLookup = $diplom->practPlace->getValue();
        }
        $this->setData("diplomLookup", $diplomLookup);
        $this->setData("reviewers", $reviewers);
        $this->setData("students", $students);
        $this->setData("commissions", $commissions);
        $this->setData("diplom", $diplom);
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
                $this->redirect(WEB_ROOT."diploms_view.php");
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
        $students = CStaffManager::getAllStudentsThisYearList();
        if (!array_key_exists($diplom->student_id, $students)) {
            $student = CStaffManager::getStudent($diplom->student_id);
            if (!is_null($student)) {
                $nv = $student->getName();
                if (!is_null($student->getGroup())) {
                    $nv .= " (".$student->getGroup()->getName().")";
                }
                $students[$student->getId()] = $nv;
            }
        }
        $reviewers = CStaffManager::getPersonsListWithType(TYPE_REVIEWER);
        if (!array_key_exists($diplom->recenz_id, $reviewers)) {
            $reviewer = CStaffManager::getPerson($diplom->recenz_id);
            if (!is_null($reviewer)) {
                $reviewers[$reviewer->getId()] = $reviewer->getName();
            }
        }
        $diplomLookup = "";
        if (!is_null($diplom->practPlace)) {
            $diplomLookup = $diplom->practPlace->getValue();
        }
        $this->setData("diplomLookup", $diplomLookup);        
        $this->setData("reviewers", $reviewers);
        $this->setData("students", $students);
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
}
