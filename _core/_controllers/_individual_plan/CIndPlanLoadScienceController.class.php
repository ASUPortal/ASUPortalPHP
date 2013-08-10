<?php
class CIndPlanLoadScienceController extends CBaseController{
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
        $this->setPageTitle("Научно-методическая и научно-исследовательская работа");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_IND_PLAN_LOAD_SCIENCE." as t")
            ->order("t.id asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CIndPlanPersonLoadScience($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_individual_plan/science/index.tpl");
    }
    public function actionAdd() {
        $object = new CIndPlanPersonLoadScience();
        $object->id_kadri = CRequest::getInt("id");
        $this->setData("object", $object);
        $this->renderView("_individual_plan/science/add.tpl");
    }
    public function actionEdit() {
        $object = CIndPlanManager::getLoadScience(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->renderView("_individual_plan/science/edit.tpl");
    }
    public function actionDelete() {
        $object = CIndPlanManager::getLoadScience(CRequest::getInt("id"));
        $person_id = $object->id_kadri;
        $object->remove();
        $this->redirect("../load.php?action=view&id=".$person_id);
    }
    public function actionSave() {
        $object = new CIndPlanPersonLoadScience();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("sciences.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("../load.php?action=view&id=".$object->id_kadri);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_individual_plan/science/edit.tpl");
    }
}