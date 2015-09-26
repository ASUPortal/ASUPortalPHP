<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 26.09.15
 * Time: 20:36
 */

class CSearchCatalogWorkPlanDisciplineModules extends CAbstractSearchCatalog{

    public function actionTypeAhead($lookup) {
        $result = array();
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $query = new CQuery();
        $query->select("parent.id as id, subject.name as name")
            ->from(TABLE_CORRICULUM_DISCIPLINES." as discipline")
            ->innerJoin(TABLE_CORRICULUM_DISCIPLINES." as parent", "parent.id = discipline.parent_id")
            ->innerJoin(TABLE_DISCIPLINES." as subject", "parent.discipline_id = subject.id")
            ->condition("subject.name like '%".$lookup."%' AND discipline.id=".$plan->corriculum_discipline_id)
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id) {
        $result = array();
        $discipline = CCorriculumsManager::getDiscipline($id);
        if (!is_null($discipline)) {
            $result[$discipline->getId()] = $discipline->discipline->getValue();
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $query = new CQuery();
        $query->select("parent.id as id, subject.name as name")
            ->from(TABLE_CORRICULUM_DISCIPLINES." as discipline")
            ->innerJoin(TABLE_CORRICULUM_DISCIPLINES." as parent", "parent.id = discipline.parent_id")
            ->innerJoin(TABLE_DISCIPLINES." as subject", "parent.discipline_id = subject.id")
            ->condition("discipline.id = ".$plan->corriculum_discipline_id);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetCreationActionUrl()
    {
        return "";
    }
}