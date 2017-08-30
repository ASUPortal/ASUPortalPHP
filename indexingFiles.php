<?php
	//добавление файлов в индекс Solr
    require_once("core.php");
    foreach (CActiveRecordProvider::getWithCondition(TABLE_SETTINGS, "solr=-1")->getItems() as $setting) {
        $coreId = CSettingsManager::getSetting($setting->getId());
        CApp::getApp()->search->updateIndex($coreId);
    }
?>