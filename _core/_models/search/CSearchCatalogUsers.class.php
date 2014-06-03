<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.06.14
 * Time: 20:07
 * To change this template use File | Settings | File Templates.
 */

class CSearchCatalogUsers implements ISearchCatalogInterface{
    public function actionTypeAhead($lookup)
    {
        $result = array();
        // выбор пользователей
        $query = new CQuery();
        $query->select("user.id as id, user.fio as name")
            ->from(TABLE_USERS." as user")
            ->condition("user.fio like '%".$lookup."%'")
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        // выбор пользователей
        $user = CStaffManager::getUser($id);
        if (!is_null($user)) {
            $result[$user->getId()] = $user->getName();
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        foreach (CStaffManager::getAllUsers()->getItems() as $user) {
            $result[$user->getId()] = $user->getName();
        }
        return $result;
    }

    public function actionGetCreationActionUrl()
    {
        // TODO: Implement actionGetCreationActionUrl() method.
    }

}