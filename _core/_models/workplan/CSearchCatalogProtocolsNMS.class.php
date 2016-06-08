<?php

class CSearchCatalogProtocolsNMS extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup) {
        $result = array();
        $query = new CQuery();
        $query->select("protocol.id as id, protocol.num as name, protocol.date_text as date")
	        ->from(TABLE_NMS_PROTOCOL." as protocol")
	        ->order("protocol.date_text desc");
        foreach ($query->execute()->getItems() as $item) {
        	$result[$item["id"]] = $item["name"]." от ".$item["date"];
        }
        return $result;
    }

    public function actionGetItem($id) {
        $result = array();
        $protocol = CProtocolManager::getNMSProtocol($id);
        if (!is_null($protocol)) {
            $result[$protocol->getId()] = $protocol->num." от ".$protocol->date_text;
        }
        return $result;
    }

    public function actionGetViewData() {
        $result = array();
        $query = new CQuery();
        $query->select("protocol.id as id, protocol.num as name, protocol.date_text as date")
	        ->from(TABLE_NMS_PROTOCOL." as protocol")
	        ->order("protocol.date_text desc");
        foreach ($query->execute()->getItems() as $item) {
        	$result[$item["id"]] = $item["name"]." от ".$item["date"];
        }
        return $result;
    }

    public function actionGetCreationActionUrl() {
        return "";
    }

    public function actionGetObject($id) {
        return CProtocolManager::getNMSProtocol($id);
    }

}