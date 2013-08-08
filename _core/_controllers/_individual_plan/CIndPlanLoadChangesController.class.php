<?php
class CIndPlanLoadChangesController extends CBaseController{
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
        $this->setPageTitle("Управление изменениями в годовом индивидуальном плане");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_IND_PLAN_CHANGES." as t")
            ->order("t.id asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CIndPlanPersonChange($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_individual_plan/change/index.tpl");
    }
    public function actionAdd() {
        $object = new CIndPlanPersonChange();
        $object->id_kadri = CRequest::getInt("id");
        $this->setData("object", $object);
        $this->renderView("_individual_plan/change/add.tpl");
    }
    public function actionEdit() {
        $object = CIndPlanManager::getChange(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->renderView("_individual_plan/change/edit.tpl");
    }
    public function actionDelete() {
        $object = CIndPlanManager::getChange(CRequest::getInt("id"));
        $person_id = $object->id_kadri;
        $object->remove();
        $this->redirect("../load.php?action=view&id=".$person_id);
    }
    public function actionSave() {
        $object = new CIndPlanPersonChange();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("changes.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("../load.php?action=view&id=".$object->id_kadri);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_individual_plan/change/edit.tpl");
    }
}