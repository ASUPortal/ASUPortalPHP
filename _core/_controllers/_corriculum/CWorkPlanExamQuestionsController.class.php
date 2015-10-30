<?php
class CWorkPlanExamQuestionsController extends CBaseController{
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
        $this->setPageTitle("Управление вопросами к экзамену (зачёту)");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_EXAMINATION_QUESTIONS." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id")." AND type=".CRequest::getInt("type"));
        $objects = new CArrayList();
        foreach ($set->getItems() as $ar) {
            $object = new CExamQuestion($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню 
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "workplanexamquestions.php?action=add&id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
            "icon" => "actions/list-add.png"
        ));
        $this->addActionsMenuItem(array(
        		"title" => "Групповое добавление",
        		"link" => "workplanexamquestions.php?action=addGroup&id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
        		"icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/examQuestions/index.tpl");
    }
    public function actionAdd() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $object = new CExamQuestion();
        $object->plan_id = CRequest::getInt("id");
        $object->type = CRequest::getInt("type");
        $object->discipline_id = $plan->discipline->id;
        $object->year_id = CUtils::getCurrentYear()->getId();
        $this->setData("cources", array(
        		1 => 1,
        		2 => 2,
        		3 => 3,
        		4 => 4,
        		5 => 5
        ));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanexamquestions.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/examQuestions/add.tpl");
    }
    public function actionAddGroup() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
    	$group = new CExamGroupAdd();
    	$group->plan_id = CRequest::getInt("id");
    	$group->type = CRequest::getInt("type");
    	$group->discipline_id = $plan->discipline->id;
    	$group->year_id = CUtils::getCurrentYear()->getId();
    	$this->setData("group", $group);
    	$this->setData("cources", array(
    			1 => 1,
    			2 => 2,
    			3 => 3,
    			4 => 4,
    			5 => 5
    	));
    	$this->addActionsMenuItem(array(
    			"title" => "Назад",
    			"link" => "workplanexamquestions.php?action=index&plan_id=".CRequest::getInt("id")."&type=".CRequest::getInt("type"),
    			"icon" => "actions/edit-undo.png"
    	));
    	$this->renderView("_corriculum/_workplan/examQuestions/groupadd.tpl");
    }
    public function actionSaveGroup() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
    	$group = new CExamGroupAdd();
    	$group->setAttributes(CRequest::getArray($group::getClassName()));
    	$texts = explode(chr(13), $group->text);
    	foreach ($texts as $text) {
    		$q = new CExamQuestion();
    		$q->speciality_id = $group->speciality_id;
    		$q->course = $group->course;
    		$q->year_id = $group->year_id;
    		$q->category_id = $group->category_id;
    		$q->discipline_id = $group->discipline_id;
    		$q->plan_id = $group->plan_id;
    		$q->type = $group->type;
    		$q->text = trim($text);
    		$q->save();
    	}
    	$this->redirect("workplanexamquestions.php?action=index&plan_id=".$q->plan_id."&type=".$q->type);
    }
    public function actionEdit() {
        $object = CExamManager::getQuestion(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->setData("cources", array(
        		1 => 1,
        		2 => 2,
        		3 => 3,
        		4 => 4,
        		5 => 5
        ));
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanexamquestions.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/examQuestions/edit.tpl");
    }
    public function actionDelete() {
        $object = CExamManager::getQuestion(CRequest::getInt("id"));
        $plan = $object->plan_id;
        $type = $object->type;
        $object->remove();
        $this->redirect("workplanexamquestions.php?action=index&plan_id=".$plan."&type=".$type);
    }
    public function actionSave() {
        $object = new CExamQuestion();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplanexamquestions.php?action=index&plan_id=".$object->plan_id."&type=".$object->type);
            } else {
                $this->redirect("workplanexamquestions.php?action=index&plan_id=".$object->plan_id."&type=".$object->type);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/examQuestions/edit.tpl");
    }
}