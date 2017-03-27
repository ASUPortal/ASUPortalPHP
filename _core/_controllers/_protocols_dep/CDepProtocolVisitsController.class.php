<?php
class CDepProtocolVisitsController extends CBaseController{
	protected $_isComponent = true;
	
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
        $this->setPageTitle("Управление посещениями заседаний кафедры");

        parent::__construct();
    }
    public function actionIndex() {
    	$protocol = CProtocolManager::getDepProtocol(CRequest::getInt("protocol_id"));
    	$this->addActionsMenuItem(array(
    		"title" => "Обновить",
    		"link" => "visit.php?action=index&protocol_id=".$protocol->getId(),
    		"icon" => "actions/view-refresh.png"
    	));
    	if ($protocol->visits->getCount() == 0) {
    		$this->addActionsMenuItem(array(
    			"title" => "Добавить",
    			"link" => "visit.php?action=addReqiuredProtocolVisits&protocol_id=".$protocol->getId(),
    			"icon" => "actions/list-add.png"
    		));
    	} else {
    		$this->addActionsMenuItem(array(
    			"title" => "Добавить пропущенных",
    			"link" => "visit.php?action=addSkippedProtocolVisits&protocol_id=".$protocol->getId(),
    			"icon" => "actions/list-add.png"
    		));
    	}
    	$this->setData("protocol", $protocol);
    	$this->renderView("_protocols_dep/visit/editGroup.tpl");
    }
    public function actionAddReqiuredProtocolVisits() {
    	$protocol = CProtocolManager::getDepProtocol(CRequest::getInt("protocol_id"));
    	// добавляем сотрудников, которые должны присутствовать на заседании
    	$persons = new CArrayList();
    	foreach (CStaffManager::getAllPersons()->getItems() as $person) {
    		if ($person->hasPersonType(TYPE_PPS) and $person->hasActiveOrder()) {
    			$persons->add($person->getId(), $person);
    		}
    	}
    	foreach ($persons->getItems() as $item) {
    		$protocolVisit = new CDepProtocolVisit();
    		$protocolVisit->protocol_id = $protocol->getId();
    		$protocolVisit->kadri_id = $item->getId();
    		$protocolVisit->visit_type = 0;
    		$protocolVisit->save();
    	}
    	$this->redirect("visit.php?action=index&protocol_id=".$protocol->getId());
    }
    public function actionAddSkippedProtocolVisits() {
    	$protocol = CProtocolManager::getDepProtocol(CRequest::getInt("protocol_id"));
    	$persons = new CArrayList();
    	foreach (CStaffManager::getAllPersons()->getItems() as $person) {
    		if (!$person->hasPersonType(TYPE_PPS) or !$person->hasActiveOrder()) {
    			$persons->add($person->getId(), $person);
    		}
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "visit.php?action=index&protocol_id=".$protocol->getId(),
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->setData("protocol", $protocol);
    	$this->setData("persons", $persons);
    	$this->renderView("_protocols_dep/visit/addGroup.tpl");
    }
    public function actionSaveAdd() {
    	$protocol = CProtocolManager::getDepProtocol(CRequest::getInt("id"));
    	$arr = CRequest::getArray("CModel");
    	foreach ($arr["data"] as $personId=>$data) {
    		if ($data["visit_type"] !=0 or $data["matter_text"] != "") {
    			$protocolVisit = new CDepProtocolVisit();
    			$protocolVisit->protocol_id = $protocol->getId();
    			$protocolVisit->kadri_id = $personId;
    			$protocolVisit->visit_type = $data["visit_type"];
    			$protocolVisit->matter_text = $data["matter_text"];
    			$protocolVisit->save();
    		}
    	}
    	$this->redirect("visit.php?action=index&protocol_id=".$protocol->getId());
    }
    public function actionSaveEdit() {
    	$protocol = CProtocolManager::getDepProtocol(CRequest::getInt("id"));
    	$arr = CRequest::getArray("CModel");
    	foreach ($arr["data"] as $visitId=>$data) {
    		$protocolVisit = CBaseManager::getDepProtocolVisit($visitId);
    		$protocolVisit->protocol_id = $protocol->getId();
    		$protocolVisit->kadri_id = $data["person"];
    		$protocolVisit->visit_type = $data["visit_type"];
    		$protocolVisit->matter_text = $data["matter_text"];
    		$protocolVisit->save();
    		if ($data["skip"] == 1) {
    			$protocolVisit = CBaseManager::getDepProtocolVisit($visitId);
    			$protocolVisit->remove();
    		}
    	}
    	$this->redirect("visit.php?action=index&protocol_id=".$protocol->getId());
    }
}