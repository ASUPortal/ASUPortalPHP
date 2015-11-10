<?php
class CWorkPlanContentSectionLoadTopicsController extends CBaseController {
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
        $this->setPageTitle("Управление темами");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_CONTENT_TOPICS." as t")
            ->condition("t.load_id=".CRequest::getInt("load_id"))
            ->order("t.ordering asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanContentSectionLoadTopic($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontenttopics.php?action=index&load_id=".CRequest::getInt("load_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить тему",
            "link" => "workplancontenttopics.php?action=add&id=".CRequest::getInt("load_id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentTopic/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanContentSectionLoadTopic();
        $object->load_id = CRequest::getInt("id");
        $load = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $object->ordering = $load->topics->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentloads.php?action=edit&id=".$object->load_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentTopic/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanContentSectionLoadTopic(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentloads.php?action=edit&id=".$object->load_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentTopic/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanContentSectionLoadTopic(CRequest::getInt("id"));
        $load = $object->load_id;
        $object->remove();
        $this->redirect("workplancontentloads.php?action=edit&id=".$load);
    }
    public function actionSave() {
        $object = new CWorkPlanContentSectionLoadTopic();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontenttopics.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancontentloads.php?action=edit&id=".$object->load_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/contentTopic/edit.tpl");
    }
}