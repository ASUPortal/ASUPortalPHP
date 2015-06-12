<?php
class CWorkPlanTermPracticesController extends CBaseController{
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
        $this->setPageTitle("Практики");

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
            ->from(TABLE_WORK_PLAN_TERM_PRACTICES." as t")
            ->order("t.id asc")
            ->innerJoin(TABLE_WORK_PLAN_TERMS." as term", "term.id = t.term_id")
            ->condition("term.plan_id=".CRequest::getInt("plan_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanTermPractice($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить практику",
            "link" => "workplantermpractices.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termPractices/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanTermPractice();
        $this->setData("object", $object);
        $this->setData("terms", $this->getTermsList(CRequest::getInt("id")));
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantermpractices.php?action=index&plan_id=".CRequest::getInt("id"),
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termPractices/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanTermPractice(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->setData("terms", $this->getTermsList($object->term->plan_id));
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantermpractices.php?action=index&plan_id".$object->term->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termPractices/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTermPractice(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("workplantermpractices.php?action=index");
    }
    public function actionSave() {
        $object = new CWorkPlanTermPractice();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->setData("terms", $this->getTermsList($object->term->plan_id));
                $this->redirect("workplantermpractices.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplantermpractices.php?action=index&plan_id=".$object->term->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/termPractices/edit.tpl");
    }
}