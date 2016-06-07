<?php

class CSearchCatalogProtocolsDep extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup) {
        $result = array();
        $query = new CQuery();
        $query->select("protocol.id as id, protocol.num as name, protocol.date_text as date")
	        ->from(TABLE_DEPARTMENT_PROTOCOLS." as protocol")
	        ->order("protocol.date_text desc");
        foreach ($query->execute()->getItems() as $item) {
        	$result[$item["id"]] = $item["name"]." от ".date("d.m.Y", strtotime($item["date"]));
        }
        return $result;
    }

    public function actionGetItem($id) {
        $result = array();
        $protocol = CProtocolManager::getDepProtocol($id);
        if (!is_null($protocol)) {
            $result[$protocol->getId()] = $protocol->num." от ".date("d.m.Y", strtotime($protocol->date_text));
        }
        return $result;
    }

    public function actionGetViewData() {
        $result = array();
        $query = new CQuery();
        $query->select("protocol.id as id, protocol.num as name, protocol.date_text as date")
	        ->from(TABLE_DEPARTMENT_PROTOCOLS." as protocol")
	        ->order("protocol.date_text desc");
        foreach ($query->execute()->getItems() as $item) {
        	$result[$item["id"]] = $item["name"]." от ".date("d.m.Y", strtotime($item["date"]));
        }
        return $result;
    }

    public function actionGetCreationActionUrl() {
        return "";
    }

    public function actionGetObject($id) {
        return CProtocolManager::getDepProtocol($id);
    }

}