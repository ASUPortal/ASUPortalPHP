<?php
class CIndPlanLoadPublicationsController extends CBaseController{
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
        $this->setPageTitle("Управление научно-методическими публикациями");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_IND_PLAN_PUBLICATIONS." as t")
            ->order("t.id asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CIndPlanPersonPublication($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_individual_plan/publication/index.tpl");
    }
    public function actionAdd() {
        $object = new CIndPlanPersonPublication();
        $object->id_kadri = CRequest::getInt("id");
        $publications = array();
        $person = CStaffManager::getPerson($object->id_kadri);
        foreach ($person->publications->getItems() as $p) {
            $publications[$p->getId()] = $p->name;
        }
        $this->setData("publications", $publications);
        $this->setData("object", $object);
        $this->renderView("_individual_plan/publication/add.tpl");
    }
    public function actionEdit() {
        $object = CIndPlanManager::getPublication(CRequest::getInt("id"));
        $publications = array();
        $person = CStaffManager::getPerson($object->id_kadri);
        foreach ($person->publications->getItems() as $p) {
            $publications[$p->getId()] = $p->name;
        }
        $this->setData("publications", $publications);
        $this->setData("object", $object);
        $this->renderView("_individual_plan/publication/edit.tpl");
    }
    public function actionDelete() {
        $object = CIndPlanManager::getPublication(CRequest::getInt("id"));
        $person_id = $object->id_kadri;
        $object->remove();
        $this->redirect("../load.php?action=view&id=".$person_id);
    }
    public function actionSave() {
        $object = new CIndPlanPersonPublication();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("publications.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("../load.php?action=view&id=".$object->id_kadri);
            }
            return true;
        }
        $publications = array();
        $person = CStaffManager::getPerson($object->id_kadri);
        foreach ($person->publications->getItems() as $p) {
            $publications[$p->getId()] = $p->name;
        }
        $this->setData("publications", $publications);
        $this->setData("object", $object);
        $this->renderView("_individual_plan/publication/edit.tpl");
    }
}