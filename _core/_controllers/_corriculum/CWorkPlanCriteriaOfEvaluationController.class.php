<?php
class CWorkPlanCriteriaOfEvaluationController extends CBaseController{
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
        $this->setPageTitle("Управление критериями оценки");
        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_CRITERIA_OF_EVALUATION." as t")
            ->order("t.ordering asc")
            ->condition("plan_id=".CRequest::getInt("plan_id")." AND type=".CRequest::getInt("type")." and _deleted=0");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanCriteriaOfEvaluation($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplancriteriaofevaluation.php?action=add&id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/criteriaEvaluation/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanCriteriaOfEvaluation();
        $object->plan_id = CRequest::getInt("id");
        $object->type = CRequest::getInt("type");
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        if ($object->type == 1) {
        	$object->ordering = $plan->criteriaExamOfEvaluation->getCount() + 1;
        } elseif($object->type == 2) {
        	$object->ordering = $plan->criteriaCreditOfEvaluation->getCount() + 1;
        } elseif($object->type == 3) {
        	$object->ordering = $plan->criteriaMaterialsOfEvaluation->getCount() + 1;
        }
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancriteriaofevaluation.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/criteriaEvaluation/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanCriteriaOfEvaluation(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancriteriaofevaluation.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/criteriaEvaluation/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanCriteriaOfEvaluation(CRequest::getInt("id"));
        $plan = $object->plan;
        $type = $object->type;
        $object->markDeleted(true);
        $object->save();
        $order = 1;
        if ($object->type == 1) {
        	foreach ($plan->criteriaExamOfEvaluation as $criteriaExamOfEvaluation) {
        		$criteriaExamOfEvaluation->ordering = $order++;
        		$criteriaExamOfEvaluation->save();
        	}
        } elseif($object->type == 2) {
        	foreach ($plan->criteriaCreditOfEvaluation as $criteriaCreditOfEvaluation) {
        		$criteriaCreditOfEvaluation->ordering = $order++;
        		$criteriaCreditOfEvaluation->save();
        	}
        } elseif($object->type == 3) {
        	foreach ($plan->criteriaMaterialsOfEvaluation as $criteriaMaterialsOfEvaluation) {
        		$criteriaMaterialsOfEvaluation->ordering = $order++;
        		$criteriaMaterialsOfEvaluation->save();
        	}
        }
        $this->redirect("workplancriteriaofevaluation.php?action=index&plan_id=".$plan->getId()."&type=".$type);
    }
    public function actionSave() {
        $object = new CWorkPlanCriteriaOfEvaluation();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancriteriaofevaluation.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancriteriaofevaluation.php?action=index&plan_id=".$object->plan_id."&type=".$object->type);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/criteriaEvaluation/edit.tpl");
    }
}