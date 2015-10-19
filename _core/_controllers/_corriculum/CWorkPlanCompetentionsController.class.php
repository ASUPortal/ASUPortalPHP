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
        		"title" => "Удалить выделенные",
        		"icon" => "actions/edit-delete.png",
        		"form" => "#MainView",
        		"link" => "workplans.php",
        		"action" => "delete"
        ));
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
        $plan = $object->plan_id;
        $type = $object->type;
        if (!is_null($object)) {
        	$object->remove();
        }
        $items = CRequest::getArray("selectedInView");
        foreach ($items as $id){
        	$object = CBaseManager::getWorkPlanCompetention($id);
        	$object->remove();
        }
        $this->redirect("workplancompetentions.php?action=index&plan_id=".$plan."&type=".$type);
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
    			foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_COMPETENTIONS, "discipline_id=".$plan->corriculumDiscipline->getId())->getItems() as $ar) {
    				$newCompetention = new CActiveModel($ar);
    				$object = new CWorkPlanCompetention();
    				$object->plan_id = $plan->getId();
    				$object->type = $type;
    				$object->competention_id = $newCompetention->competention_id;
    				$object->level_id = $newCompetention->level_id;
    				$object->save();
    				foreach ($plan->corriculumDiscipline->competentions->getItems() as $competention) {
    					foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_KNOWLEDGES, "competention_id=".$competention->getId())->getItems() as $ar) {
    						$item = new CActiveModel($ar);
    						$ar = new CActiveRecord(array(
    							"competention_id" => $object->getId(),
    							"knowledge_id" => $item->knowledge_id,
    							"id" => null
    						));
    						$ar->setTable(TABLE_WORK_PLAN_KNOWLEDGES);
    						$ar->insert();
    					}
    					foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_SKILLS, "competention_id=".$competention->getId())->getItems() as $ar) {
    						$item = new CActiveModel($ar);
    						$ar = new CActiveRecord(array(
    							"competention_id" => $object->getId(),
    							"skill_id" => $item->skill_id,
    							"id" => null
    						));
    						$ar->setTable(TABLE_WORK_PLAN_SKILLS);
    						$ar->insert();
    					}
    					foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_EXPERIENCES, "competention_id=".$competention->getId())->getItems() as $ar) {
    						$item = new CActiveModel($ar);
    						$ar = new CActiveRecord(array(
    							"competention_id" => $object->getId(),
    							"experience_id" => $item->experience_id,
    							"id" => null
    						));
    						$ar->setTable(TABLE_WORK_PLAN_EXPERIENCES);
    						$ar->insert();
    					}
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
    		$newItem->save();
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_KNOWLEDGES, "competention_id=".$competentionFormed->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    				"competention_id" => $newItem->getId(),
    				"knowledge_id" => $item->knowledge_id,
    				"id" => null
    			));
    			$ar->setTable(TABLE_CORRICULUM_DISCIPLINE_KNOWLEDGES);
    			$ar->insert();
    		}
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_SKILLS, "competention_id=".$competentionFormed->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    					"competention_id" => $newItem->getId(),
    					"skill_id" => $item->skill_id,
    					"id" => null
    			));
    			$ar->setTable(TABLE_CORRICULUM_DISCIPLINE_SKILLS);
    			$ar->insert();
    		}
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_WORK_PLAN_EXPERIENCES, "competention_id=".$competentionFormed->getId())->getItems() as $ar) {
    			$item = new CActiveModel($ar);
    			$ar = new CActiveRecord(array(
    					"competention_id" => $newItem->getId(),
    					"experience_id" => $item->experience_id,
    					"id" => null
    			));
    			$ar->setTable(TABLE_CORRICULUM_DISCIPLINE_EXPERIENCES);
    			$ar->insert();
    		}
    	}
    	$this->redirect("workplancompetentions.php?action=index&plan_id=".$plan->getId()."&type=".$type);
    }
}