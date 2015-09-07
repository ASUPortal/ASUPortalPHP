<?php
class CWorkPlanContentSectionLoadsController extends CBaseController{
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
        $this->setPageTitle("Нагрузка");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_CONTENT_LOADS." as t")
            ->condition("section_id=".CRequest::getInt("section_id"))
            ->order("t.id asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanContentSectionLoad($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontentloads.php?action=index&section_id=".CRequest::getInt("section_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить нагрузку",
            "link" => "workplancontentloads.php?action=add&id=".CRequest::getInt("section_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanContentSectionLoad();
        $object->section_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentloads.php?action=index&section_id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentloads.php?action=index&section_id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $section = $object->section_id;
        $object->remove();
        $this->redirect("workplancontentloads.php?action=index&section_id=".$section);
    }
    public function actionSave() {
        $object = new CWorkPlanContentSectionLoad();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontentloads.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancontentloads.php?action=index&section_id=".$object->section_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/contentLoads/edit.tpl");
    }
}