<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 13.04.13
 * Time: 15:47
 * To change this template use File | Settings | File Templates.
 */

class CTasksController extends CBaseController{
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
        $this->setPageTitle("Управление задачами портала");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("*")
            ->from(TABLE_USER_ROLES."")
            ->order("name asc");
        $tasks = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $task = new CUserRole($ar);
            $tasks->add($task->getId(), $task);
        }
        $this->setData("tasks", $tasks);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_users/tasks/index.tpl");
    }
    public function actionAdd() {
        $task = new CUserRole();
        $this->setData("task", $task);
        $this->renderView("_users/tasks/add.tpl");
    }
    public function actionEdit() {
        $task = CStaffManager::getUserRole(CRequest::getInt("id"));
        $this->setData("task", $task);
        $this->renderView("_users/tasks/edit.tpl");
    }
    public function actionDelete() {
        $task = CStaffManager::getUserRole(CRequest::getInt("id"));
        $task->remove();
        $this->redirect("?action=index");
    }
    public function actionSave() {
        $task = new CUserRole();
        $task->setAttributes(CRequest::getArray($task::getClassName()));
        if ($task->validate()) {
            $task->save();
            if ($this->continueEdit()) {
                $this->redirect("tasks.php?action=edit&id=".$task->getId());
            } else {
                $this->redirect("tasks?action=index");
            }
            return true;
        }
        $this->setData("task", $task);
        $this->renderView("_users/tasks/edit.tpl");
    }
}