<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 16.06.15
 * Time: 23:05
 */

class CSearchCatalogWorkPlanTerms extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup) {
        $result = array();
        // выбор сотрудников
        $query = new CQuery();
        $query->select("term.id as id, disc_term.title as name")
            ->from(TABLE_WORK_PLAN_TERMS." as term")
            ->innerJoin(TABLE_CORRICULUM_DISCIPLINE_SECTIONS." as disc_term", "disc_term.id = term.number")
            ->condition("disc_term.title like '%".$lookup."%' AND term.plan_id=".CRequest::getInt("plan_id"))
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
        $term = CBaseManager::getWorkPlanTerm($id);
        if (!is_null($term)) {
            $result[$term->getId()] = $term->corriculum_discipline_section->title;
        }
        return $result;
    }

    public function actionGetViewData() {
        $result = array();
        // выбор сотрудников
        $query = new CQuery();
        $query->select("term.id as id, disc_term.title as name")
            ->from(TABLE_WORK_PLAN_TERMS." as term")
            ->innerJoin(TABLE_CORRICULUM_DISCIPLINE_SECTIONS." as disc_term", "disc_term.id = term.number")
            ->condition("term.plan_id=".CRequest::getInt("plan_id"))
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
        return CBaseManager::getWorkPlanTerm($id);
    }

}