<?php

class CSearchCatalogCorriculumDisciplinesTerms extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup) {
        $result = array();
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $discipline = CCorriculumsManager::getDiscipline($plan->corriculum_discipline_id);
        $query = new CQuery();
        $query->select("term.id as id, term.title as name")
            ->from(TABLE_CORRICULUM_DISCIPLINE_SECTIONS." as term")
            ->condition("term.title like '%".$lookup."%' AND discipline_id=".$discipline->getId())
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id) {
        $result = array();
        /**
         * @var $term CWorkPlanTerm
         */
        $term = CBaseManager::getCorriculumDisciplineSection($id);
        if (!is_null($term)) {
            $result[$term->getId()] = $term->title;
        }
        return $result;
    }

    public function actionGetViewData() {
        $result = array();
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $discipline = CCorriculumsManager::getDiscipline($plan->corriculum_discipline_id);
        $query = new CQuery();
        $query->select("term.id as id, term.title as name")
            ->from(TABLE_CORRICULUM_DISCIPLINE_SECTIONS." as term")
            ->condition("discipline_id=".$discipline->getId());
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetCreationActionUrl() {
        return "";
    }

    public function actionGetObject($id) {
        return CBaseManager::getCorriculumDisciplineSection($id);
    }

}