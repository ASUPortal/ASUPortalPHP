<?php

class CSettingsSearchController extends CBaseController {
	public function __construct() {
		if (!CSession::isAuth()) {
			$action = CRequest::getString("action");
			if ($action == "") {
				$action = "index";
			}
			if (!in_array($action, $this->allowedAnonymous)) {
				$this->redirectNoAccess();
			}
		}
	
		$this->_smartyEnabled = true;
		$this->setPageTitle("Настройки поиска по индексу Solr");
	
		parent::__construct();
	}
    public function actionIndex() {
        $set = CActiveRecordProvider::getWithCondition(TABLE_SETTINGS, "solr=-1", "title asc");
        $settings = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $setting = new CSetting($item);
            $settings->add($setting->getId(), $setting);
        }
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "index.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	),
        	array(
        		"title" => "Добавить коллекцию",
        		"icon" => "actions/list-add.png",
        		"link" => "settings.php?action=add"
        	),
        	array(
        		"title" => "Обновить файловый индекс для всех коллекций",
        		"icon" => "actions/document-print-preview.png",
        		"link" => "settings.php?action=updateIndexFiles"
        	)
        ));
        $this->setData("settings", $settings);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_search/_settings/index.tpl");
    }
    public function actionAdd() {
        $setting = new CSetting();
        $setting->solr = -1;
        $setting->alias = "solrCore_";
        $setting->value = "solr/";
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "settings.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->setData("setting", $setting);
        $this->renderView("_search/_settings/add.tpl");
    }
    public function actionEdit() {
        $setting = CSettingsManager::getSetting(CRequest::getInt("id"));
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "settings.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	),
        	array(
        		"title" => "Обновить файловый индекс для коллекции",
        		"icon" => "actions/document-print-preview.png",
        		"link" => "index.php?action=updateIndexFiles&core_id=".CRequest::getInt("id")
        	)
        ));
        $this->setData("setting", $setting);
        $this->renderView("_search/_settings/edit.tpl");
    }
    public function actionDelete() {
        $setting = CSettingsManager::getSetting(CRequest::getInt("id"));
        $setting->remove();
        $this->redirect("?action=index");
    }
    public function actionSave() {
        $setting = new CSetting();
        $setting->setAttributes(CRequest::getArray($setting::getClassName()));
        $cacheKeyString = CACHE_APPLICATION_SETTINGS . '_' . strtoupper($setting->alias);
        $cacheKeyNumeric = CACHE_APPLICATION_SETTINGS . '_' . $setting->id;
        if ($setting->validate()) {
            CApp::getApp()->cache->set($cacheKeyString, $setting);
            CApp::getApp()->cache->set($cacheKeyNumeric, $setting);
            $setting->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$setting->getId());
            } else {
                $this->redirect("?action=index");
            }
            return true;
        }
        $this->setData("setting", $setting);
        $this->renderView("_search/_settings/edit.tpl");
    }
    public function actionUpdateIndexFiles() {
        $messages = array();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_SETTINGS, "solr=-1")->getItems() as $setting) {
            $coreId = CSettingsManager::getSetting($setting->getId());
            $messages[] = CApp::getApp()->search->updateIndex($coreId);
        }
        $results = array();
        foreach ($messages as $message) {
            foreach ($message as $result) {
                $results[] = $result;
            }
        }
        $this->setData("messages", $results);
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "settings.php?action=index",
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->renderView("_search/updateIndexFiles.tpl");
    }
}
