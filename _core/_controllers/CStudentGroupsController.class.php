<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 23.02.13
 * Time: 18:38
 * To change this template use File | Settings | File Templates.
 */
class CStudentGroupsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Учебные группы студентов");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("st_group.*")
            ->from(TABLE_STUDENT_GROUPS." as st_group")
            ->order("st_group.id desc");
        $set->setQuery($query);
        /**
         * Фильтры пока никакие не делаю, так как некогда
         */
        /**
         * Финишная выборка
         */
        $groups = new CArrayList();
        foreach($set->getPaginated()->getItems() as $item) {
            $group = new CStudentGroup($item);
            $groups->add($group->getId(), $group);
        }
        $this->setData("groups", $groups);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_student_groups/index.tpl");
    }
    public function actionEdit() {
        $group = CStaffManager::getStudentGroup(CRequest::getInt("id"));
        $students = array();
        foreach ($group->getStudents()->getItems() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->setData("group", $group);
        $this->setData("students", $students);
        $this->renderView("_student_groups/edit.tpl");
    }
    public function actionAdd() {
        $group = new CStudentGroup();
        $this->setData("group", $group);
        $this->renderView("_student_groups/add.tpl");
    }
    public function actionSave() {
        $group = new CStudentGroup();
        $group->setAttributes(CRequest::getArray($group::getClassName()));
        if ($group->validate()) {
            $group->save();
            $this->redirect("?action=index");
            return true;
        }
        $students = array();
        foreach ($group->getStudents()->getItems() as $student) {
            $students[$student->getId()] = $student->getName();
        }
        $this->setData("group", $group);
        $this->setData("students", $students);
        $this->renderView("_student_groups/edit.tpl");
    }
}
