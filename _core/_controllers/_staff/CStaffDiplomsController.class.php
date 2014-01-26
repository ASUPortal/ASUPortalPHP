<?php
class CStaffDiplomsController extends CBaseController{
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
        $this->setPageTitle("Управление дипломами сотрудников");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CPersonDiplom();
        $object->kadri_id = CRequest::getInt("id");
        $this->setData("object", $object);
        $this->renderView("_staff/diplom/add.tpl");
    }
    public function actionEdit() {
        $object = CStaffManager::getPersonDiplom(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->renderView("_staff/diplom/edit.tpl");
    }
    public function actionDelete() {
        $object = CStaffManager::getPersonDiplom(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("diploms.php?action=index");
    }
    public function actionSave() {
        $object = new CPersonDiplom();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("diploms.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("index.php?action=edit&id=".$object->kadri_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_staff/diplom/edit.tpl");
    }
}