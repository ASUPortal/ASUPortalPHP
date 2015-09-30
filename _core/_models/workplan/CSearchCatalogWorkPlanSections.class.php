<?php

class CSearchCatalogWorkPlanSections extends CAbstractSearchCatalog {

    public function actionTypeAhead($lookup){
        $result = array();
        $query = new CQuery();
        $query->select("section.id as id, section.name as name")
            ->from(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section")
            ->innerJoin(TABLE_WORK_PLAN_CONTENT_MODULES." as module", "module.id = section.module_id")
            ->condition("section.name like '%".$lookup."%' AND module.plan_id=".CRequest::getInt("plan_id"))
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
            $result[$obj->getId()] = $obj->name;
        }
        return $result;
    }

    public function actionGetViewData() {
        $result = array();
        $query = new CQuery();
        $query->select("section.id as id, section.name as name")
            ->from(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section")
            ->innerJoin(TABLE_WORK_PLAN_CONTENT_MODULES." as module", "module.id = section.module_id")
            ->condition("module.plan_id=".CRequest::getInt("plan_id"))
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
        return CBaseManager::getWorkPlanContentSection($id);
    }
}