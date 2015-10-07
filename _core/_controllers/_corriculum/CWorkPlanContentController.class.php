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
            ->condition("category.plan_id = ".$plan->getId())
            ->group("l.load_type_id")
            ->order("term.name");
        $objects = $query->execute();
        $this->setData("objects", $objects);
        $this->setData("terms", $plan->terms);
        /**
         * Теперь определим разделы дисциплины и нагрузку по
         * ним в каждом семестре.
         */
        $termSectionsData = new CArrayList();
        foreach ($plan->terms->getItems() as $term) {
            $query = new CQuery();
            $select = array();
            $select[] = "section.sectionIndex";
            $select[] = "section.name";
            $select[] = "sum(if(term.alias in ('lecture', 'practice', 'labwork', 'ksr'), l.value, 0)) + sum(selfedu.question_hours) as total";
            $select[] = "sum(if(term.alias = 'lecture', l.value, 0)) as lecture";
            $select[] = "sum(if(term.alias = 'practice', l.value, 0)) as practice";
            $select[] = "sum(if(term.alias = 'labwork', l.value, 0)) as labwork";
            $select[] = "sum(if(term.alias = 'ksr', l.value, 0)) as ksr";
            $select[] = "sum(selfedu.question_hours) as selfedu";
            $query->select(join(", ", $select))
                ->from(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section")
                ->innerJoin(TABLE_WORK_PLAN_CONTENT_LOADS." as l", "l.section_id = section.id")
                ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
                ->leftJoin(TABLE_WORK_PLAN_SELFEDUCATION." as selfedu", "selfedu.load_id = l.id")
                ->group("l.section_id")
                ->condition("l.term_id = ".$term->getId());
            $items = $query->execute();
            if ($items->getCount() > 0) {
                $termSectionsData->add($term->getId(), $items);
            }
        }
        $this->setData("termSectionsData", $termSectionsData);
        /**
         * Виды контроля
         */
        $set = new CRecordSet();
		$queryControlTypes = new CQuery();
        $set->setQuery($queryControlTypes);
        $queryControlTypes->select("l.*")
	        ->from(TABLE_WORK_PLAN_TYPES_CONTROL." as l")
	        ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.control_id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "l.section_id = section.id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
	        ->condition("category.plan_id = ".$plan->getId())
	        ->order("term.name asc");
        $controlTypes = new CArrayList();
        foreach ($set->getItems() as $ar) {
        	$controlType = new CWorkPlanControlTypes($ar);
        	$controlTypes->add($controlType->getId(), $controlType);
        }
        $this->setData("controlTypes", $controlTypes);
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
	        ->condition("category.plan_id = ".$plan->getId())
	        ->order("activity.mark asc");
        $marks = new CArrayList();
        foreach ($setMarks->getItems() as $ar) {
        	$mark = new CWorkPlanControlTypes($ar);
        	$marks->add($mark->getId(), $mark);
        }
        $this->setData("marks", $marks);
        $this->renderView("_corriculum/_workplan/content/structure.tpl");
    }
}