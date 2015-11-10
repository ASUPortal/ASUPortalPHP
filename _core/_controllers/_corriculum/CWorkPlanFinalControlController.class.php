<?php
class CWorkPlanFinalControlController extends CBaseController{
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
        $this->setPageTitle("Итоговый контроль");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_FINAL_CONTROL." as t")
            ->condition("plan_id=".CRequest::getInt("plan_id"))
            ->order("t.ordering asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanFinalControl($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
        	"title" => "Обновить",
        	"link" => "workplanfinalcontrol.php?action=index&plan_id=".CRequest::getInt("plan_id"),
        	"icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplanfinalcontrol.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/finalControl/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanFinalControl();
        $object->plan_id = CRequest::getInt("id");
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $object->ordering = $plan->finalControls->getCount() + 1;
        $this->setData("object", $object);
        /** 
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanfinalcontrol.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/finalControl/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanFinalControl(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanfinalcontrol.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/finalControl/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanFinalControl(CRequest::getInt("id"));
        $plan = $object->plan;
        $object->remove();
        $order = 1;
        foreach ($plan->finalControls as $finalControl) {
        	$finalControl->ordering = $order++;
        	$finalControl->save();
        }
        $this->redirect("workplanfinalcontrol.php?action=index&plan_id=".$plan->getId());
    }
    public function actionSave() {
        $object = new CWorkPlanFinalControl();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplanfinalcontrol.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplanfinalcontrol.php?action=index&plan_id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/finalControl/edit.tpl");
    }
}