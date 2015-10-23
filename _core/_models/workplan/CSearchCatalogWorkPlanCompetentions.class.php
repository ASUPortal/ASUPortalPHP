<?php

class CSearchCatalogWorkPlanCompetentions extends CAbstractSearchCatalog {

    public function actionTypeAhead($lookup){
        $result = array();
        $query = new CQuery();
        $query->select("term.id as id, term.name as name")
            ->from(TABLE_WORK_PLAN_COMPETENTIONS." as competention")
            ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = competention.competention_id")
            ->condition("term.name like '%".$lookup."%' AND competention.plan_id=".CRequest::getInt("plan_id")." AND competention.type=0")
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
        $query->select("term.id as id, term.name as name")
            ->from(TABLE_WORK_PLAN_COMPETENTIONS." as competention")
            ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = competention.competention_id")
            ->condition("competention.plan_id=".CRequest::getInt("plan_id")." AND competention.type=0")
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
        return CTaxonomyManager::getTerm($id);
    }
}