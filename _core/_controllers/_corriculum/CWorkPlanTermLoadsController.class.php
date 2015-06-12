<?php
class CWorkPlanTermLoadsController extends CBaseController{
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
        $this->setPageTitle("Нагрузка по семестрам");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_TERM_LOADS." as t")
            ->order("t.id asc")
            ->condition("term_id=".CRequest::getInt("term_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanTermLoad($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить нагрузку",
            "link" => "workplantermloads.php?action=add&id=".CRequest::getInt("term_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termLoads/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanTermLoad();
        $object->term_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantermloads.php?action=index&term_id=".$object->term_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termLoads/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanTermLoad(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantermloads.php?action=index&term_id=".$object->term_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termLoads/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTermLoad(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("workplantermloads.php?action=index");
    }
    public function actionSave() {
        $object = new CWorkPlanTermLoad();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplantermloads.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplantermloads.php?action=index&term_id=".$object->term_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/termLoads/edit.tpl");
    }
}