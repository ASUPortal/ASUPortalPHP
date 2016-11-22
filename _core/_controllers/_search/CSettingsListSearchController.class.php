<?php

class CSettingsListSearchController extends CBaseController {
	protected $_isComponent = true;
	
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
		$this->setPageTitle("Список настроек поиска по индексу Solr");
	
		parent::__construct();
	}
    public function actionIndex() {
    	$set = new CRecordSet();
    	$query = new CQuery();
    	$set->setQuery($query);
    	$query->select("t.*")
	    	->from(TABLE_SETTINGS." as t")
	    	->order("t.title asc")
	    	->condition("solr=".CRequest::getInt("core_id"));
        $settings = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $setting = new CSetting($item);
            $settings->add($setting->getId(), $setting);
        }
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Обновить",
        		"link" => "settingsList.php?action=index&core_id=".CRequest::getInt("core_id"),
        		"icon" => "actions/view-refresh.png"
        	),
        	array(
        		"title" => "Добавить настройки",
        		"icon" => "actions/list-add.png",
        		"link" => "settingsList.php?action=add&core_id=".CRequest::getInt("core_id")
        	),
        	array(
        		"title" => "Удалить все настройки",
        		"icon" => "actions/edit-delete.png",
        		"link" => "settingsList.php?action=deleteAll&core_id=".CRequest::getInt("core_id")
        	)
        ));
        $this->setData("settings", $settings);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_search/_settingsList/index.tpl");
    }
    public function actionAdd() {
        $setting = new CSetting();
        $setting->title = "Адрес FTP-сервера";
        $setting->alias = "ftp_server";
        $setting->value = CSettingsManager::getSettingValue("ftpServer");
        $setting->solr = CRequest::getInt("core_id");
        $setting->save();
        
        $setting = new CSetting();
        $setting->title = "Путь к локальной папке с файлами для индексирования";
        $setting->alias = "path_for_indexing_files";
        $setting->value = "";
        $setting->solr = CRequest::getInt("core_id");
        $setting->save();
        
        $setting = new CSetting();
        $setting->title = "Форматы файлов для индексирования";
        $setting->alias = "formats_files_for_indexing";
        $setting->value = CSettingsManager::getSettingValue("formatsFilesForIndexing");
        $setting->solr = CRequest::getInt("core_id");
        $setting->save();
        
        $setting = new CSetting();
        $setting->title = "Пользователь FTP-сервера";
        $setting->alias = "ftp_server_user";
        $setting->value = CSettingsManager::getSettingValue("ftpServerUser");
        $setting->solr = CRequest::getInt("core_id");
        $setting->save();
        
        $setting = new CSetting();
        $setting->title = "Пароль FTP-сервера";
        $setting->alias = "ftp_server_password";
        $setting->value = CSettingsManager::getSettingValue("ftpServerPassword");
        $setting->solr = CRequest::getInt("core_id");
        $setting->save();
        
        $setting = new CSetting();
        $setting->title = "Путь к папке с файлами для индексирования на FTP-сервере";
        $setting->alias = "path_for_indexing_files_from_ftp";
        $setting->value = "";
        $setting->solr = CRequest::getInt("core_id");
        $setting->save();
        
        $this->redirect("settingsList.php?action=index&core_id=".CRequest::getInt("core_id"));
    }
    public function actionEdit() {
        $setting = CSettingsManager::getSetting(CRequest::getInt("id"));
        $this->addActionsMenuItem(array(
        	array(
        		"title" => "Назад",
        		"link" => "settingsList.php?action=index&core_id=".$setting->solr,
        		"icon" => "actions/edit-undo.png"
        	)
        ));
        $this->setData("setting", $setting);
        $this->renderView("_search/_settingsList/edit.tpl");
    }
    public function actionDelete() {
        $setting = CSettingsManager::getSetting(CRequest::getInt("id"));
        $coreId = $setting->solr;
        $setting->remove();
        $this->redirect("settingsList.php?action=index&core_id=".$coreId);
    }
    public function actionDeleteAll() {
        $core = CSettingsManager::getSetting(CRequest::getInt("core_id"));
        foreach (CActiveRecordProvider::getWithCondition(TABLE_SETTINGS, "solr=".$core->getId())->getItems() as $ar) {
            $object = new CSetting($ar);
            $object->remove();
        }
        $this->redirect("settingsList.php?action=index&core_id=".$core->getId());
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
                $this->redirect("settingsList.php?action=edit&id=".$setting->getId());
            } else {
                $this->redirect("settingsList.php?action=index&core_id=".$setting->solr);
            }
            return true;
        }
        $this->setData("setting", $setting);
        $this->renderView("_search/_settingsList/edit.tpl");
    }
}
