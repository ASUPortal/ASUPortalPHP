<?php
class CIndPlanLoadTeachingController extends CBaseController{
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
        $this->setPageTitle("Распределение нагрузки по видам работ");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CIndPlanPersonLoadTeaching();
        $object->kadri_id = CRequest::getInt("id");
        $object->year_id = CRequest::getInt("year");
        $this->setData("object", $object);
        $this->renderView("_individual_plan/teaching/add.tpl");
    }
    public function actionEdit() {
        $object = CIndPlanManager::getLoadTeaching(CRequest::getInt("id"), CRequest::getInt("year"));
        $this->setData("object", $object);
        $this->renderView("_individual_plan/teaching/edit.tpl");
    }
    public function actionDelete() {
        $object = CIndPlanManager::getLoadTeaching(CRequest::getInt("id"), CRequest::getInt("year"));
        $object->remove();
        $this->redirect("teaching.php?action=index");
    }
    public function actionSave() {
        $object = new CIndPlanPersonLoadTeaching();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("teaching.php?action=edit&id=".$object->kadri_id."&year=".$object->year_id);
            } else {
                $this->redirect("../load.php?action=view&id=".$object->kadri_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_individual_plan/teaching/edit.tpl");
    }
}