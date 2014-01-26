<?php
class CStaffPapersController extends CBaseController{
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
        $this->setPageTitle("Управление диссертациями сотрудников");

        parent::__construct();
    }
    public function actionAdd() {
        if (CRequest::getInt("type") == "1") {
            $object = new CPersonPHDPaper();
        } elseif (CRequest::getInt("type") == "2") {
            $object = new CPersonDoctorPaper();
        } elseif (CRequest::getInt("type") == "3") {
            $object = new CPersonDegree();
        }
        $object->kadri_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=edit&id=".$object->kadri_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_staff/paper/add.tpl");
    }
    public function actionEdit() {
        $object = CStaffManager::getPersonPaper(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=edit&id=".$object->kadri_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_staff/paper/edit.tpl");
    }
    public function actionDelete() {
        $object = CStaffManager::getPersonPaper(CRequest::getInt("id"));
        $person = $object->kadri_id;
        $object->remove();
        $this->redirect("index.php?action=edit&id=".$person);
    }
    public function actionSave() {
        $object = new CPersonPaper();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("papers.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("index.php?action=edit&id=".$object->kadri_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_staff/paper/edit.tpl");
    }
}