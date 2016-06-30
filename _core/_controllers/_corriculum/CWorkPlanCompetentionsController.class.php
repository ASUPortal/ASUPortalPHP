<?php
class CWorkPlanCompetentionsController extends CBaseController{
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
        $this->setPageTitle("Управление компетенциями");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_COMPETENTIONS." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id")." AND type=".CRequest::getInt("type"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanCompetention($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить компетенцию",
            "link" => "workplancompetentions.php?action=add&id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
            "icon" => "actions/list-add.png"
        ));
        $this->addActionsMenuItem(array(
        	"title" => "Обновить",
        	"link" => "workplancompetentions.php?action=index&plan_id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
        	"icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
        	"title" => "Загрузить из дисциплины",
        	"link" => "workplancompetentions.php?action=update&id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
        	"icon" => "actions/format-indent-more.png"
        ));
        if (CRequest::getInt("type") == 0) {
        	$this->addActionsMenuItem(array(
        		"title" => "Скопировать компетенции из РП в УП",
        		"link" => "workplancompetentions.php?action=copyCompetentions&id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
        		"icon" => "actions/format-indent-less.png"
        	));
        }
        $this->addActionsMenuItem(array(
        	"title" => "Удалить все",
        	"link" => "workplancompetentions.php?action=deleteAll&id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
        	"icon" => "actions/edit-delete.png"
        ));
        /*$this->addActionsMenuItem(array(
        		"title" => "Удалить выделенные",
        		"icon" => "actions/edit-delete.png",
        		"form" => "#competentionsForm",
        		"link" => "workplancompetentions.php",
        		"action" => "delete"
        ));*/
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/competentions/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanCompetention();
        $object->plan_id = CRequest::getInt("id");
        $object->type = CRequest::getInt("type");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancompetentions.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/competentions/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanCompetention(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancompetentions.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/competentions/edit.tpl");
    }
    public function actionDelete() {
    	$object = CBaseManager::getWorkPlanCompetention(CRequest::getInt("id"));
    	if (!is_null($object)) {
    		$plan = $object->plan_id;
    		$type = $object->type;
    		$object->remove();
    	}
    	$items = CRequest::getArray("selectedInView");
    	if (!empty($items)) {
    		$object = CBaseManager::getWorkPlanCompetention($items[0]);
    		$items = CRequest::getArray("selectedInView");
    		if (!is_null($object)) {
    			$plan = $object->plan_id;
    			$type = $object->type;
    			foreach ($items as $id){
    				$object = CBaseManager::getWorkPlanCompetention($id);
    				$object->remove();
    			}
    		}
    	}
    	$this->redirect("workplancompetentions.php?action=index&plan_id=".$plan."&type=".$type);
    }
    public function actionDeleteAll() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
    	$type = CRequest::getInt("type");
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_COMPETENTIONS, "plan_id=".$plan->getId()." and type=".$type)->getItems() as $ar) {
    		$object = new CWorkPlanCompetention($ar);
    		$object->remove();
    	}
    	$this->redirect("workplancompetentions.php?action=index&plan_id=".$plan->getId()."&type=".$type);
    }
    public function actionSave() {
        $object = new CWorkPlanCompetention();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancompetentions.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancompetentions.php?action=index&plan_id=".$object->plan_id."&type=".$object->type);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/competentions/edit.tpl");
    }
    public function actionUpdate() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
    	$type = CRequest::getInt("type");
    	if ($type == 0) {
    		if (!is_null($plan->corriculumDiscipline)) {
    			foreach ($plan->corriculumDiscipline->competentions->getItems() as $competention) {
    				$planCompetention = new CWorkPlanCompetention();
    				$planCompetention->plan_id = $plan->getId();
    				$planCompetention->allow_delete = 0;
    				$planCompetention->competention_id = $competention->competention_id;
    				$planCompetention->level_id = $competention->level_id;
    				$planCompetention->save();
    				foreach ($competention->knowledges->getItems() as $knowledge) {
    					$planCompetentionKnowledge = new CWorkPlanCompetentionKnowledge();
    					$planCompetentionKnowledge->competention_id = $planCompetention->getId();
    					$planCompetentionKnowledge->knowledge_id = $knowledge->getId();
    					$planCompetentionKnowledge->save();
    				}
    				foreach ($competention->skills->getItems() as $skill) {
    					$planCompetentionSkill = new CWorkPlanCompetentionSkill();
    					$planCompetentionSkill->competention_id = $planCompetention->getId();
    					$planCompetentionSkill->skill_id = $skill->getId();
    					$planCompetentionSkill->save();
    				}
    				foreach ($competention->experiences->getItems() as $experience) {
    					$planCompetentionExperience = new CWorkPlanCompetentionExperience();
    					$planCompetentionExperience->competention_id = $planCompetention->getId();
    					$planCompetentionExperience->experience_id = $experience->getId();
    					$planCompetentionExperience->save();
    				}
    			}
    		}
    	}
    	if ($type == 1) {
    		if (!is_null($plan->disciplinesBefore)) {
    			foreach ($plan->disciplinesBefore->getItems() as $item) {
    				foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_COMPETENTIONS, "discipline_id=".$item->getId())->getItems() as $ar) {
    					$competention = new CActiveModel($ar);
    					$object = new CWorkPlanCompetention();
    					$object->plan_id = $plan->getId();
    					$object->type = $type;
    					$object->competention_id = $competention->competention_id;
    					$object->level_id = $competention->level_id;
    					$object->discipline_id = $competention->discipline_id;
    					$object->save();
    				}
    			}
    		}
    	}
    	if ($type == 2) {
    		if (!is_null($plan->disciplinesAfter)) {
    			foreach ($plan->disciplinesAfter->getItems() as $item) {
    				foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_COMPETENTIONS, "discipline_id=".$item->getId())->getItems() as $ar) {
    					$competention = new CActiveModel($ar);
    					$object = new CWorkPlanCompetention();
    					$object->plan_id = $plan->getId();
    					$object->type = $type;
    					$object->competention_id = $competention->competention_id;
    					$object->level_id = $competention->level_id;
    					$object->discipline_id = $competention->discipline_id;
    					$object->save();
    				}
    			}
    		}
    	}
    	$this->redirect("workplancompetentions.php?action=index&plan_id=".$plan->getId()."&type=".$type);
    }
    public function actionCopyCompetentions() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
    	$corriculumDiscipline = $plan->corriculumDiscipline;
    	$type = CRequest::getInt("type");
    	foreach ($plan->competentionsFormed->getItems() as $competentionFormed) {
    		$newItem = new CCorriculumDisciplineCompetention();
    		$newItem->discipline_id = $corriculumDiscipline->getId();
    		$newItem->competention_id = $competentionFormed->competention_id;
    		$newItem->level_id = $competentionFormed->level_id;
    		foreach ($competentionFormed->knowledges->getItems() as $knowledge) {
    			$newItem->knowledges->add($knowledge->knowledge->getId(), $knowledge->knowledge->getId());
    		}
    		foreach ($competentionFormed->skills->getItems() as $skill) {
    			$newItem->skills->add($skill->skill->getId(), $skill->skill->getId());
    		}
    		foreach ($competentionFormed->experiences->getItems() as $experience) {
    			$newItem->experiences->add($experience->experience->getId(), $experience->experience->getId());
    		}	
    		$newItem->save();
    	}
    	$this->redirect("workplancompetentions.php?action=index&plan_id=".$plan->getId()."&type=".$type);
    }
}