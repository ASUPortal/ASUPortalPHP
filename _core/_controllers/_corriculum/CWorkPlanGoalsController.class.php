<?php
class CWorkPlanGoalsController extends CBaseController{
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
        $this->setPageTitle("Управление целями рабочей программы");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_GOALS." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanGoal($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplangoals.php?action=index&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/goal/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanGoal();
        $object->plan_id = CRequest::getInt("id");
        $items = array();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_GOALS, "plan_id=".CRequest::getInt("id"))->getItems() as $ar) {
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
            "link" => "workplans.php?action=edit&id=".CRequest::getInt("id"),
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/goal/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanGoal(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplans.php?action=edit&id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить задачу",
            "link" => "workplantasks.php?action=add&id=".$object->getId(),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/goal/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanGoal(CRequest::getInt("id"));
        $plan = $object->plan_id;
        $object->remove();
        $this->redirect("workplans.php?action=edit&id=".$plan);
    }
    public function actionSave() {
        $object = new CWorkPlanGoal();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplangoals.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplans.php?action=edit&id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/goal/edit.tpl");
    }
}