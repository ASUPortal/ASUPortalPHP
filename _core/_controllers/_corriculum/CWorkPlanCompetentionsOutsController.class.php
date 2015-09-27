<?php
class CWorkPlanCompetentionsOutsController extends CBaseController{
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
        $this->setPageTitle("Управление исходящими компетенциями");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_COMPETENTIONS." as t")
            ->order("t.id asc")
            ->condition("t.plan_id=".CRequest::getInt("plan_id")." and t.discipline_id != 0 and t.out != 0");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanCompetention($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить компетенцию",
            "link" => "workplancompetentionsouts.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/competentionsOuts/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanCompetention();
        $object->plan_id = CRequest::getArray("id");
        $object->out = 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancompetentionsouts.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/competentionsOuts/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanCompetention(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancompetentionsouts.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/competentionsOuts/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanCompetention(CRequest::getInt("id"));
        $plan = $object->plan_id;
        $object->remove();
        $this->redirect("workplancompetentionsouts.php?action=index&plan_id=".$plan);
    }
    public function actionSave() {
        $object = new CWorkPlanCompetention();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancompetentionsouts.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancompetentionsouts.php?action=index&plan_id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/competentionsOuts/edit.tpl");
    }
}