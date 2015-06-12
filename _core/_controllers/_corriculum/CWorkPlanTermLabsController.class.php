<?php
class CWorkPlanTermLabsController extends CBaseController{
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
        $this->setPageTitle("Лабораторные работы");

        parent::__construct();
    }

    /**
     * Названия семестров для подстановки
     *
     * @param $plan_id
     * @return array
     */
    private function getTermsList($plan_id) {
        /**
         * @var $plan CWorkPlan
         * @var $term CWorkPlanTerm
         */
        $plan = CBaseManager::getWorkPlan($plan_id);
        $result = array();
        foreach ($plan->terms->getItems() as $term) {
            $result[$term->getId()] = $term->number;
        }
        return $result;
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_TERM_LABS." as t")
            ->order("t.id asc")
            ->innerJoin(TABLE_WORK_PLAN_TERMS." as term", "t.term_id = term.id")
            ->condition("term.plan_id=".CRequest::getInt("plan_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanTermLab($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplantermlabs.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termLabs/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanTermLab();
        $this->setData("object", $object);
        $this->setData("terms", $this->getTermsList(CRequest::getInt("id")));
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantermlabs.php?action=index&plan_id=".CRequest::getInt("id"),
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termLabs/add.tpl");
    }
    public function actionEdit() {
        /**
         * @var $object CWorkPlanTermLab
         */
        $object = CBaseManager::getWorkPlanTermLab(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->setData("terms", $this->getTermsList($object->term->plan_id));
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantermlabs.php?action=index&plan_id=".$object->term->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termLabs/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTermLab(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("workplantermlabs.php?action=index");
    }
    public function actionSave() {
        $object = new CWorkPlanTermLab();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->setData("terms", $this->getTermsList($object->term->plan_id));
                $this->redirect("workplantermlabs.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplantermlabs.php?action=index&plan_id=".$object->term->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/termLabs/edit.tpl");
    }
}