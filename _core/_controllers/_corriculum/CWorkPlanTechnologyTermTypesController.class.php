<?php
class CWorkPlanTechnologyTermTypesController extends CBaseController{
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
            ->from(TABLE_WORK_PLAN_TECHNOLOGY_TERM_TYPES." as t")
            ->order("t.id asc")
            ->condition("technology_term_id=".CRequest::getInt("term_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanTechnologyTermType($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplantechnologytermtypes.php?action=add&id=".CRequest::getInt("term_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/technologyTermType/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanTechnologyTermType();
        $object->technology_term_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantechnologytermtypes.php?action=index&term_id=".$object->technology_term_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/technologyTermType/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanTechnologyTermType(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantechnologytermtypes.php?action=index&term_id=".$object->technology_term_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/technologyTermType/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTechnologyTermType(CRequest::getInt("id"));
        $term = $object->technology_term_id;
        $object->remove();
        $this->redirect("workplantechnologytermtypes.php?action=index&term_id=".$term);
    }
    public function actionSave() {
        $object = new CWorkPlanTechnologyTermType();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplantechnologytermtypes.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplantechnologytermtypes.php?action=index&term_id=".$object->technology_term_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/technologyTermType/edit.tpl");
    }
}