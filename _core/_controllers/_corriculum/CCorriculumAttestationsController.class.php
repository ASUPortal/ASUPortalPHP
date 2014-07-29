<?php
class CCorriculumAttestationsController extends CBaseController{
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
        $this->setPageTitle("Управление какими-то объектами");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CCorriculumAttestation();
        $object->corriculum_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=edit&id=".$object->corriculum_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_attestations/add.tpl");
    }
    public function actionEdit() {
        $object = CCorriculumsManager::getAttestation(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "index.php?action=edit&id=".$object->corriculum_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_attestations/edit.tpl");
    }
    public function actionDelete() {
        $object = CCorriculumsManager::getAttestation(CRequest::getInt("id"));
        $id = $object->corriculum_id;
        $object->remove();
        $this->redirect("index.php?action=edit&id=".$id);
    }
    public function actionSave() {
        $object = new CCorriculumAttestation();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("attestations.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("index.php?action=edit&id=".$object->corriculum_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_attestations/edit.tpl");
    }
}