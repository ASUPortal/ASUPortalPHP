<?php
class CWorkPlanBRSController extends CBaseController{
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
            ->from(TABLE_WORK_PLAN_BRS." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanBRS($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplanbrs.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/brs/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanBRS();
        $object->plan_id = CRequest::getInt("id");
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $object->ordering = $plan->BRS->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanbrs.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/brs/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanBRS(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanbrs.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/brs/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanBRS(CRequest::getInt("id"));
        $plan = $object->plan;
        $object->remove();
        $order = 1;
        foreach ($plan->BRS as $brs) {
        	$brs->ordering = $order++;
        	$brs->save();
        }
        $this->redirect("workplanbrs.php?action=index&plan_id=".$plan->getId());
    }
    public function actionSave() {
        $object = new CWorkPlanBRS();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplanbrs.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplanbrs.php?action=index&plan_id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/brs/edit.tpl");
    }
}