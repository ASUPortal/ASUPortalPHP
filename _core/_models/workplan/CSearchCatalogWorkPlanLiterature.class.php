<?php

class CSearchCatalogWorkPlanLiterature extends CAbstractSearchCatalog {

    public function actionTypeAhead($lookup){
        $result = array();
        $query = new CQuery();
        $query->select("literature.id as id, term.book_name as name")
            ->from(TABLE_WORK_PLAN_LITERATURE." as literature")
            ->innerJoin(TABLE_CORRICULUM_BOOKS." as term", "term.id = literature.book_id")
            ->condition("term.book_name like '%".$lookup."%' AND literature.plan_id=".CRequest::getInt("plan_id"))
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
            $result[$obj->getId()] = $obj->book->book_name;
        }
        return $result;
    }

    public function actionGetViewData() {
        $result = array();
        $query = new CQuery();
        $query->select("literature.id as id, term.book_name as name")
            ->from(TABLE_WORK_PLAN_LITERATURE." as literature")
            ->innerJoin(TABLE_CORRICULUM_BOOKS." as term", "term.id = literature.book_id")
            ->condition("literature.plan_id=".CRequest::getInt("plan_id"))
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
        return CBaseManager::getWorkPlanLiterature($id);
    }
}