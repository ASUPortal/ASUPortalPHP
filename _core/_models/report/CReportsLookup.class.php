<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 19.04.14
 * Time: 16:33
 * To change this template use File | Settings | File Templates.
 */

class CReportsLookup extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup)
    {
        $result = array();
        // выбор активных отчетов
        $query = new CQuery();
        $query->select("report.id as id, report.title as name")
            ->from(TABLE_REPORTS." as report")
            ->condition("report.title like '%".$lookup."%' and report.active=1")
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        // выбор отчетов
        $report = CReportManager::getReport($id);
        if (!is_null($report)) {
            $result[$report->getId()] = $report->title;
        }
        return $result;
    }

    public function actionGetViewData()
    {
        // $result = array();
        // выбор активных отчетов
        $query = new CQuery();
        $query->select("report.id as id, report.title as name")
            ->from(TABLE_REPORTS." as report");
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetCreationActionUrl()
    {
        // TODO: Implement actionGetCreationActionUrl() method.
    }

}