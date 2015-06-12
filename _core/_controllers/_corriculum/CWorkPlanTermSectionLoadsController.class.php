<?php
class CWorkPlanTermSectionLoadsController extends CBaseController{
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
        $this->setPageTitle("Количество часов в разделе");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_TERM_SECTION_LOADS." as t")
            ->order("t.id asc")
            ->condition("section_id=".CRequest::getInt("section_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanTermSectionLoad($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить нагрузку",
            "link" => "workplantermsectionloads.php?action=add&id=".CRequest::getInt("section_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termSectionLoads/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanTermSectionLoad();
        $object->section_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantermsectionloads.php?action=index&section_id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termSectionLoads/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanTermSectionLoad(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantermsectionloads.php?action=index&section_id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termSectionLoads/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTermSectionLoad(CRequest::getInt("id"));
        $section = $object->section_id;
        $object->remove();
        $this->redirect("workplantermsectionloads.php?action=index&section_id=".$section);
    }
    public function actionSave() {
        $object = new CWorkPlanTermSectionLoad();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplantermsectionloads.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplantermsectionloads.php?action=index&section_id=".$object->section_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/termSectionLoads/edit.tpl");
    }
}