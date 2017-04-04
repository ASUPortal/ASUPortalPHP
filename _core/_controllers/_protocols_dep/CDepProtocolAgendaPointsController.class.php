<?php
class CDepProtocolAgendaPointsController extends CBaseController{
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
        $this->setPageTitle("Управление пунктами повестки");

        parent::__construct();
    }
    public function actionIndex() {
    	$set = new CRecordSet();
    	$query = new CQuery();
    	$query->select("point.*")
	    	->from(TABLE_DEP_PROTOCOL_AGENDA." as point")
	    	->condition("protocol_id=".CRequest::getInt("protocol_id"))
    		->order("point.ordering asc");
    	$set->setQuery($query);
    	$set->setPageSize(PAGINATION_ALL);
    	
    	$protocolPoints = new CArrayList();
    	foreach ($set->getPaginated()->getItems() as $item) {
    		$point = new CDepProtocolAgendaPoint($item);
    		$protocolPoints->add($point->getId(), $point);
    	}
    	$this->addActionsMenuItem(array(
    		"title" => "Обновить",
    		"link" => "point.php?action=index&protocol_id=".CRequest::getInt("protocol_id"),
    		"icon" => "actions/view-refresh.png"
    	));
    	$this->addActionsMenuItem(array(
    		"title" => "Добавить",
    		"link" => "point.php?action=add&protocol_id=".CRequest::getInt("protocol_id"),
    		"icon" => "actions/list-add.png"
    	));
    	$this->setData("protocolPoints", $protocolPoints);
    	$this->renderView("_protocols_dep/agendaPoint/index.tpl");
    }
    public function actionAdd() {
    	$protocol = CProtocolManager::getDepProtocol(CRequest::getInt("protocol_id"));
        $protocolPoint = new CDepProtocolAgendaPoint();
        $protocolPoint->protocol_id = $protocol->getId();
        if ($protocol->agenda->isEmpty()) {
        	$protocolPoint->ordering = 1;
        } else {
        	$protocolPoint->ordering = $protocol->agenda->getCount() + 1;
        }
        $this->setData("protocolPoint", $protocolPoint);
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "point.php?action=index&protocol_id=".$protocolPoint->protocol_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->renderView("_protocols_dep/agendaPoint/add.tpl");
    }
    public function actionEdit() {
        $protocolPoint = CBaseManager::getDepProtocolAgendaPoint(CRequest::getInt("id"));
        $this->setData("protocolPoint", $protocolPoint);
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "point.php?action=index&protocol_id=".$protocolPoint->protocol_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->renderView("_protocols_dep/agendaPoint/edit.tpl");
    }
    public function actionDelete() {
        $protocolPoint = CBaseManager::getDepProtocolAgendaPoint(CRequest::getInt("id"));
        $porotocolId = $protocolPoint->protocol_id;
        $protocolPoint->remove();
        $this->redirect("point.php?action=index&protocol_id=".$porotocolId);
    }
    public function actionSave() {
        $protocolPoint = new CDepProtocolAgendaPoint();
        $protocolPoint->setAttributes(CRequest::getArray($protocolPoint::getClassName()));
        if ($protocolPoint->validate()) {
            $protocolPoint->save();
            if ($this->continueEdit()) {
                $this->redirect("point.php?action=edit&id=".$protocolPoint->getId());
            } else {
                $this->redirect("point.php?action=index&protocol_id=".$protocolPoint->protocol_id);
            }
            return true;
        }
        $this->setData("protocolPoint", $protocolPoint);
        $this->renderView("_protocols_dep/agendaPoint/edit.tpl");
    }
}