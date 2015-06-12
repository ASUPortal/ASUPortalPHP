<?php
class CWorkPlanTermSectionsController extends CBaseController{
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
        $this->setPageTitle("Разделы по семестрам");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_TERM_SECTIONS." as t")
            ->order("t.id asc")
            ->condition("term_id=".CRequest::getInt("term_id"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanTermSection($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить раздел",
            "link" => "workplantermsections.php?action=add&id=".CRequest::getInt("term_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termSections/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanTermSection();
        $object->term_id = CRequest::getInt("term_id");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantermsections.php?action=index&term_id=".$object->term_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termSections/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanTermSection(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplantermsections.php?action=index&term_id=".$object->term_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/termSections/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTermSection(CRequest::getInt("id"));
        $term = $object->term_id;
        $object->remove();
        $this->redirect("workplantermsections.php?action=index&term_id=".$term);
    }
    public function actionSave() {
        $object = new CWorkPlanTermSection();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            /**
             * При сохранении раздела скопируем из семестра
             * недостающие виды нагрузки
             */
            $term = $object->term;
            $types = array();
            /**
             * @var $type CWorkPlanTermLoad
             * @var $load CWorkPlanTermSectionLoad
             */
            foreach ($term->types->getItems() as $type) {
                if (!in_array($type->type_id, $types)) {
                    $types[] = $type->type_id;
                }
            }
            foreach ($types as $loadType) {
                $exists = false;
                foreach ($object->loads->getItems() as $load) {
                    $exists |= $load->type_id == $loadType;
                }
                if (!$exists) {
                    $t = new CWorkPlanTermSectionLoad();
                    $t->section_id = $object->getId();
                    $t->type_id = $loadType;
                    $t->value = 0;
                    $t->save();
                }
            }
            if ($this->continueEdit()) {
                $this->redirect("workplantermsections.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplantermsections.php?action=index&term_id=".$object->term_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/termSections/edit.tpl");
    }
}