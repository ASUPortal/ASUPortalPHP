<?php
class CWorkPlanAdditionalSupplyController extends CBaseController{
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
            ->from(TABLE_WORK_PLAN_ADDITIONAL_SUPPLY." as t")
            ->order("t.ordering asc")
            ->condition("plan_id=".CRequest::getInt("plan_id")." and _deleted=0");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanAdditionalSupply($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplansupplies.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/additionalSupply/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanAdditionalSupply();
        $object->plan_id = CRequest::getInt("id");
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $object->ordering = $plan->additionalSupply->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplansupplies.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/additionalSupply/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanAdditionalSupply(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplansupplies.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/additionalSupply/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanAdditionalSupply(CRequest::getInt("id"));
        $plan = $object->plan;
        $object->markDeleted(true);
        $object->save();
        $order = 1;
        foreach ($plan->additionalSupply as $add) {
        	$add->ordering = $order++;
        	$add->save();
        }
        $this->redirect("workplansupplies.php?action=index&plan_id=".$plan->getId());
    }
    public function actionSave() {
        $object = new CWorkPlanAdditionalSupply();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplansupplies.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplansupplies.php?action=index&plan_id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/additionalSupply/edit.tpl");
    }
}