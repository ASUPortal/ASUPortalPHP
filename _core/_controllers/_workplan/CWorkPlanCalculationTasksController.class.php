<?php
class CWorkPlanCalculationTasksController extends CBaseController{
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
        $this->setPageTitle("Управление расчётными заданиями");

        parent::__construct();
    }
    public function actionIndex() {
    	$section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_CALCULATION_TASKS." as t")
            ->order("t.ordering asc")
            ->condition("section_id=".CRequest::getInt("id")." and plan_id=".$section->category->plan_id." and _deleted=0");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanCalculationTask($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
        	"title" => "Обновить",
        	"link" => "workplancalculationtasks.php?action=index&id=".CRequest::getInt("id"),
        	"icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplancalculationtasks.php?action=add&id=".CRequest::getInt("id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/calculationTasks/index.tpl");
    }
    public function actionView() {
    	$set = new CRecordSet();
    	$query = new CQuery();
    	$set->setQuery($query);
    	$query->select("t.*")
	    	->from(TABLE_WORK_PLAN_CALCULATION_TASKS." as t")
	    	->order("t.ordering asc")
	    	->condition("plan_id=".CRequest::getInt("plan_id")." and _deleted=0");
    	$objects = new CArrayList();
    	foreach ($set->getPaginated()->getItems() as $ar) {
    		$object = new CWorkPlanCalculationTask($ar);
    		$objects->add($object->getId(), $object);
    	}
    	$this->setData("objects", $objects);
    	$this->setData("paginator", $set->getPaginator());
    	/**
    	 * Отображение представления
    	*/
    	$this->renderView("_corriculum/_workplan/calculationTasks/view.tpl");
    }
    public function actionAdd() {
    	$section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
    	$plan = CWorkPlanManager::getWorkplan($section->category->plan_id);
        $object = new CWorkPlanCalculationTask();
        $object->section_id = CRequest::getInt("id");
        $object->plan_id = $section->category->plan_id;
        $object->ordering = $section->calculationTasks->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancalculationtasks.php?action=index&id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/calculationTasks/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanCalculationTask(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancalculationtasks.php?action=index&id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/calculationTasks/edit.tpl");
    }
    public function actionDelete() {
        if (CRequest::getInt("view") == 1) {
        	$object = CBaseManager::getWorkPlanCalculationTask(CRequest::getInt("id"));
        	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        	$object->markDeleted(true);
        	$object->save();
        	$order = 1;
        	foreach ($plan->calculationTasks as $calculationTask) {
        		$calculationTask->ordering = $order++;
        		$calculationTask->save();
        	}
        	$this->redirect("workplancalculationtasks.php?action=view&plan_id=".$plan->getId());
        } else {
        	$object = CBaseManager::getWorkPlanCalculationTask(CRequest::getInt("id"));
        	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        	$section = $object->section;
        	$object->markDeleted(true);
        	$object->save();
        	$order = 1;
        	foreach ($section->calculationTasks as $calculationTask) {
        		$calculationTask->ordering = $order++;
        		$calculationTask->save();
        	}
        	$this->redirect("workplancalculationtasks.php?action=index&id=".$section->getId());
        }
    }
    public function actionSave() {
        $object = new CWorkPlanCalculationTask();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancalculationtasks.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancalculationtasks.php?action=index&id=".$object->section_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/calculationTasks/edit.tpl");
    }
}