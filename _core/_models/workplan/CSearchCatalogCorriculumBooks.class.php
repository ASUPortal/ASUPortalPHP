<?php

class CSearchCatalogCorriculumBooks extends CAbstractSearchCatalog {

    public function actionTypeAhead($lookup) {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
    	$codeDiscipl = $plan->corriculumDiscipline->codeFromLibrary;
        $result = array();
        $query = new CQuery();
        $query->select("books.id as id, books.book_name as name")
            ->from(TABLE_CORRICULUM_BOOKS." as books")
            ->innerJoin(TABLE_CORRICULUM_DISCIPLINE_BOOKS." as disc_books", "books.id = disc_books.book_id")
            ->condition("disc_books.discipline_code_from_library = ".$codeDiscipl);
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
        $query->select("books.id as id, books.book_name as name")
            ->from(TABLE_CORRICULUM_BOOKS." as books")
            ->innerJoin(TABLE_CORRICULUM_DISCIPLINE_BOOKS." as disc_books", "books.id = disc_books.book_id")
            ->condition("disc_books.discipline_code_from_library = ".$codeDiscipl);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetCreationActionUrl() {
        return "";
    }

    public function actionGetObject($id) {
        return CBaseManager::getCorriculumBook($id);
    }
}