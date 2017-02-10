<?php

class CSearchCatalogOrdersIndPlan extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup) {
        $person = CStaffManager::getPerson(CRequest::getInt("person_id"));;
        $year = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
        $result = array();
        $result = CStaffService::getActiveOrdersListForYear($person, $year);
        return $result;
    }

    public function actionGetItem($id) {
        $result = array();
        $order = CStaffManager::getOrder($id);
        if (!is_null($order)) {
            $result[$order->getId()] = "Приказ № ".$order->num_order." от ".$order->date_order." (".$order->rate.")";
        }
        return $result;
    }

    public function actionGetViewData() {
        $person = CStaffManager::getPerson(CRequest::getInt("person_id"));;
        $year = CTaxonomyManager::getYear(CRequest::getInt("year_id"));
        $result = array();
        $result = CStaffService::getActiveOrdersListForYear($person, $year);
        return $result;
    }

    public function actionGetCreationActionUrl() {
        return "";
    }

    public function actionGetObject($id) {
        return CStaffManager::getOrder($id);
    }

}