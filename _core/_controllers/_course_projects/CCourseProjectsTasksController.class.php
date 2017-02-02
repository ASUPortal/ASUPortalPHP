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
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_COURSE_PROJECTS_TASKS." as t");
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
            "title" => "Добавить задание",
            "link" => "task.php?action=add&course_project_id=".CRequest::getInt("course_project_id"),
            "icon" => "actions/list-add.png"
        ));
        $this->setData("tasks", $tasks);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_course_projects/tasks/index.tpl");
    }
    public function actionAdd() {
        $task = new CCourseProjectsTask();
        $courseProject = CBaseManager::getCourseProject(CRequest::getInt("course_project_id"));
        $task->course_project_id = $courseProject->getId();
        $students = array();
        foreach ($courseProject->group->getStudents() as $student) {
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
    public function actionEdit() {
        $task = CBaseManager::getCourseProjectsTask(CRequest::getInt("id"));
        $courseProject = CBaseManager::getCourseProject($task->course_project_id);
        $students = array();
        foreach ($courseProject->group->getStudents() as $student) {
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
        $courseProject = $task->courseProject;
        $task->remove();
        $this->redirect("task.php?action=index&course_project_id=".$courseProject->getId());
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
        foreach ($courseProject->group->getStudents() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->setData("task", $task);
        $this->setData("students", $students);
        $this->renderView("_course_projects/tasks/edit.tpl");
    }
}