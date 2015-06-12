<?php
class CWorkPlanContentControlsController extends CBaseController{
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
        $this->setPageTitle("Формы текущего контроля");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_CONTENT_CONTROLS." as t")
            ->order("t.id asc")
            ->condition("section_id=".CRequest::getInt("section_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanContentControl($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить форму контроля",
            "link" => "workplancontentcontrols.php?action=add&id=".CRequest::getInt("section_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentControls/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanContentControl();
        $object->section_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentcontrols.php?action=index&section_id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentControls/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanContentControl(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentcontrols.php?action=index&section_id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentControls/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanContentControl(CRequest::getInt("id"));
        $section = $object->section_id;
        $object->remove();
        $this->redirect("workplancontentcontrols.php?action=index&section_id=".$section);
    }
    public function actionSave() {
        $object = new CWorkPlanContentControl();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontentcontrols.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancontentcontrols.php?action=index&section_id=".$object->section_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/contentControls/edit.tpl");
    }
}