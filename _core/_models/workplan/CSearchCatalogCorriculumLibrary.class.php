<?php

class CSearchCatalogCorriculumLibrary extends CAbstractSearchCatalog {

    public function actionTypeAhead($lookup) {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
    	$codeDiscipl = $plan->corriculumDiscipline->codeFromLibrary;
        $result = array();
        $query = new CQuery();
        $query->select("library.id as id, library.book_name as name")
            ->from(TABLE_CORRICULUM_LIBRARY." as library")
            ->condition("library.discipline_code_id = ".$codeDiscipl);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id) {
        $result = array();
        $obj = $this->actionGetObject($id);
        if (!is_null($obj)) {
            $result[$obj->getId()] = $obj->book_name;
        }
        return $result;
    }

    public function actionGetViewData() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
    	$codeDiscipl = $plan->corriculumDiscipline->codeFromLibrary;
        $result = array();
        $query = new CQuery();
        $query->select("library.id as id, library.book_name as name")
            ->from(TABLE_CORRICULUM_LIBRARY." as library")
            ->condition("library.discipline_code_id = ".$codeDiscipl);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetCreationActionUrl() {
        return "";
    }

    public function actionGetObject($id) {
        return CBaseManager::getCorriculumLibrary($id);
    }
}