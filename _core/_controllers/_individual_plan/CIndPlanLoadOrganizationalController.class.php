<?php
class CIndPlanLoadOrganizationalController extends CBaseController{
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
        $this->setPageTitle("Управление учебной и организационно-методической работой");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_IND_PLAN_LOAD_ORGANIZATIONAL." as t")
            ->order("t.id asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CIndPlanPersonLoadOrg($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_individual_plan/organization/index.tpl");
    }
    public function actionAdd() {
        $object = new CIndPlanPersonLoadOrg();
        $object->id_kadri = CRequest::getInt("id");
        $this->setData("object", $object);
        $this->renderView("_individual_plan/organization/add.tpl");
    }
    public function actionEdit() {
        $object = CIndPlanManager::getLoadOrganizational(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->renderView("_individual_plan/organization/edit.tpl");
    }
    public function actionDelete() {
        $object = CIndPlanManager::getLoadOrganizational(CRequest::getInt("id"));
        $person_id = $object->id_kadri;
        $object->remove();
        $this->redirect("../load.php?action=view&id=".$person_id);
    }
    public function actionSave() {
        $object = new CIndPlanPersonLoadOrg();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("organizational.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("../load.php?action=view&id=".$object->id_kadri);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_individual_plan/organization/edit.tpl");
    }
}