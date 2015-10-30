<?php
class CWorkPlanProjectThemesController extends CBaseController{
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
        $this->setPageTitle("Темы курсовых");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_PROJECT_THEMES." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id")." AND type=".CRequest::getInt("type"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanProjectTheme($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplanprojectthemes.php?action=add&id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/projectThemes/index.tpl");
    }
    public function actionAdd() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
    	$group = new CWorkPlanProjectThemeGroupAdd();
    	$group->plan_id = CRequest::getInt("id");
    	$group->type = CRequest::getInt("type");
    	$this->setData("group", $group);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanprojectthemes.php?action=index&plan_id=".$group->plan_id."&type=".$group->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/projectThemes/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanProjectTheme(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanprojectthemes.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/projectThemes/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanProjectTheme(CRequest::getInt("id"));
        $plan = $object->plan_id;
        $type = $object->type;
        $object->remove();
        $this->redirect("workplanprojectthemes.php?action=index&plan_id=".$plan."&type=".$type);
    }
    public function actionSave() {
    	$object = new CWorkPlanProjectTheme();
    	$object->setAttributes(CRequest::getArray($object::getClassName()));
    	if ($object->validate()) {
    		$object->save();
    		if ($this->continueEdit()) {
    			$this->redirect("workplanprojectthemes.php?action=index&plan_id=".$object->plan_id."&type=".$object->type);
    		} else {
    			$this->redirect("workplanprojectthemes.php?action=index&plan_id=".$object->plan_id."&type=".$object->type);
    		}
    		return true;
    	}
    	$this->setData("object", $object);
    	$this->renderView("_corriculum/_workplan/projectThemes/edit.tpl");
    }
    public function actionSaveGroup() {
    	$group = new CWorkPlanProjectThemeGroupAdd();
    	$group->setAttributes(CRequest::getArray($group::getClassName()));
    	$texts = explode(chr(13), $group->project_title);
    	foreach ($texts as $text) {
    		$q = new CWorkPlanProjectTheme();
    		$q->plan_id = $group->plan_id;
    		$q->type = $group->type;
    		$q->project_title = trim($text);
    		$q->save();
    	}
    	$this->redirect("workplanprojectthemes.php?action=index&plan_id=".$q->plan_id."&type=".$q->type);
    }
}