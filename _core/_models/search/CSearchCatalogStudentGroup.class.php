<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.02.14
 * Time: 21:05
 * To change this template use File | Settings | File Templates.
 */

class CSearchCatalogStudentGroup extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup)
    {
        $result = array();
        // выбор студенческих групп
        $query = new CQuery();
        $query->select("distinct(gr.id) as id, gr.name as name")
            ->from(TABLE_STUDENT_GROUPS." as gr")
            ->condition("gr.name like '%".$lookup."%'")
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        // группы студентов
        $group = CStaffManager::getStudentGroup($id);
        if (!is_null($group)) {
            $result[$group->getId()] = $group->getName();
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        // выбор студенческих групп
        foreach (CStaffManager::getAllStudentGroups()->getItems() as $group) {
            $result[$group->getId()] = $group->getName();
        }
        return $result;
    }
    public function actionGetCreationActionUrl()
    {
        // TODO: Implement actionGetCreationActionUrl() method.
    }

    public function actionGetObject($id)
    {
        return CStaffManager::getStudentGroup($id);
    }

}