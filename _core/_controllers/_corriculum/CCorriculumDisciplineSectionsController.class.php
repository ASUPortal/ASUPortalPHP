<?php
class CCorriculumDisciplineSectionsController extends CBaseController{
	protected $_isComponent = true;
	
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
        $this->setPageTitle("Управление семестрами");

        parent::__construct();
    }
    public function actionIndex() {
        $discipline = CCorriculumsManager::getDiscipline(CRequest::getInt("discipline_id"));
        $this->addActionsMenuItem(array(
            "title" => "Добавить семестр",
            "link" => "disciplineSections.php?action=add&id=".CRequest::getInt("discipline_id"),
            "icon" => "actions/list-add.png"
        ));
        $this->setData("discipline", $discipline);
        $this->renderView("_corriculum/_disciplineSections/index.tpl");
    }
    public function actionAdd() {
        $object = new CCorriculumDisciplineSection();
        $object->discipline_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "disciplineSections.php?action=index&discipline_id=".$object->discipline_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_disciplineSections/add.tpl");
    }
    public function actionEdit() {
        $object = CCorriculumsManager::getDisciplineSection(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "disciplineSections.php?action=index&discipline_id=".$object->discipline_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_disciplineSections/edit.tpl");
    }
    public function actionDelete() {
        $object = CCorriculumsManager::getDisciplineSection(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("disciplines.php?action=edit&id=".CRequest::getInt("discipline_id"));
    }
    public function actionSave() {
        $object = new CCorriculumDisciplineSection();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("disciplineSections.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("disciplineSections.php?action=index&discipline_id=".$object->discipline_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_disciplineSections/edit.tpl");
    }
}