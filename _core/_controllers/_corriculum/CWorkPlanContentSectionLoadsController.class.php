<?php
class CWorkPlanContentSectionLoadsController extends CBaseController{
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
        $this->setPageTitle("Нагрузка");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_CONTENT_LOADS." as t")
            ->condition("section_id=".CRequest::getInt("section_id"))
            ->order("t.ordering asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanContentSectionLoad($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
			array(
				"title" => "Назад",
				"link" => "workplancontentsections.php?action=edit&id=".$object->section_id,
				"icon" => "actions/edit-undo.png"
			),
			array(
				"title" => "Обновить",
				"link" => "workplancontentloads.php?action=index&section_id=".CRequest::getInt("section_id"),
				"icon" => "actions/view-refresh.png"
			),
			array(
				"title" => "Добавить нагрузку",
				"link" => "workplancontentloads.php?action=add&id=".CRequest::getInt("section_id"),
				"icon" => "actions/list-add.png"
			),
        	array(
        		"title" => "Удалить выделенные",
        		"icon" => "actions/edit-delete.png",
        		"form" => "#mainView",
        		"link" => "workplancontentloads.php?action=delete&section_id=".CRequest::getInt("section_id"),
        		"action" => "delete"
        	)
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanContentSectionLoad();
        $object->section_id = CRequest::getInt("id");
        $section = CBaseManager::getWorkPlanContentSection(CRequest::getInt("id"));
        $object->ordering = $section->loads->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentloads.php?action=index&section_id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontentsections.php?action=edit&id=".$object->section_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить тему",
            "link" => "workplancontenttopics.php?action=add&id=".$object->getId(),
            "icon" => "actions/list-add.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить технологию",
            "link" => "workplancontenttechnologies.php?action=add&id=".$object->getId(),
            "icon" => "actions/list-add.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить самостоятельную работу",
            "link" => "workplanselfeducationblocks.php?action=add&id=".$object->getId(),
            "icon" => "actions/list-add.png"
        ));
        $this->addActionsMenuItem(array(
        	"title" => "Удалить выделенные темы",
        	"icon" => "actions/edit-delete.png",
        	"form" => "#mainViewTopics",
        	"link" => "workplancontenttopics.php?action=delete&load_id=".CRequest::getInt("id"),
        	"action" => "delete"
        ));
        $this->addActionsMenuItem(array(
        	"title" => "Удалить выделенные технологии",
        	"icon" => "actions/edit-delete.png",
        	"form" => "#mainViewTechnologies",
        	"link" => "workplancontenttechnologies.php?action=delete&load_id=".CRequest::getInt("id"),
        	"action" => "delete"
        ));
        $this->addActionsMenuItem(array(
        	"title" => "Удалить выделенные вопросы",
        	"icon" => "actions/edit-delete.png",
        	"form" => "#mainViewSelfedu",
        	"link" => "workplanselfeducationblocks.php?action=delete&load_id=".CRequest::getInt("id"),
        	"action" => "delete"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/contentLoads/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanContentSectionLoad(CRequest::getInt("id"));
        if (!is_null($object)) {
        	$section = $object->section_id;
        	$item = CBaseManager::getWorkPlanContentSection($section);
        	$object->remove();
        	$order = 1;
        	foreach ($item->loads as $load) {
        		$load->ordering = $order++;
        		$load->save();
        	}
        	$this->redirect("workplancontentloads.php?action=index&section_id=".$section);
        }
        $items = CRequest::getArray("selectedInView");
        $section = CRequest::getInt("section_id");
        foreach ($items as $id){
        	$object = CBaseManager::getWorkPlanContentSectionLoad($id);
        	$object->remove();
        }
        $item = CBaseManager::getWorkPlanContentSection($section);
        $order = 1;
        foreach ($item->loads as $load) {
        	$load->ordering = $order++;
        	$load->save();
        }
        $this->redirect("workplancontentloads.php?action=index&section_id=".$section);
    }
    public function actionSave() {
        $object = new CWorkPlanContentSectionLoad();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontentloads.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancontentloads.php?action=index&section_id=".$object->section_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/contentLoads/edit.tpl");
    }
}