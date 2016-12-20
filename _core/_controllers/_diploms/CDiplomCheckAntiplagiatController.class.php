<?php
class CDiplomCheckAntiplagiatController extends CBaseController{
    public function __construct() {
        if (!CSession::isAuth()) {
        	$action = CRequest::getString("action");
        	if ($action == "") {
        		$action = "index";
        	}
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }
        $this->_smartyEnabled = true;
        $this->setPageTitle("Проверка на антиплагиат тем ВКР");
        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_DIPLOM_CHECKS_ON_ANTIPLAGIAT." as t")
            ->condition("diplom_id=".CRequest::getInt("id"));
        $checks = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $check = new CDiplomCheckAntiplagiat($ar);
            $checks->add($check->getId(), $check);
        }
        $this->setData("checks", $checks);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_diploms/diplom_check/index.tpl");
    }
    public function actionAdd() {
        $check = new CDiplomCheckAntiplagiat();
        $check->diplom_id = CRequest::getInt("id");
        $this->setData("check", $check);
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => WEB_ROOT."_modules/_diploms/index.php?action=edit&id=".$check->diplom_id,
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->renderView("_diploms/diplom_check/add.tpl");
    }
    public function actionEdit() {
        $check = CBaseManager::getDiplomCheckAntiplagiat(CRequest::getInt("id"));
        $this->setData("check", $check);
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => WEB_ROOT."_modules/_diploms/index.php?action=edit&id=".$check->diplom_id,
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->renderView("_diploms/diplom_check/edit.tpl");
    }
    public function actionDelete() {
        $check = CBaseManager::getDiplomCheckAntiplagiat(CRequest::getInt("id"));
        $diplom = $check->diplom;
        $check->remove();
        $this->redirect("index.php?action=edit&id=".$diplom->getId());
    }
    public function actionSave() {
        $check = new CDiplomCheckAntiplagiat();
        $check->setAttributes(CRequest::getArray($check::getClassName()));
        if ($check->validate()) {
        	$check->save();
        	if ($this->continueEdit()) {
        		$this->redirect("antiplagiat.php?action=edit&id=".$check->getId());
        	} else {
        		$this->redirect("index.php?action=edit&id=".$check->diplom_id);
        	}
        	return true;
        }
        $this->setData("check", $check);
        $this->renderView("_diploms/diplom_check/edit.tpl");
    }
}