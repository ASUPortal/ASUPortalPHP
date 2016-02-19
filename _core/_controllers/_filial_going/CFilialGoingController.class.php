<?php

class CFilialGoingController extends CBaseController{
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
        $this->setPageTitle("Командировочные удостоверения");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("filial_going.*")
            ->from(TABLE_FILIAL_GOING." as filial_going")
            ->order("filial_going.day_start desc");
        if (CRequest::getString("order") == "person.fio") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_PERSON." as person", "filial_going.kadri_id=person.id");
        		$query->order("person.fio ".$direction);
        } elseif (CRequest::getString("order") == "filial.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_FILIALS." as filial", "filial_going.filial_id=filial.id");
        		$query->order("filial.name ".$direction);
        } elseif (CRequest::getString("order") == "filial_act.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_FILIAL_ACTIONS." as filial_act", "filial_going.filial_act_id=filial_act.id");
        		$query->order("filial_act.name ".$direction);
        } elseif (CRequest::getString("order") == "transport.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_TRANSPORT." as transport", "filial_going.transport_type_id=transport.id");
        		$query->order("transport.name ".$direction);
        }
        $set->setQuery($query);
        $filialGoings = new CArrayList();
        $set->setQuery($query);
        foreach ($set->getPaginated()->getItems() as $ar) {
            $filialGoing = new CFilialGoing($ar);
    		$filialGoings->add($filialGoing->getId(), $filialGoing);
        }
        $this->addActionsMenuItem(array(
        	"title" => "Добавить",
        	"link" => "index.php?action=add",
        	"icon" => "actions/list-add.png"
        ));
        /**
         * Параметры для групповой печати по шаблону
         */
        $this->setData("template", "formset_filial_goings");
        $this->setData("selectedDoc", true);
        $this->setData("url", null);
        $this->setData("action", null);
        $this->setData("id", null);
        
        $this->setData("paginator", $set->getPaginator());
        $this->setData("filialGoings", $filialGoings);
        $this->renderView("_filial_going/index.tpl");
    }
    public function actionAdd() {
    	$filialGoing = new CFilialGoing();
    	$this->addActionsMenuItem(array(
    		"title" => "Назад",
    		"link" => "index.php?action=index",
    		"icon" => "actions/edit-undo.png"
    	));
    	$this->setData("filialGoing", $filialGoing);
    	$this->renderView("_filial_going/add.tpl");
    }
    public function actionEdit() {
        $filialGoing = CBaseManager::getFilialGoing(CRequest::getInt("id"));
    	$this->addActionsMenuItem(array(
    		"title" => "Назад",
    		"link" => "index.php?action=index",
    		"icon" => "actions/edit-undo.png"
    	));
    	$this->addActionsMenuItem(array(
    		"title" => "Печать по шаблону",
    		"link" => "#",
    		"icon" => "devices/printer.png",
    		"template" => "formset_filial_goings"
    	));
        $this->setData("filialGoing", $filialGoing);
        $this->renderView("_filial_going/edit.tpl");
    }
    public function actionDelete() {
    	$filialGoing = CBaseManager::getFilialGoing(CRequest::getInt("id"));
    	$filialGoing->remove();
    	$this->redirect("index.php?action=index");
    }
    public function actionSave() {
        $filialGoing = new CFilialGoing();
        $filialGoing->setAttributes(CRequest::getArray($filialGoing::getClassName()));
        if ($filialGoing->validate()) {
            $filialGoing->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$filialGoing->getId());
            } else {
                $this->redirect("index.php?action=index");
            }
            return true;
        }
        $this->setData("filialGoing", $filialGoing);
        $this->renderView("_filial_going/edit.tpl");
    }
}