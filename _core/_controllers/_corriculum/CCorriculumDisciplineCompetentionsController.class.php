<?php
class CCorriculumDisciplineCompetentionsController extends CBaseController{
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
        $this->setPageTitle("Управление компетенциями дисциплин");

        parent::__construct();
    }
    public function actionAdd() {
        $object = new CCorriculumDisciplineCompetention();
        $object->discipline_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "disciplines.php?action=edit&id=".$object->discipline_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_competentions/add.tpl");
    }
    public function actionEdit() {
        $object = CCorriculumsManager::getCompetention(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "disciplines.php?action=edit&id=".$object->discipline_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_competentions/edit.tpl");
    }
    public function actionDelete() {
        $object = CCorriculumsManager::getCompetention(CRequest::getInt("id"));
        $discipline_id = $object->discipline_id;
        if (!is_null($object)) {
        	$object->remove();
        }
        $items = CRequest::getArray("selectedInView");
        if (!empty($items)) {
        	$object = CCorriculumsManager::getCompetention($items[0]);
        	$discipline_id = $object->discipline_id;
        }
        foreach ($items as $id){
        	$object = CCorriculumsManager::getCompetention($id);
        	$object->remove();
        }
        $this->redirect("disciplines.php?action=edit&id=".$discipline_id);
    }
    public function actionSave() {
        $object = new CCorriculumDisciplineCompetention();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("competentions.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("disciplines.php?action=edit&id=".$object->discipline_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_competentions/edit.tpl");
    }
}