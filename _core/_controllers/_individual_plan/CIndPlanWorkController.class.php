<?php
class CIndPlanWorkController extends CBaseController{
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
        $this->setPageTitle("Управление нагрузкой");

        parent::__construct();
    }
    public function actionAdd() {
        if (CRequest::getInt("type") == "1") {
            $load = CIndPlanManager::getLoad(CRequest::getInt("id"));
            $object = $load->getStudyLoadTable();
        } else {
            $object = new CIndPlanPersonWork();
            $object->load_id = CRequest::getInt("id");
            $object->work_type = CRequest::getInt("type");
        }
        $this->setData("object", $object);
        $this->renderView("_individual_plan/work/add.tpl");
    }
    public function actionEdit() {
        $object = CIndPlanManager::getWork(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->renderView("_individual_plan/work/edit.tpl");
    }
    public function actionDelete() {
        $object = CIndPlanManager::getWork(CRequest::getInt("id"));
        $id = $object->load->person_id;
        $object->remove();
        $this->redirect("load.php?action=view&id=".$id);
    }
    public function actionSave() {
        $arr = CRequest::getArray("CModel");
        if ($arr["work_type"] == "1") {
            $load = CIndPlanManager::getLoad($arr["load_id"]);
            $object = new CIndPlanPersonLoadTable($load);
            $object->setAttributes(CRequest::getArray($object::getClassName()));
            if ($object->validate()) {
                $object->save();
                if ($this->continueEdit()) {
                    $this->redirect("work.php?action=add&id=".$object->getLoad()->getId()."&type=1");
                } else {
                    $this->redirect("load.php?action=view&id=".$object->getLoad()->person_id);
                }
                return true;
            }
        } else {
            $object = new CIndPlanPersonWork();
            $object->setAttributes(CRequest::getArray($object::getClassName()));
            if ($object->validate()) {
                $object->save();
                if ($this->continueEdit()) {
                    $this->redirect("work.php?action=edit&id=".$object->getId());
                } else {
                    $this->redirect("load.php?action=view&id=".$object->load->person_id);
                }
                return true;
            }
        }
        $this->setData("object", $object);
        $this->renderView("_individual_plan/work/edit.tpl");
    }
}