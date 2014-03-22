<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.02.14
 * Time: 20:59
 * To change this template use File | Settings | File Templates.
 */

class CSearchCatalogStudent extends CComponent implements ISearchCatalogInterface{
    public function actionTypeAhead($lookup)
    {
        $result = array();
        // выбор студентов
        $query = new CQuery();
        $query->select("distinct(student.id) as id, student.fio as name")
            ->from(TABLE_STUDENTS." as student")
            ->condition("student.fio like '%".$lookup."%'")
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        // выбор студентов
        $student = CStaffManager::getStudent($id);
        if (!is_null($student)) {
            $result[$student->getId()] = $student->getName();
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        // выбор студентов
        foreach (CStaffManager::getAllStudents()->getItems() as $student) {
            $result[$student->getId()] = $student->getName();
        }
        return $result;
    }
    public function actionGetCreationActionUrl()
    {
        // TODO: Implement actionGetCreationActionUrl() method.
    }
}