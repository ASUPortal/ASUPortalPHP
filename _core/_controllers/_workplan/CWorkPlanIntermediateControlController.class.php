<?php
class CWorkPlanIntermediateControlController extends CBaseController{
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
        $this->setPageTitle("Промежуточный контроль");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_INTERMEDIATE_CONTROL." as t")
            ->condition("plan_id=".CRequest::getInt("plan_id")." and _deleted=0")
            ->order("t.ordering asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanIntermediateControl($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
        	"title" => "Обновить",
        	"link" => "workplanintermediatecontrol.php?action=index&plan_id=".CRequest::getInt("plan_id"),
        	"icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplanintermediatecontrol.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/intermediateControl/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanIntermediateControl();
        $object->plan_id = CRequest::getInt("id");
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $object->ordering = $plan->intermediateControls->getCount() + 1;
        $this->setData("object", $object);
        /** 
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanintermediatecontrol.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/intermediateControl/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanIntermediateControl(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanintermediatecontrol.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/intermediateControl/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanIntermediateControl(CRequest::getInt("id"));
        $plan = $object->plan;
        $object->markDeleted(true);
        $object->save();
        $order = 1;
        foreach ($plan->intermediateControls as $intermediateControl) {
        	$intermediateControl->ordering = $order++;
        	$intermediateControl->save();
        }
        $this->redirect("workplanintermediatecontrol.php?action=index&plan_id=".$plan->getId());
    }
    public function actionSave() {
        $object = new CWorkPlanIntermediateControl();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplanintermediatecontrol.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplanintermediatecontrol.php?action=index&plan_id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/intermediateControl/edit.tpl");
    }
}