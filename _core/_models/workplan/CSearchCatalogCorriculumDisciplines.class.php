<?php

class CSearchCatalogCorriculumDisciplines extends CAbstractSearchCatalog {

    public function actionTypeAhead($lookup) {
        $result = array();
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $discipline = CCorriculumsManager::getDiscipline($plan->corriculum_discipline_id);
        $corriculum = $discipline->cycle->corriculum;
        $query = new CQuery();
        $query->select("discipline.id as id, subject.name as name")
	        ->from(TABLE_CORRICULUM_DISCIPLINES." as discipline")
	        ->innerJoin(TABLE_CORRICULUM_CYCLES." as cycle", "discipline.cycle_id = cycle.id")
	        ->innerJoin(TABLE_DISCIPLINES." as subject", "subject.id = discipline.discipline_id")
	        ->condition("subject.name like '%".$lookup."%' AND cycle.corriculum_id=".$corriculum->getId())
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id) {
        $result = array();
        $obj = $this->actionGetObject($id);
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
        $discipline = CCorriculumsManager::getDiscipline($plan->corriculum_discipline_id);
        $corriculum = $discipline->cycle->corriculum;
        $query = new CQuery();
        $query->select("discipline.id as id, subject.name as name")
            ->from(TABLE_CORRICULUM_DISCIPLINES." as discipline")
            ->innerJoin(TABLE_CORRICULUM_CYCLES." as cycle", "discipline.cycle_id = cycle.id")
            ->innerJoin(TABLE_DISCIPLINES." as subject", "discipline.discipline_id = subject.id")
            ->condition("cycle.corriculum_id=".$corriculum->getId());
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetCreationActionUrl()
    {
        return "";
    }
    
    public function actionGetObject($id) {
    	return CBaseManager::getCorriculumDiscipline($id);
    }
}