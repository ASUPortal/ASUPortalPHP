<?php
/**
 * Каталог для выбора преподавателей, имеющих нагрузку по дисциплине
 */
class CSearchCatalogLecturersWithLoadByDiscipline extends CAbstractSearchCatalog {

    public function actionTypeAhead($lookup){
        $result = array();
        $discipline = CTaxonomyManager::getDiscipline(CRequest::getInt("discipline_id"));
        $lecturers = CStudyLoadService::getLecturersNameByDiscipline($discipline);
        foreach ($lecturers->getItems() as $lecturer) {
            $result[$lecturer->getId()] = $lecturer->getName();
        }
        return $result;
    }

    public function actionGetItem($id) {
        $result = array();
        $obj = $this->actionGetObject($id);
        if (!is_null($obj)) {
            $result[$obj->getId()] = $obj->getName();
        }
        return $result;
    }

    public function actionGetViewData() {
        $result = array();
        $discipline = CTaxonomyManager::getDiscipline(CRequest::getInt("discipline_id"));
        $lecturers = CStudyLoadService::getLecturersNameByDiscipline($discipline);
        foreach ($lecturers->getItems() as $lecturer) {
        	$result[$lecturer->getId()] = $lecturer->getName();
        }
        return $result;
    }

    public function actionGetCreationActionUrl() {
        return "";
    }

    public function actionGetObject($id) {
        return CStaffManager::getPerson($id);
    }
}