<?php

class CBiographyController extends CBaseController{
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
        $this->setPageTitle("Биография");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("biography.*")
            ->from(TABLE_BIOGRAPHY." as biography")
        	->condition("biography.user_id = ".CSession::getCurrentUser()->getId());
        $biographys = new CArrayList();
        $set->setQuery($query);
        foreach ($set->getPaginated()->getItems() as $ar) {
            $biography = new CBiography($ar);
    		$biographys->add($biography->getId(), $biography);
        }
        $this->setData("paginator", $set->getPaginator());
        $this->setData("biographys", $biographys);
        $this->renderView("_biography/index.tpl");
    }
    public function actionAdd() {
    	$biography = new CBiography();
    	$biography->user_id = CSession::getCurrentUser()->getId();
    	$this->setData("biography", $biography);
    	$this->renderView("_biography/add.tpl");
    }
    public function actionAddBiogr() {
    	$biography = new CBiography();
    	$biography->user_id = CRequest::getInt("user_id");
    	$this->setData("biography", $biography);
    	$this->renderView("_biography/add.tpl");
    }
    public function actionEdit() {
        $biography = CBiographyManager::getBiography(CRequest::getInt("id"));
        $this->setData("biography", $biography);
        $this->renderView("_biography/edit.tpl");
    }
    public function actionDelete() {
    	$biography = CBiographyManager::getBiography(CRequest::getInt("id"));
    	$biography->remove();
    	$this->redirect("index.php?action=index");
    }
    public function actionSave() {
        $biography = new CBiography();
        $biography->setAttributes(CRequest::getArray($biography::getClassName()));
        if ($biography->validate()) {
            $biography->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$biography->getId());
            } else {
                $this->redirect("index.php?action=index");
            }
            return true;
        }
        $this->setData("biography", $biography);
        $this->renderView("_biography/edit.tpl");
    }
}