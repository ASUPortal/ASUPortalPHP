<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 01.04.13
 * Time: 9:48
 * To change this template use File | Settings | File Templates.
 */

class CGrantsController extends CBaseController{
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
        $this->setPageTitle("Гранты и все такое");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("grant.*")
            ->from(TABLE_GRANTS." as grant")
            ->order("grant.id desc");
        $set->setQuery($query);
        $grants = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $grant = new CGrant($ar);
            $grants->add($grant->getId(), $grant);
        }
        $this->setData("grants", $grants);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_grants/index.tpl");
    }
    public function actionAdd() {
        $grant = new CGrant();
        $this->setData("grant", $grant);
        $this->renderView("_grants/add.tpl");
    }
    public function actionEdit() {
        $grant = CGrantManager::getGrant(CRequest::getInt("id"));
        $this->setData("grant", $grant);
        $this->renderView("_grants/edit.tpl");
    }
    public function actionDelete() {
        $grant = CGrantManager::getGrant(CRequest::getInt("id"));
        $grant->remove();
        $this->redirect("?action=index");
    }
    public function actionSave() {
        $grant = new CGrant();
        $grant->setAttributes(CRequest::getArray($grant::getClassName()));
        if ($grant->validate()) {
            $grant->save();
            $this->redirect("?action=index");
            return true;
        }
        $this->setData("grant", $grant);
        $this->renderView("_grants/edit.tpl");
    }
    public function actionSearch() {

    }
}