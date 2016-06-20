<?php
class CWorkPlanCompetentionSkillsController extends CBaseController{
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
        $this->setPageTitle("Управление умениями компетенций рабочей программы");

        parent::__construct();
    }
	public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_SKILLS." as t")
            ->order("t.ordering asc")
            ->condition("competention_id=".CRequest::getInt("id"));
        if (CRequest::getString("order") == "term.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$query->innerJoin(TABLE_TAXONOMY_TERMS." as term", "t.skill_id=term.id");
        		$direction = CRequest::getString("direction");}
        		$query->order("term.name ".$direction);
        }
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanCompetentionSkill($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
        	"title" => "Обновить",
        	"link" => "workplancompetentionskills.php?action=index&id=".CRequest::getInt("id"),
        	"icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplancompetentionskills.php?action=add&id=".CRequest::getInt("id"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/competentionSkills/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanCompetentionSkill();
        $object->competention_id = CRequest::getInt("id");
        $competention = CBaseManager::getWorkPlanCompetention(CRequest::getInt("id"));
        $object->ordering = $competention->skills->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancompetentionskills.php?action=index&id=".$object->competention_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/competentionSkills/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanCompetentionSkill(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancompetentionskills.php?action=index&id=".$object->competention_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/competentionSkills/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanCompetentionSkill(CRequest::getInt("id"));
        $competention = $object->competention;
        $object->remove();
        $order = 1;
        foreach ($competention->skills as $skill) {
        	$skill->ordering = $order++;
        	$skill->save();
        }
        $this->redirect("workplancompetentionskills.php?action=index&id=".$competention->getId());
    }
    public function actionSave() {
        $object = new CWorkPlanCompetentionSkill();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancompetentionskills.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancompetentionskills.php?action=index&id=".$object->competention_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/competentionSkills/edit.tpl");
    }
}