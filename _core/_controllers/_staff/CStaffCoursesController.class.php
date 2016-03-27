<?php
class CStaffCoursesController extends CBaseController{
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
        $this->setPageTitle("Управление курсами повышения квалификации");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CPersonCourse();
        $object->kadri_id = CRequest::getInt("id");
        $this->setData("object", $object);
        $this->renderView("_staff/course/add.tpl");
    }
    public function actionEdit() {
        $object = CStaffManager::getPersonCourse(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->renderView("_staff/course/edit.tpl");
    }
    public function actionDelete() {
        $object = CStaffManager::getPersonCourse(CRequest::getInt("id"));
        $person = $object->kadri_id;
        $object->remove();
        $this->redirect("index.php?action=edit&id=".$person);
    }
    public function actionSave() {
        $object = new CPersonCourse();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
        	// дату нужно сконвертить в MySQL date
        	$object->date_start = date("Y-m-d", strtotime($object->date_start));
        	$object->date_end = date("Y-m-d", strtotime($object->date_end));
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("courses.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("index.php?action=edit&id=".$object->kadri_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_staff/course/edit.tpl");
    }
}