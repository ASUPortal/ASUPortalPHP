<?php
class CWorkPlanLiteratureController extends CBaseController{
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
            ->from(TABLE_WORK_PLAN_LITERATURE." as t")
            ->order("t.ordering asc")
            ->condition("plan_id=".CRequest::getInt("plan_id")." AND type=".CRequest::getInt("type")." and _deleted=0");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanLiterature($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplanliterature.php?action=add&plan_id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
            "icon" => "actions/list-add.png"
        ));
        if (CRequest::getInt("type") == CWorkPlanLiteratureType::WORKPLAN_BASE_LITERATURE or CRequest::getInt("type") == CWorkPlanLiteratureType::WORKPLAN_ADDITIONAL_LITERATURE) {
            $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
            $corriculumDisciplineId = $plan->corriculumDiscipline->getId();
            $this->setData("corriculumDisciplineId", $corriculumDisciplineId);
            $this->addActionsMenuItem(array(
                "title" => "Добавить новый",
                "link" => "books.php?action=add&discipline_id=".$plan->corriculumDiscipline->getId()."&plan_id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
                "icon" => "actions/list-add.png"
            ));
        }
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/literature/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanLiterature();
        $object->plan_id = CRequest::getInt("plan_id");
        $object->type = CRequest::getInt("type");
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        // type: 1 - Основная литература, 2 - Дополнительная литература, 3 - Интернет-ресурсы
        if ($object->type == 1) {
        	$object->ordering = $plan->baseLiterature->getCount() + 1;
        } elseif($object->type == 2) {
        	$object->ordering = $plan->additionalLiterature->getCount() + 1;
        } elseif($object->type == 3) {
        	$object->ordering = $plan->internetResources->getCount() + 1;
        }
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanliterature.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/literature/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanLiterature(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanliterature.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/literature/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanLiterature(CRequest::getInt("id"));
        $plan = $object->plan;
        $type = $object->type;
        $object->markDeleted(true);
        $object->save();
        $order = 1;
        if ($object->type == 1) {
        	foreach ($plan->baseLiterature as $baseLiterature) {
        		$baseLiterature->ordering = $order++;
        		$baseLiterature->save();
        	}
        } elseif($object->type == 2) {
        	foreach ($plan->additionalLiterature as $additionalLiterature) {
        		$additionalLiterature->ordering = $order++;
        		$additionalLiterature->save();
        	}
        } elseif($object->type == 3) {
        	foreach ($plan->internetResources as $internetResources) {
        		$internetResources->ordering = $order++;
        		$internetResources->save();
        	}
        }
        $this->redirect("workplanliterature.php?action=index&plan_id=".$plan->getId()."&type=".$type);
    }
    public function actionSave() {
        $object = new CWorkPlanLiterature();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplanliterature.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplanliterature.php?action=index&plan_id=".$object->plan_id."&type=".$object->type);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/literature/edit.tpl");
    }
}