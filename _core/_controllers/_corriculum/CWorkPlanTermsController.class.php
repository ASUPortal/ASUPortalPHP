<?php
class CWorkPlanTermsController extends CBaseController{
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
        $this->setPageTitle("Семестры");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_TERMS." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanTerm($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить семестр",
            "link" => "workplanterms.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/terms/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanTerm();
        $object->plan_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanterms.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/terms/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanTerm(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanterms.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/terms/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTerm(CRequest::getInt("id"));
        $object->remove();
        $this->redirect("workplanterms.php?action=index");
    }
    public function actionSave() {
        $object = new CWorkPlanTerm();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            /**
             * Добавим в семестр все виды нагрузки, которые есть
             * в других семестрах этого плана
             */
            $types = array();
            $plan = $object->plan;
            /**
             * @var $term CWorkPlanTerm
             * @var $type CWorkPlanTermLoad
             */
            foreach ($plan->terms->getItems() as $term) {
                foreach ($term->types->getItems() as $type) {
                    if (!in_array($type->type_id, $types)) {
                        $types[] = $type->type_id;
                    }
                }
            }
            foreach ($types as $type) {
                $t = new CWorkPlanTermLoad();
                $t->term_id = $object->getId();
                $t->type_id = $type;
                $t->value = 0;
                $t->save();
            }
            if ($this->continueEdit()) {
                $this->redirect("workplanterms.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplanterms.php?action=index&plan_id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/terms/edit.tpl");
    }
}