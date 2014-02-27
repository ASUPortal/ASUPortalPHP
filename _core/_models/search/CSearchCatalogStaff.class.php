<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.02.14
 * Time: 20:58
 * To change this template use File | Settings | File Templates.
 */

class CSearchCatalogStaff implements ISearchCatalogInterface{
    public function actionTypeAhead($lookup)
    {
        $result = array();
        // выбор сотрудников
        $query = new CQuery();
        $query->select("person.id as id, person.fio as name")
            ->from(TABLE_PERSON." as person")
            ->condition("person.fio like '%".$lookup."%'")
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        // выбор сотрудников
        $person = CStaffManager::getPerson($id);
        if (!is_null($person)) {
            $result[$person->getId()] = $person->getName();
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        // выбор сотрудников
        foreach (CStaffManager::getAllPersons()->getItems() as $person) {
            $result[$person->getId()] = $person->getName();
        }
        return $result;
    }

}