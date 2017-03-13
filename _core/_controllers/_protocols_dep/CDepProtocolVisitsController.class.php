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
    	
    	$visits = $protocol->visits;
    	$comparator = new CPersonComparator("fio");
    	$protocolVisits = CCollectionUtils::sort($visits, $comparator);
    	
    	$this->addActionsMenuItem(array(
    			"title" => "Обновить",
    			"link" => "visit.php?action=index&protocol_id=".$protocol->getId(),
    			"icon" => "actions/view-refresh.png"
    	));
    	
    	if ($protocol->visits->getCount() == 0) {
    		$this->addActionsMenuItem(array(
    			"title" => "Добавить",
    			"link" => "visit.php?action=addGroup&protocol_id=".$protocol->getId(),
    			"icon" => "actions/list-add.png"
    		));
    	} else {
    		$this->addActionsMenuItem(array(
    			"title" => "Редактировать",
    			"link" => "visit.php?action=editGroup&protocol_id=".$protocol->getId(),
    			"icon" => "actions/list-add.png"
    		));
    	}
    	
    	$this->setData("protocolVisits", $protocolVisits);
    	$this->renderView("_protocols_dep/visit/index.tpl");
    }
    public function actionEditGroup() {
    	$protocol = CProtocolManager::getDepProtocol(CRequest::getInt("protocol_id"));
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => "visit.php?action=index&protocol_id=".$protocol->getId(),
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->setData("protocol", $protocol);
    	$this->renderView("_protocols_dep/visit/editGroup.tpl");
    }
    public function actionAddGroup() {
    	$protocol = CProtocolManager::getDepProtocol(CRequest::getInt("protocol_id"));
    	$persons = new CArrayList();
    	foreach (CStaffManager::getAllPersons()->getItems() as $person) {
    		if ($person->hasPersonType(TYPE_PPS) and $person->hasActiveOrder()) {
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
    		$protocolVisit->visit_type = CRequest::getString($item->getId());
    		$protocolVisit->save();
    	}
    	$this->redirect("visit.php?action=index&protocol_id=".$protocol->getId());
    }
    public function actionSaveEdit() {
    	$protocol = CProtocolManager::getDepProtocol(CRequest::getInt("id"));
    	foreach ($protocol->visits->getItems() as $item) {
    		$visit = CBaseManager::getDepProtocolVisit($item->getId());
    		$visit->visit_type = CRequest::getString($item->getId());
    		$visit->save();
    	}
    	$this->redirect("visit.php?action=index&protocol_id=".$protocol->getId());
    }
}