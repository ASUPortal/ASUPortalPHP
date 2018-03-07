<?php
class CCourseProjectsTasksController extends CBaseController {
	protected $_isComponent = true;
	
    public function __construct() {
        if (!CSession::isAuth()) {
        	$action = CRequest::getString("action");
        	if ($action == "") {
        		$action = "index";
        	}
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }
        $this->_smartyEnabled = true;
        $this->setPageTitle("Журнал заданий на курсовое проектирование");
        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet(false);
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("tasks.*")
            ->from(TABLE_COURSE_PROJECTS_TASKS." as tasks")
            ->innerJoin(TABLE_STUDENTS." as student", "tasks.student_id=student.id")
            ->condition("course_project_id=".CRequest::getInt("course_project_id"))
            ->order("student.fio asc");
        if (CRequest::getString("order") == "student_id") {
            $direction = "asc";
            if (CRequest::getString("direction") != "") {
                $direction = CRequest::getString("direction");
            }
            $query->innerJoin(TABLE_STUDENTS." as student", "tasks.student_id=student.id");
            $query->order("student.fio ".$direction);
        } elseif (CRequest::getString("order") == "theme") {
            $direction = "asc";
            if (CRequest::getString("direction") != "") {
                $direction = CRequest::getString("direction");
            }
            $query->order("tasks.theme ".$direction);
        } elseif (CRequest::getString("order") == "mark") {
            $direction = "asc";
            if (CRequest::getString("direction") != "") {
                $direction = CRequest::getString("direction");
            }
            $query->innerJoin(TABLE_STUDENTS." as student", "tasks.student_id=student.id");
            $query->innerJoin(TABLE_STUDENTS_ACTIVITY." as activity", "activity.student_id=student.id");
            $query->innerJoin(TABLE_MARKS." as mark", "activity.study_mark=mark.id");
            $query->condition("activity.study_act_id = ".CTaxonomyManager::getLegacyTaxonomy("study_act")->getTerm(CCourseProjectConstants::CONTROL_TYPE_COURSE_PROJECT)->getId());
            $query->order("mark.name ".$direction);
        }
        $set->setPageSize(PAGINATION_ALL);
        $tasks = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $task = new CCourseProjectsTask($ar);
            $tasks->add($task->getId(), $task);
        }
        $this->addActionsMenuItem(array(
            "title" => "Обновить",
            "link" => "task.php?action=index&course_project_id=".CRequest::getInt("course_project_id"),
            "icon" => "actions/view-refresh.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "task.php?action=add&course_project_id=".CRequest::getInt("course_project_id"),
            "icon" => "actions/list-add.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Добавить задания",
            "link" => "task.php?action=addTasks&course_project_id=".CRequest::getInt("course_project_id"),
            "icon" => "actions/list-add.png"
        ));
        $this->addActionsMenuItem(array(
            "title" => "Удалить выделенные",
            "icon" => "actions/edit-delete.png",
            "form" => "#mainViewTasks",
            "link" => "task.php?action=delete&course_project_id=".CRequest::getInt("course_project_id"),
            "action" => "delete"
        ));
        $this->setData("tasks", $tasks);
        $this->renderView("_course_projects/tasks/index.tpl");
    }
    public function actionAdd() {
        $task = new CCourseProjectsTask();
        $courseProject = CBaseManager::getCourseProject(CRequest::getInt("course_project_id"));
        $task->course_project_id = $courseProject->getId();
        $students = array();
        foreach ($courseProject->group->getStudentsWithChangeGroupsHistory() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->setData("task", $task);
        $this->setData("students", $students);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "task.php?action=index&course_project_id=".$courseProject->getId(),
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_course_projects/tasks/add.tpl");
    }
    public function actionAddTasks() {
        $courseProject = CBaseManager::getCourseProject(CRequest::getInt("course_project_id"));
        if ($courseProject->tasks->isEmpty()) {
            foreach ($courseProject->group->getStudentsWithChangeGroupsHistory() as $student) {
                $task = new CCourseProjectsTask();
                $task->course_project_id = $courseProject->getId();
                $task->student_id = $student->getId();
                $task->save();
            }
        }
        $this->redirect("task.php?action=addGroup&course_project_id=".$courseProject->getId());
    }
    public function actionAddGroup() {
        $courseProject = CBaseManager::getCourseProject(CRequest::getInt("course_project_id"));
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "task.php?action=index&course_project_id=".$courseProject->getId(),
                "icon" => "actions/edit-undo.png"
            ),
        	array(
        		"title" => "Заполнить темы",
        		"link" => "task.php?action=fillThemes&course_project_id=".$courseProject->getId(),
        		"icon" => "actions/format-indent-more.png"
            )
        ));
        $this->setData("courseProject", $courseProject);
        $this->renderView("_course_projects/tasks/addGroup.tpl");
    }
    public function actionFillThemes() {
        $courseProject = CBaseManager::getCourseProject(CRequest::getInt("course_project_id"));
        $group = $courseProject->group;
        $projectThemes = array();
        if (!is_null($group)) {
            $corriculum = $group->corriculum;
            if (!is_null($corriculum)) {
        	    foreach ($corriculum->cycles as $cycle) {
        	        foreach ($cycle->allDisciplines as $discipline) {
        	            if ($courseProject->discipline->getId() == $discipline->discipline->getId()) {
        	                foreach ($discipline->plans->getItems() as $plan) {
        	                    foreach ($plan->projectThemes->getItems() as $projectTheme) {
        	                        $projectThemes[$projectTheme->getId()] = $projectTheme->project_title;
        	                    }
        	                }
        	            }
        	        }
        	    }
            }
        }
        if (!empty($projectThemes)) {
            $randomProjectThemes = array_rand($projectThemes, $courseProject->tasks->getCount());
            $i = 0;
            foreach ($courseProject->tasks->getItems() as $task) {
        	    $task->theme = CBaseManager::getWorkPlanProjectTheme($randomProjectThemes[$i])->project_title;
        	    $task->save();
        	    $i++;
            }
        }
        $this->redirect("task.php?action=addGroup&course_project_id=".$courseProject->getId());
    }
    public function actionEdit() {
        $task = CBaseManager::getCourseProjectsTask(CRequest::getInt("id"));
        $courseProject = CBaseManager::getCourseProject($task->course_project_id);
        $students = array();
        foreach ($courseProject->group->getStudentsWithChangeGroupsHistory() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->setData("task", $task);
        $this->setData("students", $students);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => "task.php?action=index&course_project_id=".$courseProject->getId(),
                "icon" => "actions/edit-undo.png"
            )
        ));
        $this->renderView("_course_projects/tasks/edit.tpl");
    }
    public function actionDelete() {
        $task = CBaseManager::getCourseProjectsTask(CRequest::getInt("id"));
        if (!is_null($task)) {
            $courseProject = $task->courseProject;
            $task->remove();
            $this->redirect("task.php?action=index&course_project_id=".$courseProject->getId());
        }
        $items = CRequest::getArray("selectedInView");
        $courseProject = CBaseManager::getCourseProject(CRequest::getInt("course_project_id"));
        foreach ($items as $id){
            $task = CBaseManager::getCourseProjectsTask($id);
            $task->remove();
        }
        $this->redirect("index.php?action=edit&id=".$courseProject->getId());
    }
    public function actionSave() {
        $task = new CCourseProjectsTask();
        $task->setAttributes(CRequest::getArray($task::getClassName()));
        if ($task->validate()) {
            $task->save();
            if ($this->continueEdit()) {
                $this->redirect("task.php?action=edit&id=".$task->getId());
            } else {
                $this->redirect("task.php?action=index&course_project_id=".$task->course_project_id);
            }
            return true;
        }
        $courseProject = CBaseManager::getCourseProject($task->course_project_id);
        $students = array();
        foreach ($courseProject->group->getStudentsWithChangeGroupsHistory() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->setData("task", $task);
        $this->setData("students", $students);
        $this->renderView("_course_projects/tasks/edit.tpl");
    }
    public function actionSaveGroup() {
        $courseProject = CBaseManager::getCourseProject(CRequest::getInt("id"));
        foreach ($courseProject->tasks->getItems() as $item) {
            $task = CBaseManager::getCourseProjectsTask($item->getId());
            $task->theme = CRequest::getString($item->getId());
            $task->save();
        }
        $this->redirect("task.php?action=index&course_project_id=".$courseProject->getId());
    }
}