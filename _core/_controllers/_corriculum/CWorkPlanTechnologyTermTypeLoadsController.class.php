<?php
class CWorkPlanTechnologyTermTypeLoadsController extends CBaseController{
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
        $this->setPageTitle("Управление какими-то объектами");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_TECHNOLOGY_TERM_TYPE_LOADS." as t")
            ->order("t.id asc")
            ->condition("type_id=".CRequest::getInt("type_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanTechnologyTermTypeLoad($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplantechnologytermloads.php?action=add&id=".CRequest::getInt("type_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/technologyTermTypeLoads/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanTechnologyTermTypeLoad();
        $object->type_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantechnologytermloads.php?action=index&type_id=".$object->type_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/technologyTermTypeLoads/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanTechnologyTermTypeLoad(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantechnologytermloads.php?action=index&type_id=".$object->type_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/technologyTermTypeLoads/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTechnologyTermTypeLoad(CRequest::getInt("id"));
        $type = $object->type_id;
        $object->remove();
        $this->redirect("workplantechnologytermloads.php?action=index&type_id=".$type);
    }
    public function actionSave() {
        $object = new CWorkPlanTechnologyTermTypeLoad();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplantechnologytermloads.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplantechnologytermloads.php?action=index&type_id=".$object->type_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/technologyTermTypeLoads/edit.tpl");
    }
}