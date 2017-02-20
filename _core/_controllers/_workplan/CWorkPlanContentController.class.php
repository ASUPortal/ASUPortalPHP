<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 07.09.15
 * Time: 22:15
 */

class CWorkPlanContentController extends CBaseController{
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
        $this->setPageTitle("Управление категориями");

        parent::__construct();
    }
    public function actionPractices() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontent.php?action=practices&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $this->setData("objects", $plan->getPractices());
        $this->renderView("_corriculum/_workplan/content/practices.tpl");
    }
    public function actionEditPractices() {
        $object = CBaseManager::getWorkPlanContentSectionLoadTopic(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontent.php?action=practices&plan_id=".$object->load->section->category->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->renderView("_corriculum/_workplan/content/practicesForm.tpl");
    }
    public function actionSavePractices() {
        $object = new CWorkPlanContentSectionLoadTopic();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontent.php?action=editPractices&id=".$object->getId());
            } else {
                $this->redirect("workplancontent.php?action=practices&plan_id=".$object->load->section->category->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/content/practicesForm.tpl");
    }
    public function actionLabWorks() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontent.php?action=labworks&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $this->setData("objects", $plan->getLabWorks());
        $this->renderView("_corriculum/_workplan/content/labworks.tpl");
    }
    public function actionEditLabWorks() {
        $object = CBaseManager::getWorkPlanContentSectionLoadTopic(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontent.php?action=labworks&plan_id=".$object->load->section->category->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->renderView("_corriculum/_workplan/content/labworksForm.tpl");
    }
    public function actionSaveLabWorks() {
        $object = new CWorkPlanContentSectionLoadTopic();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontent.php?action=editLabWorks&id=".$object->getId());
            } else {
                $this->redirect("workplancontent.php?action=labWorks&plan_id=".$object->load->section->category->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/content/labworksForm.tpl");
    }
    public function actionLectures() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
    	$this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontent.php?action=lectures&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
    	));
    	$this->setData("objects", $plan->getLectures());
    	$this->renderView("_corriculum/_workplan/content/lectures.tpl");
    }
    public function actionEditLectures() {
        $object = CBaseManager::getWorkPlanContentSectionLoadTopic(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontent.php?action=lectures&plan_id=".$object->load->section->category->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->renderView("_corriculum/_workplan/content/lecturesForm.tpl");
    }
    public function actionSaveLectures() {
        $object = new CWorkPlanContentSectionLoadTopic();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontent.php?action=editLectures&id=".$object->getId());
            } else {
                $this->redirect("workplancontent.php?action=lectures&plan_id=".$object->load->section->category->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/content/lecturesForm.tpl");
    }
    public function actionTechnologies() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontent.php?action=technologies&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $this->setData("objects", $plan->getTechnologies());
        $this->renderView("_corriculum/_workplan/content/technologies.tpl");
    }
    public function actionEditTechnologies() {
        $object = CBaseManager::getWorkPlanContentSectionLoadTechnology(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontent.php?action=technologies&plan_id=".$object->load->section->category->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->renderView("_corriculum/_workplan/content/technologiesForm.tpl");
    }
    public function actionSaveTechnologies() {
        $object = new CWorkPlanContentSectionLoadTechnology();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontent.php?action=editTechnologies&id=".$object->getId());
            } else {
                $this->redirect("workplancontent.php?action=technologies&plan_id=".$object->load->section->category->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/content/technologiesForm.tpl");
    }
    public function actionSelfWorkQuestions() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontent.php?action=selfWorkQuestions&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $this->setData("objects", $plan->getSelfWorkQuestions());
        $this->renderView("_corriculum/_workplan/content/selfWorkQuestions.tpl");
    }
    public function actionEditSelfWorkQuestions() {
        $object = CBaseManager::getWorkPlanContentSectionLoadTopic(CRequest::getInt("id"));
        $this->setData("object", $object);
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancontent.php?action=selfWorkQuestions&plan_id=".$object->load->section->category->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        $this->renderView("_corriculum/_workplan/content/selfWorkQuestionsForm.tpl");
    }
    public function actionSaveSelfWorkQuestions() {
        $object = new CWorkPlanContentSectionLoadTopic();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancontent.php?action=editSelfWorkQuestions&id=".$object->getId());
            } else {
                $this->redirect("workplancontent.php?action=selfWorkQuestions&plan_id=".$object->load->section->category->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/content/selfWorkQuestionsForm.tpl");
    }

    /**
     * Структура дисциплины.
     * Здесь идут жестокие sql-запросы, но так оно
     * работает намного быстрее
     */
    public function actionStructure() {
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "workplancontent.php?action=structure&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $terms = array();
        $terms[] = "term.name";
        $termIds = array();
        foreach ($plan->terms->getItems() as $term) {
            $termIds[] = $term->getId();
            $terms[] = "sum(if(l.term_id = ".$term->getId().", l.value, 0)) as t_".$term->getId();
        }
        if (count($termIds) > 0) {
            $terms[] = "sum(if(l.term_id in (".join(", ", $termIds)."), l.value, 0)) as t_sum";
        }
        /**
         * Определим нагрузку по каждому виду в каждом семестре
         */
        $query = new CQuery();
        $query->select(join(", ", $terms))
            ->from(TABLE_WORK_PLAN_CONTENT_LOADS." as l")
            ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
            ->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "l.section_id = section.id")
            ->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
            ->condition("category.plan_id = ".$plan->getId()." and l._deleted = 0 and category._deleted = 0")
            ->group("l.load_type_id")
            ->order("l.ordering asc");
        $objects = $query->execute();
        $this->setData("objects", $objects);
        $this->setData("terms", $plan->terms);
        /**
         * Теперь определим разделы дисциплины и нагрузку по
         * ним в каждом семестре.
         */
        $termSectionsData = new CArrayList();
        $selfWork = false;
        foreach ($plan->categories->getItems() as $category) {
        	foreach ($category->sections->getItems() as $section) {
        		foreach ($section->loadsDisplay->getItems() as $load) {
        			if ($load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK) {
        				$selfWork = true;
        			}
        		}
        	}
        }
        foreach ($plan->terms->getItems() as $term) {
            $query = new CQuery();
            $select = array();
            $select[] = "section.sectionIndex";
            $select[] = "section.name";
            if ($selfWork) {
            	$select[] = "sum(if(term.alias in ('".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE."',
            			 '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE."', 
            			 '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK."',
            			 '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR."', 
            			 '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK."'), l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_TOTAL."";
            } else {
            	$select[] = "sum(if(term.alias in ('".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE."', 
            			'".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE."', 
            			'".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK."', 
            			'".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR."'), l.value, 0)) + sum(ifnull(selfedu.question_hours, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_TOTAL."";
            }
            $select[] = "sum(if(term.alias = '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE."', l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE."";
            $select[] = "sum(if(term.alias = '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE."', l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE."";
            $select[] = "sum(if(term.alias = '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK."', l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK."";
            $select[] = "sum(if(term.alias = '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR."', l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR."";
            if ($selfWork) {
            	$select[] = "sum(if(term.alias = '".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK."', l.value, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK."";
            } else {
            	$select[] = "sum(ifnull(selfedu.question_hours, 0)) as ".CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_EDUCATION."";
            }
            $query->select(join(", ", $select))
                ->from(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section")
                ->innerJoin(TABLE_WORK_PLAN_CONTENT_LOADS." as l", "l.section_id = section.id")
                ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
                ->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
                ->group("l.section_id")
                ->condition("l.term_id = ".$term->getId()." and l._deleted = 0 and category._deleted = 0")
                ->order("section.sectionIndex");
            if (!$selfWork) {
            	$query->leftJoin(TABLE_WORK_PLAN_SELFEDUCATION." as selfedu", "selfedu.load_id = l.id");
            }
            $items = $query->execute();
            if ($items->getCount() > 0) {
                $termSectionsData->add($term->getId(), $items);
            }
        }
        $this->setData("termSectionsData", $termSectionsData);
        /**
         * Виды контроля
         */
        $this->setData("controlTypes", $plan->getControlTypes());
        /**
         * Описание и количество баллов по видам учебной деятельности
         */
        $setMarks = new CRecordSet();
        $queryMarks = new CQuery();
        $setMarks->setQuery($queryMarks);
        $queryMarks->select("control.*")
	        ->from(TABLE_WORK_PLAN_TYPES_CONTROL." as control")
	        ->innerJoin(TABLE_WORK_PLAN_MARKS_STUDY_ACTIVITY." as activity", "activity.activity_id = control.id")
	        ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = control.type_study_activity_id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "control.section_id = section.id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
	        ->condition("category.plan_id = ".$plan->getId()." and category._deleted = 0")
	        ->order("activity.ordering asc");
        $marks = new CArrayList();
        foreach ($setMarks->getItems() as $ar) {
        	$mark = new CWorkPlanControlTypes($ar);
        	$marks->add($mark->getId(), $mark);
        }
        $this->setData("marks", $marks);
        $this->renderView("_corriculum/_workplan/content/structure.tpl");
    }
}