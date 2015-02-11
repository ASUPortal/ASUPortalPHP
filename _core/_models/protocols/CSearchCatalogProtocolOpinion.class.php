<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 28.05.14
 * Time: 21:01
 * To change this template use File | Settings | File Templates.
 */

class CSearchCatalogProtocolOpinion extends  CAbstractSearchCatalog {
    public function actionTypeAhead($lookup)
    {
        $result = array();
        $query = new CQuery();
        $query->select("opinion.id as id, opinion.name as name")
            ->from(TABLE_PROTOCOL_OPINIONS." as opinion")
            ->condition("opinion.name like '%".$lookup."%'")
            ->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        $opinion = CProtocolManager::getProtocolOpinion($id);
        if (!is_null($opinion)) {
            $result[$opinion->getId()] = $opinion->name;
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        foreach (CActiveRecordProvider::getAllFromTable(TABLE_PROTOCOL_OPINIONS)->getItems() as $ar) {
            $opinion = new CProtocolOpinion($ar);
            $result[$opinion->getId()] = $opinion->name;
        }
        return $result;
    }

    public function actionGetCreationActionUrl()
    {
        // TODO: Implement actionGetCreationActionUrl() method.
    }

}