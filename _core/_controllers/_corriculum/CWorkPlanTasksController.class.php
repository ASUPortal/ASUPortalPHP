<?php
class CWorkPlanTasksController extends CBaseController{
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
        $this->setPageTitle("Управление задачами рабочих программ");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_TASKS." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id"));;
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanTask($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplantasks.php?action=index&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/task/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanTask();
        $goal = CBaseManager::getWorkPlanGoal(CRequest::getInt("id"));
        $object->goal_id = $goal->getId();
        $object->plan_id = $goal->plan->getId();
        $items = array();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_TASKS, "goal_id=".CRequest::getInt("id"))->getItems() as $ar) {
        	$item = new CActiveModel($ar);
        	$items[] = $item->ordering;
        }
        if (!empty($items)) {
        	$object->ordering = max($items)+1;
        } else {
        	$object->ordering = 1;
        }
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplangoals.php?action=edit&id=".$object->goal_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/task/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanTask(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplangoals.php?action=edit&id=".$object->goal_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/task/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTask(CRequest::getInt("id"));
        $goal = $object->goal_id;
        $object->remove();
        $this->redirect("workplangoals.php?action=edit&id=".$goal);
    }
    public function actionSave() {
        $object = new CWorkPlanTask();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplantasks.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplangoals.php?action=edit&id=".$object->goal_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/task/edit.tpl");
    }
}