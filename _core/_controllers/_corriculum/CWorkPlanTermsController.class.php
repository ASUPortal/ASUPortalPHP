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
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        /**
         * Сформируем таблицу по всем семестрам
         * указанного учебного плана
         *
         * @var $term CWorkPlanTerm
         * @var $type CWorkPlanTermLoad
         */
        $data = array(array());
        foreach ($plan->terms->getItems() as $term) {
            foreach ($term->types->getItems() as $type) {
                $data[$type->type->getValue()][$term->number] = $type->value;
            }
        }
        unset($data[0]);
        $this->setData("data", $data);
        /**
         * Теперь соберем трудоемкость по отдельным семестрам
         * по разделам
         *
         * @var $section CWorkPlanTermSection
         * @var $load CWorkPlanTermSectionLoad
         */
        $terms = array();
        foreach ($plan->terms->getItems() as $term) {
            $termData = array(array());
            foreach ($term->sections->getItems() as $section) {
                foreach ($section->loads->getItems() as $load) {
                    $termData[$section->title][$load->type->getValue()] = $load->value;
                }
            }
            unset($termData[0]);
            $terms[$term->number] = $termData;
        }
        $this->setData("termData", $terms);
        $this->setData("plan", $plan);
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
        $this->addActionsMenuItem(array(array(
            "title" => "Назад",
            "link" => "workplanterms.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ), array(
            "title" => "Удалить семестр",
            "link" => "workplanterms.php?action=delete&id=".$object->getId(),
            "icon" => "actions/edit-delete.png"
        )));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/terms/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTerm(CRequest::getInt("id"));
        $plan = $object->plan_id;
        $object->remove();
        $this->redirect("workplanterms.php?action=index&plan_id=".$plan);
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