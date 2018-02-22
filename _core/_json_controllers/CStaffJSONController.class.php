<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 23.09.12
 * Time: 14:57
 * To change this template use File | Settings | File Templates.
 */
class CStaffJSONController extends CBaseJSONController {
    /**
     * Сотрудники, которым обязательно присутствовать на заседании кафедры
     * (т.е. имеют активный приказ) и обладают указанными ролями
     */
    public function actionGetStaffWithRolesForProtocol() {
        // получаем из POST-запроса роли
        $roles = new CArrayList();
        foreach (explode(",", CRequest::getString("roles")) as $val) {
            $roles->add($val, $val);
        }
        $persons = CStaffManager::getPersonsWithTypes($roles);
        $compulsory = array();
        $unCompulsory = array();
        foreach ($persons->getItems() as $person) {
            if ($person->hasActiveOrder()) {
                $compulsory[$person->getId()] = $person->getName();
            } else {
                $unCompulsory[$person->getId()] = $person->getName();
            }
        }
        echo json_encode(array(
            "compulsory" => $compulsory,
            "uncompulsory" => $unCompulsory
        ));
    }
    /**
     * Массив ключ-значение сотрудников с указанными ролями
     */
    public function actionGetStaffWithRoles() {
        // получаем из POST-запроса роли
        $res = array();
        $roles = new CArrayList();
        foreach (explode(",", CRequest::getString("roles")) as $val) {
            $roles->add($val, $val);
        }
        $persons = CStaffManager::getPersonsWithTypes($roles);
        foreach ($persons->getItems() as $person) {
            $res[$person->getId()] = $person->getName();
        }
        echo json_encode($res);
    }
    /**
     * Все сотрудники кафедры
     */
    public function actionGetAllStaff() {
        $res = array();
        $persons = CStaffManager::getAllPersons();
        foreach ($persons->getItems() as $person) {
            $res[$person->getId()] = $person->getName();
        }
        echo json_encode($res);
    }
    /**
     * Все роли пользователей
     */
    public function actionGetStaffRoles() {
        $res = array();
        foreach (CTaxonomyManager::getCacheTypes()->getItems() as $role) {
            $res[$role->getId()] = $role->getValue();
        }
        echo json_encode($res);
    }

    /**
     * Получить всех студентов учебной группы
     */
    public function actionGetStudentsByGroup() {
        $group = CRequest::getInt("group");
        $res = array();
        foreach (CStaffManager::getStudentGroup($group)->getStudents()->getItems() as $student) {
            $res["0".$student->getId()] = $student->getName();
        }
        echo json_encode($res);
    }
}
