<?php
class CWorkPlanContentSectionsController extends CBaseController{
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
        $this->setPageTitle("Содержание разделов дисциплины");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_CONTENT_SECTIONS." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanContentSection($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplancontentsections.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentSections/index.tpl");
    }
    public function actionAdd() {
        $module = CBaseManager::getWorkPlanContentModule(CRequest::getInt("id"));
        $object = new CWorkPlanContentSection();
        $object->module_id = $module->getId();
        $object->sectionIndex = $module->sections->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentmodules.php?action=index&plan_id=".$module->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentSections/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentmodules.php?action=index&plan_id=".$object->module->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentSections/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $plan = $object->module->plan_id;
        $object->remove();
        $this->redirect("workplancontentmodules.php?action=index&plan_id=".$plan);
    }
    public function actionSave() {
        $object = new CWorkPlanContentSection();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontentsections.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancontentmodules.php?action=index&plan_id=".$object->module->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/contentSections/edit.tpl");
    }
}