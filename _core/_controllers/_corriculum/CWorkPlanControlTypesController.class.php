<?php
class CWorkPlanControlTypesController extends CBaseController{
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
        $this->setPageTitle("Управление видами контроля");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_TYPES_CONTROL." as t")
            ->order("t.ordering asc")
            ->condition("section_id=".CRequest::getInt("id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanControlTypes($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
        	"title" => "Обновить",
        	"link" => "workplantypescontrol.php?action=index&id=".CRequest::getInt("id"),
        	"icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplantypescontrol.php?action=add&id=".CRequest::getInt("id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/typesControl/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanControlTypes();
        $object->section_id = CRequest::getInt("id");
        $section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $object->ordering = $section->controlTypes->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantypescontrol.php?action=index&id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/typesControl/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanControlTypes(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantypescontrol.php?action=index&id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/typesControl/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanControlTypes(CRequest::getInt("id"));
        $section = $object->section_id;
        $item = CBaseManager::getWorkPlanContentSection($section);
        $object->remove();
        $order = 1;
        foreach ($item->controlTypes as $controlType) {
        	$controlType->ordering = $order++;
        	$controlType->save();
        }
        $this->redirect("workplantypescontrol.php?action=index&id=".$section);
    }
    public function actionSave() {
        $object = new CWorkPlanControlTypes();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplantypescontrol.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplantypescontrol.php?action=index&id=".$object->section_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/typesControl/edit.tpl");
    }
}