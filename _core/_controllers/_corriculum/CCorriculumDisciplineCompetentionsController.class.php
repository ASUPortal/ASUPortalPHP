<?php
class CCorriculumDisciplineCompetentionsController extends CBaseController{
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
        $this->setPageTitle("Управление компетенциями дисциплин");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("competentions.*")
            ->from(TABLE_CORRICULUM_DISCIPLINE_COMPETENTIONS." as competentions")
            ->condition("discipline_id=".CRequest::getInt("discipline_id"));
        $set->setPageSize(PAGINATION_ALL);
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $competention = new CCorriculumDisciplineCompetention($ar);
            $objects->add($competention->getId(), $competention);
        }
        $this->addActionsMenuItem(array(
            "title" => "Добавить компетенцию",
            "link" => "competentions.php?action=add&id=".CRequest::getInt("discipline_id"),
            "icon" => "actions/list-add.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Удалить выделенные компетенции",
            "icon" => "actions/edit-delete.png",
            "form" => "#MainView",
            "link" => "competentions.php",
            "action" => "delete"
        ));
        $this->setData("objects", $objects);
        $this->renderView("_corriculum/_competentions/index.tpl");
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
            "link" => "competentions.php?action=index&discipline_id=".$object->discipline_id,
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
            "link" => "competentions.php?action=index&discipline_id=".$object->discipline_id,
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
                $this->redirect("competentions.php?action=index&discipline_id=".$object->discipline_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_competentions/edit.tpl");
    }
}