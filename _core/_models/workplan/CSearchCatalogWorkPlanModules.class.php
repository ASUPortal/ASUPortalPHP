<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 06.09.15
 * Time: 19:49
 */

class CSearchCatalogWorkPlanModules extends CAbstractSearchCatalog {

    public function actionTypeAhead($lookup){
        $result = array();
        // выбор сотрудников
        $query = new CQuery();
        $query->select("module.id as id, module.title as name")
            ->from(TABLE_WORK_PLAN_CONTENT_MODULES." as module")
            ->condition("module.title like '%".$lookup."%' AND plan_id=".CRequest::getInt("plan_id"))
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id) {
        $result = array();
        $obj = $this->actionGetObject($id);
        if (!is_null($obj)) {
            $result[$obj->getId()] = $obj->title;
        }
        return $result;
    }

    public function actionGetViewData() {
        $result = array();
        // выбор сотрудников
        $query = new CQuery();
        $query->select("module.id as id, module.title as name")
            ->from(TABLE_WORK_PLAN_CONTENT_MODULES." as module")
            ->condition("plan_id=".CRequest::getInt("plan_id"))
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetCreationActionUrl() {
        return "";
    }

    public function actionGetObject($id) {
        return CBaseManager::getWorkPlanContentCategory($id);
    }
}